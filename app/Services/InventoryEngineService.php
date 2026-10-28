<?php

namespace App\Services;

use App\Models\Disposition;
use App\Models\Inspection;
use App\Models\InventoryMovement;
use App\Models\InventoryStock;
use App\Models\Rma;
use App\Models\RmaItem;
use Illuminate\Support\Facades\DB;

class InventoryEngineService
{
    public function __construct(
        protected TenantContext $tenantContext,
        protected AuditLogService $auditLogService
    ) {}

    /**
     * Mark returned package as received in warehouse.
     */
    public function receiveItem(Rma $rma, int $warehouseId, ?int $receivedBy = null): bool
    {
        return DB::transaction(function () use ($rma, $warehouseId) {
            $rma->update([
                'status' => 'item_received',
                'received_at' => now(),
            ]);

            $this->auditLogService->log(
                event: 'rma.item_received',
                auditable: $rma,
                newValues: ['status' => 'item_received', 'warehouse_id' => $warehouseId],
                justification: 'Returned shipment received at warehouse'
            );

            return true;
        });
    }

    /**
     * Perform quality inspection and grading.
     */
    public function inspectItem(
        Rma $rma,
        int $warehouseId,
        string $overallGrade = 'good',
        array $checklist = [],
        ?string $notes = null,
        array $photos = [],
        ?int $inspectorId = null
    ): Inspection {
        return DB::transaction(function () use ($rma, $warehouseId, $overallGrade, $checklist, $notes, $photos, $inspectorId) {
            $passed = ! in_array($overallGrade, ['damaged', 'defective', 'counterfeit', 'scrap']);

            $suggestedDisposition = match ($overallGrade) {
                'unopened', 'like_new', 'good' => 'restock',
                'damaged', 'used' => 'refurbish',
                'defective' => 'repair',
                'counterfeit', 'scrap' => 'scrap',
                default => 'restock',
            };

            $inspection = Inspection::create([
                'rma_id' => $rma->id,
                'warehouse_id' => $warehouseId,
                'inspector_id' => $inspectorId ?? auth()->id(),
                'overall_grade' => $overallGrade,
                'checklist_results' => $checklist,
                'notes' => $notes,
                'photos' => $photos,
                'estimated_restocking_value' => $passed ? $rma->estimated_refund_amount : 0.00,
                'suggested_disposition' => $suggestedDisposition,
                'passed_inspection' => $passed,
                'inspected_at' => now(),
            ]);

            $rma->update([
                'status' => 'inspection_completed',
            ]);

            $this->auditLogService->log(
                event: 'rma.inspected',
                auditable: $inspection,
                newValues: $inspection->toArray(),
                justification: "Inspection completed with grade {$overallGrade}"
            );

            return $inspection;
        });
    }

    /**
     * Complete final inventory disposition (restock, scrap, refurbish, RTV).
     */
    public function completeDisposition(
        Rma $rma,
        RmaItem $item,
        string $destinationType = 'restock',
        ?int $warehouseId = null,
        ?int $completedBy = null,
        ?string $notes = null
    ): Disposition {
        return DB::transaction(function () use ($rma, $item, $destinationType, $warehouseId, $completedBy, $notes) {
            $warehouseId = $warehouseId ?? $rma->branch?->warehouses()->first()?->id;

            $restockedAmount = 0.00;
            $scrapLoss = 0.00;

            if ($destinationType === 'restock' && $warehouseId) {
                $stock = InventoryStock::firstOrCreate(
                    [
                        'tenant_id' => $rma->tenant_id,
                        'warehouse_id' => $warehouseId,
                        'product_id' => $item->product_id,
                        'product_variant_id' => $item->product_variant_id,
                    ],
                    [
                        'quantity_on_hand' => 0,
                        'quantity_reserved' => 0,
                    ]
                );

                $stock->increment('quantity_on_hand', $item->quantity);
                $restockedAmount = (float) $item->unit_price * $item->quantity;

                InventoryMovement::create([
                    'tenant_id' => $rma->tenant_id,
                    'warehouse_id' => $warehouseId,
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'reference_type' => 'rma',
                    'reference_id' => $rma->id,
                    'movement_type' => 'restock',
                    'quantity' => $item->quantity,
                    'unit_cost' => $item->unit_price,
                    'notes' => "Restocked from RMA #{$rma->rma_number}",
                    'created_by' => $completedBy ?? auth()->id(),
                ]);
            } elseif ($destinationType === 'scrap') {
                $scrapLoss = (float) $item->unit_price * $item->quantity;

                if ($warehouseId) {
                    InventoryMovement::create([
                        'tenant_id' => $rma->tenant_id,
                        'warehouse_id' => $warehouseId,
                        'product_id' => $item->product_id,
                        'product_variant_id' => $item->product_variant_id,
                        'reference_type' => 'rma',
                        'reference_id' => $rma->id,
                        'movement_type' => 'scrap',
                        'quantity' => -$item->quantity,
                        'unit_cost' => $item->unit_price,
                        'notes' => "Scrapped from RMA #{$rma->rma_number}: {$notes}",
                        'created_by' => $completedBy ?? auth()->id(),
                    ]);
                }
            }

            $disposition = Disposition::create([
                'rma_id' => $rma->id,
                'rma_item_id' => $item->id,
                'destination_type' => $destinationType,
                'destination_warehouse_id' => $warehouseId,
                'quantity' => $item->quantity,
                'scrap_loss_amount' => $scrapLoss,
                'restocked_amount' => $restockedAmount,
                'completed_by' => $completedBy ?? auth()->id(),
                'completed_at' => now(),
                'notes' => $notes,
            ]);

            $this->auditLogService->log(
                event: 'inventory.disposition_completed',
                auditable: $disposition,
                newValues: $disposition->toArray(),
                justification: "Item disposition finalized to {$destinationType}"
            );

            return $disposition;
        });
    }
}
