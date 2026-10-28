<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Rma;
use App\Models\Tenant;
use App\Services\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenancyIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_scope_isolates_rma_records_between_tenants(): void
    {
        // Tenant A
        $tenantA = Tenant::create([
            'name' => 'Tenant Alpha',
            'slug' => 'tenant-alpha',
            'status' => 'active',
            'email' => 'admin@alpha.test',
        ]);
        $companyA = Company::create(['tenant_id' => $tenantA->id, 'name' => 'Alpha Co', 'code' => 'ALPHA-01']);
        $branchA = Branch::create(['tenant_id' => $tenantA->id, 'company_id' => $companyA->id, 'name' => 'Alpha Branch', 'code' => 'AL-01']);
        $custA = Customer::create(['tenant_id' => $tenantA->id, 'customer_code' => 'CUST-001', 'name' => 'Cust A', 'email' => 'a@test.com']);
        $orderA = Order::create([
            'tenant_id' => $tenantA->id,
            'company_id' => $companyA->id,
            'branch_id' => $branchA->id,
            'customer_id' => $custA->id,
            'order_number' => 'ORD-AAA-01',
            'order_date' => now(),
            'total_amount' => 100,
            'status' => 'delivered',
        ]);
        $rmaA = Rma::create([
            'tenant_id' => $tenantA->id,
            'company_id' => $companyA->id,
            'branch_id' => $branchA->id,
            'customer_id' => $custA->id,
            'order_id' => $orderA->id,
            'rma_number' => 'RMA-AAA-001',
            'status' => 'requested',
            'type' => 'refund',
            'return_reason' => 'defective',
            'total_refund_amount' => 100,
        ]);

        // Tenant B
        $tenantB = Tenant::create([
            'name' => 'Tenant Beta',
            'slug' => 'tenant-beta',
            'status' => 'active',
            'email' => 'admin@beta.test',
        ]);
        $companyB = Company::create(['tenant_id' => $tenantB->id, 'name' => 'Beta Co', 'code' => 'BETA-01']);
        $branchB = Branch::create(['tenant_id' => $tenantB->id, 'company_id' => $companyB->id, 'name' => 'Beta Branch', 'code' => 'BE-01']);
        $custB = Customer::create(['tenant_id' => $tenantB->id, 'customer_code' => 'CUST-002', 'name' => 'Cust B', 'email' => 'b@test.com']);
        $orderB = Order::create([
            'tenant_id' => $tenantB->id,
            'company_id' => $companyB->id,
            'branch_id' => $branchB->id,
            'customer_id' => $custB->id,
            'order_number' => 'ORD-BBB-01',
            'order_date' => now(),
            'total_amount' => 200,
            'status' => 'delivered',
        ]);
        $rmaB = Rma::create([
            'tenant_id' => $tenantB->id,
            'company_id' => $companyB->id,
            'branch_id' => $branchB->id,
            'customer_id' => $custB->id,
            'order_id' => $orderB->id,
            'rma_number' => 'RMA-BBB-001',
            'status' => 'requested',
            'type' => 'exchange',
            'return_reason' => 'wrong_item',
            'total_refund_amount' => 200,
        ]);

        // Set context to Tenant A
        app(TenantContext::class)->setTenant($tenantA);

        $visibleRmas = Rma::all();
        $this->assertCount(1, $visibleRmas);
        $this->assertEquals('RMA-AAA-001', $visibleRmas->first()->rma_number);
        $this->assertNull(Rma::where('rma_number', 'RMA-BBB-001')->first());

        // Switch context to Tenant B
        app(TenantContext::class)->setTenant($tenantB);
        $visibleRmasB = Rma::all();
        $this->assertCount(1, $visibleRmasB);
        $this->assertEquals('RMA-BBB-001', $visibleRmasB->first()->rma_number);
        $this->assertNull(Rma::where('rma_number', 'RMA-AAA-001')->first());

        // Clear context
        app(TenantContext::class)->setTenant(null);
    }
}
