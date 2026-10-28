<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Rma;
use App\Models\SaasPlan;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicAndPortalWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed a plan for the pricing & registration pages
        SaasPlan::create([
            'key' => 'growth',
            'name' => 'Growth Enterprise',
            'price_monthly' => 199.00,
            'price_annual' => 1990.00,
            'max_branches' => 10,
            'max_monthly_rmas' => 2000,
            'is_active' => true,
        ]);
    }

    public function test_public_marketing_pages_return_successful_responses(): void
    {
        $routes = [
            '/',
            '/features',
            '/workflow',
            '/industries',
            '/pricing',
            '/docs',
            '/contact',
            '/register',
            '/demo',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200, "Failed asserting that route {$route} returns 200 OK.");
        }
    }

    public function test_customer_portal_guest_lookup_resolves_valid_order(): void
    {
        $tenant = Tenant::create([
            'name' => 'Portal Test Merchant',
            'slug' => 'portal-test-merchant',
            'status' => 'active',
            'email' => 'portal@merchant.test',
        ]);

        $company = Company::create([
            'tenant_id' => $tenant->id,
            'name' => 'Portal Co',
            'code' => 'PT-01',
        ]);

        $branch = Branch::create([
            'tenant_id' => $tenant->id,
            'company_id' => $company->id,
            'name' => 'Online Hub',
            'code' => 'ONL-01',
        ]);

        $customer = Customer::create([
            'tenant_id' => $tenant->id,
            'customer_code' => 'CUST-LOOKUP-01',
            'name' => 'Kazi Nazrul',
            'email' => 'nazrul@example.com',
        ]);

        $order = Order::create([
            'tenant_id' => $tenant->id,
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'customer_id' => $customer->id,
            'order_number' => 'ORD-LOOKUP-2026',
            'order_date' => now()->subDays(2),
            'status' => 'delivered',
            'total_amount' => 85.00,
            'paid_amount' => 85.00,
            'payment_status' => 'paid',
        ]);

        $rma = Rma::create([
            'tenant_id' => $tenant->id,
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'customer_id' => $customer->id,
            'order_id' => $order->id,
            'rma_number' => 'RMA-LOOKUP-2026-01',
            'status' => 'pending_review',
            'type' => 'refund',
            'return_reason' => 'defective',
            'estimated_refund_amount' => 85.00,
        ]);

        $response = $this->get('/portal/guest-lookup?rma_number=RMA-LOOKUP-2026-01&email=nazrul@example.com');

        $response->assertStatus(200);
        $response->assertSee('RMA-LOOKUP-2026-01');
    }

    public function test_demo_sandbox_step_runner_transitions_state_machine(): void
    {
        $tenant = Tenant::create([
            'name' => 'Sandbox Tenant',
            'slug' => 'sandbox-tenant',
            'status' => 'active',
            'email' => 'sandbox@demo.test',
        ]);

        $company = Company::create(['tenant_id' => $tenant->id, 'name' => 'Sandbox Co', 'code' => 'SB-01']);
        $branch = Branch::create(['tenant_id' => $tenant->id, 'company_id' => $company->id, 'name' => 'Main', 'code' => 'M-01']);
        $customer = Customer::create(['tenant_id' => $tenant->id, 'customer_code' => 'SB-CUST', 'name' => 'Demo User', 'email' => 'demo@test.com']);
        $order = Order::create([
            'tenant_id' => $tenant->id,
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'customer_id' => $customer->id,
            'order_number' => 'ORD-DEMO-RUN',
            'order_date' => now(),
            'status' => 'delivered',
            'total_amount' => 100,
            'paid_amount' => 100,
        ]);

        $rma = Rma::create([
            'tenant_id' => $tenant->id,
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'customer_id' => $customer->id,
            'order_id' => $order->id,
            'rma_number' => 'RMA-DEMO-RUNNER',
            'status' => 'pending_review',
            'type' => 'refund',
            'return_reason' => 'defective',
            'estimated_refund_amount' => 100.00,
        ]);

        $response = $this->postJson('/demo/step-advance', [
            'step' => 'approve',
        ]);

        $response->assertStatus(200);
        $this->assertEquals('approved', $rma->fresh()->status);
    }
}
