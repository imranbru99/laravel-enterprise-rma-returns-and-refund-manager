<?php

namespace App\Services;

use App\Models\ExchangeOrder;
use App\Models\InventoryStock;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Rma;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ExchangeEngineService
{
    public function __construct(
        protected TenantContext $tenantContext,
        protected AuditLogService $auditLogService
    ) {}

    /**
     * Process an exchange or variant swap.
     */
    public function processExchange(
        Rma $rma,
        int $replacementProductId,
        ?int $replacementVariantId = null,
        ?int $sourceBranchId = null
    ): ExchangeOrder {
        return DB::transaction(function () use ($rma, $replacementProductId, $replacementVariantId, $sourceBranchId) {
            $replacementProduct = Product::findOrFail($replacementProductId);
            $replacementPrice = (float) $replacementProduct->price;

            if ($replacementVariantId) {
                $variant = ProductVariant::findOrFail($replacementVariantId);
                $replacementPrice = (float) $variant->price;
            }

            $originalCredit = (float) $rma->approved_refund_amount > 0 ? (float) $rma->approved_refund_amount : (float) $rma->estimated_refund_amount;
            $priceDifference = $replacementPrice - $originalCredit;

            $sourceBranchId = $sourceBranchId ?? $rma->branch_id;

            // Reserve stock from source warehouse if exists
            $warehouse = $rma->branch?->warehouses()->first();
            if ($warehouse) {
                $stock = InventoryStock::where('warehouse_id', $warehouse->id)
                    ->where('product_id', $replacementProductId)
                    ->first();

                if ($stock && $stock->available_quantity < 1) {
                    throw ValidationException::withMessages([
                        'replacement_product_id' => ['Insufficient inventory for requested exchange replacement.'],
                    ]);
                }

                if ($stock) {
                    $stock->increment('quantity_reserved', 1);
                }
            }

            $exchange = ExchangeOrder::create([
                'rma_id' => $rma->id,
                'original_order_id' => $rma->order_id,
                'replacement_product_id' => $replacementProductId,
                'replacement_variant_id' => $replacementVariantId,
                'source_branch_id' => $sourceBranchId,
                'price_difference' => $priceDifference,
                'payment_status' => $priceDifference <= 0 ? 'settled' : 'payment_required',
                'shipping_status' => 'pending',
            ]);

            $rma->update([
                'resolution_type' => 'exchange',
                'status' => 'exchanged',
            ]);

            $this->auditLogService->log(
                event: 'exchange.processed',
                auditable: $exchange,
                newValues: $exchange->toArray(),
                justification: "Exchange created with price difference {$priceDifference}"
            );

            return $exchange;
        });
    }
}
