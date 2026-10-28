<?php

namespace App\Services;

use App\Models\Product;
use App\Models\RepairJob;
use App\Models\WarrantyClaim;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WarrantyEngineService
{
    public function __construct(
        protected TenantContext $tenantContext,
        protected AuditLogService $auditLogService
    ) {}

    /**
     * Submit and validate a warranty claim.
     */
    public function submitClaim(array $data): WarrantyClaim
    {
        return DB::transaction(function () use ($data) {
            $product = Product::findOrFail($data['product_id']);
            $purchaseDate = Carbon::parse($data['purchase_date']);
            $warrantyMonths = (int) $product->warranty_period_months;
            $expiresAt = (clone $purchaseDate)->addMonths($warrantyMonths);

            $isExpired = now()->gt($expiresAt);
            if ($isExpired && empty($data['allow_out_of_warranty'])) {
                throw ValidationException::withMessages([
                    'purchase_date' => ["Warranty for this product expired on {$expiresAt->toDateString()}."],
                ]);
            }

            $claimNumber = 'WAR-'.date('Ym').'-'.strtoupper(bin2hex(random_bytes(3)));

            $claim = WarrantyClaim::create([
                'tenant_id' => $data['tenant_id'] ?? $this->tenantContext->getTenantId() ?? $product->tenant_id,
                'customer_id' => $data['customer_id'],
                'product_id' => $product->id,
                'claim_number' => $claimNumber,
                'serial_number' => $data['serial_number'] ?? null,
                'purchase_date' => $purchaseDate->toDateString(),
                'warranty_expires_at' => $expiresAt->toDateString(),
                'issue_description' => $data['issue_description'],
                'status' => 'submitted',
            ]);

            $this->auditLogService->log(
                event: 'warranty.claim_submitted',
                auditable: $claim,
                newValues: $claim->toArray(),
                justification: "Warranty claim #{$claimNumber} created"
            );

            return $claim;
        });
    }

    /**
     * Create a repair job for a warranty claim or RMA.
     */
    public function createRepairJob(WarrantyClaim $claim, ?int $technicianId = null, ?int $branchId = null): RepairJob
    {
        return DB::transaction(function () use ($claim, $technicianId, $branchId) {
            $jobNumber = 'REP-'.date('Ym').'-'.strtoupper(bin2hex(random_bytes(3)));

            $job = RepairJob::create([
                'tenant_id' => $claim->tenant_id,
                'branch_id' => $branchId,
                'warranty_claim_id' => $claim->id,
                'technician_id' => $technicianId,
                'job_number' => $jobNumber,
                'diagnosis' => 'Awaiting technician diagnostic inspection',
                'status' => 'queued',
                'labor_cost' => 0.00,
                'parts_cost' => 0.00,
            ]);

            $claim->update(['status' => 'repair_in_progress']);

            return $job;
        });
    }
}
