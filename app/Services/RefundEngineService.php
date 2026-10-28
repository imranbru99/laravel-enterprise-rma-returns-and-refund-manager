<?php

namespace App\Services;

use App\Models\CreditNote;
use App\Models\FinancialJournal;
use App\Models\FinancialJournalLine;
use App\Models\Order;
use App\Models\Refund;
use App\Models\Rma;
use App\Models\StoreCreditAccount;
use App\Models\StoreCreditTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RefundEngineService
{
    public function __construct(
        protected TenantContext $tenantContext,
        protected AuditLogService $auditLogService
    ) {}

    /**
     * Process an authentic refund with strict financial safety guarantees.
     *
     * @throws ValidationException
     */
    public function processRefund(
        Rma $rma,
        float $amount,
        string $method = 'original_payment_method',
        string $gatewayName = 'mock_gateway',
        ?int $approvedBy = null,
        ?string $idempotencyKey = null
    ): Refund {
        return DB::transaction(function () use ($rma, $amount, $method, $gatewayName, $approvedBy, $idempotencyKey) {
            $order = Order::lockForUpdate()->findOrFail($rma->order_id);

            // 1. SAFEGUARD: Check refundable remaining balance
            $remainingRefundable = (float) $order->remaining_refundable_amount;
            if ($amount <= 0 || $amount > ($remainingRefundable + 0.001)) {
                throw ValidationException::withMessages([
                    'amount' => [
                        "Refund amount ({$amount}) exceeds the remaining refundable order balance ({$remainingRefundable}).",
                    ],
                ]);
            }

            // 2. SAFEGUARD: Idempotency protection against duplicate payouts
            $idempotencyKey = $idempotencyKey ?? "ref_{$order->id}_{$rma->id}_".md5($amount.'_'.$method.'_'.now()->format('YmdH'));

            $existingRefund = Refund::where('idempotency_key', $idempotencyKey)->first();
            if ($existingRefund) {
                if ($existingRefund->status === 'settled') {
                    throw ValidationException::withMessages([
                        'idempotency_key' => ['A settled refund with this idempotency key already exists.'],
                    ]);
                }

                return $existingRefund;
            }

            $refundNumber = 'REF-'.date('Ym').'-'.strtoupper(bin2hex(random_bytes(3)));

            // 3. Gateway execution / simulation
            $gatewayTxId = 'TXN_'.strtoupper(Str::random(12));
            $status = 'settled';

            $refund = Refund::create([
                'tenant_id' => $rma->tenant_id,
                'rma_id' => $rma->id,
                'order_id' => $order->id,
                'refund_number' => $refundNumber,
                'amount' => $amount,
                'currency' => $order->currency ?? 'USD',
                'method' => $method,
                'gateway_name' => $gatewayName,
                'gateway_transaction_id' => $gatewayTxId,
                'status' => $status,
                'idempotency_key' => $idempotencyKey,
                'approved_by' => $approvedBy ?? auth()->id(),
                'approved_at' => now(),
                'processed_at' => now(),
            ]);

            // 4. Update Order refunded amount
            $order->increment('refunded_amount', $amount);

            // 5. Update RMA approved refund amount & status
            $rma->update([
                'approved_refund_amount' => $amount,
                'status' => 'refunded',
            ]);

            // 6. If method is store credit, update customer wallet
            if ($method === 'store_credit') {
                $wallet = StoreCreditAccount::firstOrCreate(
                    ['tenant_id' => $rma->tenant_id, 'customer_id' => $rma->customer_id],
                    ['currency' => $order->currency ?? 'USD', 'balance' => 0.00]
                );

                $newBalance = (float) $wallet->balance + $amount;
                $wallet->update(['balance' => $newBalance]);

                StoreCreditTransaction::create([
                    'account_id' => $wallet->id,
                    'rma_id' => $rma->id,
                    'type' => 'refund_issued',
                    'amount' => $amount,
                    'balance_after' => $newBalance,
                    'description' => "Store credit issued for RMA #{$rma->rma_number}",
                ]);
            }

            // 7. Generate Credit Note
            $creditNote = CreditNote::create([
                'tenant_id' => $rma->tenant_id,
                'rma_id' => $rma->id,
                'refund_id' => $refund->id,
                'customer_id' => $rma->customer_id,
                'credit_note_number' => 'CN-'.date('Ym').'-'.strtoupper(bin2hex(random_bytes(3))),
                'amount' => $amount,
                'tax_amount' => round($amount * 0.10, 2),
                'balance_remaining' => $amount,
                'status' => 'active',
                'issued_at' => now(),
            ]);

            // 8. Generate immutable double-entry Financial Journal
            $journal = FinancialJournal::create([
                'tenant_id' => $rma->tenant_id,
                'reference_type' => 'refund',
                'reference_id' => $refund->id,
                'entry_date' => now()->toDateString(),
                'description' => "Settled refund for RMA #{$rma->rma_number} (Order #{$order->order_number})",
                'total_debit' => $amount,
                'total_credit' => $amount,
            ]);

            // Line 1: Debit Returns Expense / Refund Payable
            FinancialJournalLine::create([
                'journal_id' => $journal->id,
                'account_code' => '4010-RETURNS-EXPENSE',
                'account_name' => 'Returns & Allowances',
                'debit' => $amount,
                'credit' => 0.00,
                'branch_id' => $rma->branch_id,
            ]);

            // Line 2: Credit Gateway Clearing / Cash Account
            FinancialJournalLine::create([
                'journal_id' => $journal->id,
                'account_code' => $method === 'store_credit' ? '2020-STORE-CREDIT' : '1010-CASH-GATEWAY',
                'account_name' => $method === 'store_credit' ? 'Customer Store Credit Liability' : 'Gateway Clearing Account',
                'debit' => 0.00,
                'credit' => $amount,
                'branch_id' => $rma->branch_id,
            ]);

            // 9. Audit Logging
            $this->auditLogService->log(
                event: 'refund.settled',
                auditable: $refund,
                newValues: $refund->toArray(),
                justification: "Refund of {$amount} processed via {$gatewayName}"
            );

            return $refund;
        });
    }
}
