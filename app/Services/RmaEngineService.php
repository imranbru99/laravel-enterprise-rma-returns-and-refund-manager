<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ReturnPolicy;
use App\Models\Rma;
use App\Models\RmaItem;
use App\Models\RmaStatusHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RmaEngineService
{
    public function __construct(
        protected TenantContext $tenantContext,
        protected AuditLogService $auditLogService
    ) {}

    /**
     * Generate a sequential and unique RMA number.
     */
    public function generateRmaNumber(): string
    {
        $prefix = 'RMA-'.date('Ym').'-';
        $random = strtoupper(bin2hex(random_bytes(3)));

        return $prefix.$random;
    }

    /**
     * Evaluate return eligibility for an order and items.
     *
     * @param  array<int, array{order_item_id: int, quantity: int}>  $items
     * @return array{is_eligible: bool, reason: string|null, eligible_items: array}
     */
    public function evaluateEligibility(Order $order, array $items = []): array
    {
        // 1. Check order status
        if (! in_array($order->status, ['completed', 'delivered', 'shipped'])) {
            return [
                'is_eligible' => false,
                'reason' => 'Returns can only be requested for orders that have been shipped or delivered.',
                'eligible_items' => [],
            ];
        }

        // 2. Fetch tenant active policy or default policy
        $policy = ReturnPolicy::where('tenant_id', $order->tenant_id)
            ->where('is_active', true)
            ->first();

        $returnWindowDays = $policy ? $policy->return_window_days : 30;
        $orderDate = $order->delivered_at ?? $order->ordered_at ?? $order->created_at;

        if ($orderDate && Carbon::parse($orderDate)->diffInDays(now()) > $returnWindowDays) {
            return [
                'is_eligible' => false,
                'reason' => "The return window of {$returnWindowDays} days has expired for this order.",
                'eligible_items' => [],
            ];
        }

        // 3. Verify refundable remaining balance
        if ($order->remaining_refundable_amount <= 0) {
            return [
                'is_eligible' => false,
                'reason' => 'This order has already been fully refunded.',
                'eligible_items' => [],
            ];
        }

        $eligibleItems = [];
        foreach ($items as $itemData) {
            $orderItem = OrderItem::find($itemData['order_item_id']);
            if (! $orderItem || $orderItem->order_id !== $order->id) {
                continue;
            }

            if (! $orderItem->product->is_returnable) {
                continue;
            }

            $alreadyReturnedQty = RmaItem::where('order_item_id', $orderItem->id)
                ->whereHas('rma', fn ($q) => $q->whereNotIn('status', ['rejected', 'cancelled']))
                ->sum('quantity');

            $maxReturnable = max(0, $orderItem->quantity - $alreadyReturnedQty);
            if ($maxReturnable > 0) {
                $eligibleItems[] = [
                    'order_item_id' => $orderItem->id,
                    'max_quantity' => $maxReturnable,
                    'unit_price' => (float) $orderItem->unit_price,
                    'product_name' => $orderItem->product->name,
                ];
            }
        }

        return [
            'is_eligible' => count($eligibleItems) > 0,
            'reason' => count($eligibleItems) > 0 ? null : 'No eligible items remaining for return in this order.',
            'eligible_items' => $eligibleItems,
        ];
    }

    /**
     * Create a new RMA record with items and audit trail.
     *
     * @param  array<string, mixed>  $data
     */
    public function createRma(array $data): Rma
    {
        return DB::transaction(function () use ($data) {
            $order = Order::with('items.product')->findOrFail($data['order_id']);

            $tenantId = $data['tenant_id'] ?? $order->tenant_id;
            $branchId = $data['branch_id'] ?? $order->branch_id;

            $policy = ReturnPolicy::where('tenant_id', $tenantId)
                ->where('is_active', true)
                ->first();

            $policyVersion = $policy ? $policy->version : 1;
            $rmaNumber = $this->generateRmaNumber();

            $initialStatus = 'pending_review';
            $autoApproveThreshold = (float) ($policy ? $policy->auto_approve_max_amount : 50.00);

            $estimatedTotal = 0.00;
            $rmaItemsData = $data['items'] ?? [];

            foreach ($rmaItemsData as $itemData) {
                $orderItem = OrderItem::findOrFail($itemData['order_item_id']);
                $qty = (int) ($itemData['quantity'] ?? 1);
                $estimatedTotal += ((float) $orderItem->unit_price) * $qty;
            }

            // Auto-approval logic: low value and eligible
            if ($estimatedTotal <= $autoApproveThreshold && empty($data['force_manual_review'])) {
                $initialStatus = 'approved';
            }

            $priority = $data['priority'] ?? 'normal';
            $slaDueAt = match ($priority) {
                'urgent' => now()->addHours(12),
                'high' => now()->addHours(24),
                'low' => now()->addDays(5),
                default => now()->addDays(2),
            };

            $rma = Rma::create([
                'tenant_id' => $tenantId,
                'company_id' => $order->branch?->company_id,
                'branch_id' => $branchId,
                'destination_branch_id' => $data['destination_branch_id'] ?? $branchId,
                'customer_id' => $order->customer_id,
                'order_id' => $order->id,
                'return_policy_id' => $policy?->id,
                'rma_number' => $rmaNumber,
                'status' => $initialStatus,
                'resolution_type' => $data['resolution_type'] ?? 'refund',
                'return_reason' => $data['return_reason'] ?? 'customer_remorse',
                'customer_notes' => $data['customer_notes'] ?? null,
                'internal_notes' => $data['internal_notes'] ?? null,
                'return_method' => $data['return_method'] ?? 'mail_in',
                'estimated_refund_amount' => $estimatedTotal,
                'approved_refund_amount' => $initialStatus === 'approved' ? $estimatedTotal : 0.00,
                'currency' => $order->currency ?? 'USD',
                'policy_version' => $policyVersion,
                'priority' => $priority,
                'sla_due_at' => $slaDueAt,
                'approved_at' => $initialStatus === 'approved' ? now() : null,
            ]);

            foreach ($rmaItemsData as $itemData) {
                $orderItem = OrderItem::findOrFail($itemData['order_item_id']);
                $qty = (int) ($itemData['quantity'] ?? 1);
                $itemRefund = ((float) $orderItem->unit_price) * $qty;

                RmaItem::create([
                    'rma_id' => $rma->id,
                    'order_item_id' => $orderItem->id,
                    'product_id' => $orderItem->product_id,
                    'product_variant_id' => $orderItem->product_variant_id,
                    'quantity' => $qty,
                    'unit_price' => $orderItem->unit_price,
                    'refund_amount' => $itemRefund,
                    'return_reason' => $data['return_reason'] ?? 'customer_remorse',
                    'condition_stated' => $itemData['condition_stated'] ?? 'like_new',
                    'serial_number' => $itemData['serial_number'] ?? null,
                    'customer_photos' => $itemData['customer_photos'] ?? [],
                    'status' => $initialStatus === 'approved' ? 'approved' : 'pending',
                ]);
            }

            // Create initial status history entry
            RmaStatusHistory::create([
                'rma_id' => $rma->id,
                'user_id' => auth()->id(),
                'from_status' => null,
                'to_status' => $initialStatus,
                'notes' => 'RMA request created'.($initialStatus === 'approved' ? ' (automatically approved under threshold)' : ''),
            ]);

            $this->auditLogService->log(
                event: 'rma.created',
                auditable: $rma,
                newValues: $rma->toArray(),
                justification: 'New RMA initiated by customer / agent'
            );

            return $rma;
        });
    }

    /**
     * Transition the status of an RMA.
     */
    public function transitionStatus(Rma $rma, string $targetStatus, ?string $notes = null, ?int $userId = null): bool
    {
        if (! $rma->canTransitionTo($targetStatus)) {
            throw ValidationException::withMessages([
                'status' => ["Cannot transition RMA from {$rma->status} to {$targetStatus}."],
            ]);
        }

        $oldStatus = $rma->status;

        $updates = ['status' => $targetStatus];
        if ($targetStatus === 'approved' && ! $rma->approved_at) {
            $updates['approved_at'] = now();
            if ($rma->approved_refund_amount <= 0) {
                $updates['approved_refund_amount'] = $rma->estimated_refund_amount;
            }
        }
        if ($targetStatus === 'item_received' && ! $rma->received_at) {
            $updates['received_at'] = now();
        }
        if ($targetStatus === 'closed' && ! $rma->closed_at) {
            $updates['closed_at'] = now();
        }

        $rma->update($updates);

        RmaStatusHistory::create([
            'rma_id' => $rma->id,
            'user_id' => $userId ?? auth()->id(),
            'from_status' => $oldStatus,
            'to_status' => $targetStatus,
            'notes' => $notes,
        ]);

        $this->auditLogService->log(
            event: 'rma.status_changed',
            auditable: $rma,
            oldValues: ['status' => $oldStatus],
            newValues: ['status' => $targetStatus],
            justification: $notes
        );

        return true;
    }
}
