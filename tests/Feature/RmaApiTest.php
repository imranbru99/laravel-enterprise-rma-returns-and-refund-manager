<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Rma;
use App\Models\Tenant;
use App\Services\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RmaApiTest extends TestCase
{
    use RefreshDatabase;

    protected Tenant $tenant;

    protected Company $company;

    protected Branch $branch;

    protected Customer $customer;

    protected Product $product;

    protected Order $order;

    protected OrderItem $orderItem;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'name' => 'API Commerce Corp',
            'slug' => 'api-commerce-corp',
            'status' => 'active',
            'email' => 'api@commerce.test',
        ]);
        app(TenantContext::class)->setTenant($this->tenant);

        $this->company = Company::create(['tenant_id' => $this->tenant->id, 'name' => 'Commerce Ltd', 'code' => 'COM-01']);
        $this->branch = Branch::create(['tenant_id' => $this->tenant->id, 'company_id' => $this->company->id, 'name' => 'Main API Hub', 'code' => 'API-01']);
        $this->customer = Customer::create(['tenant_id' => $this->tenant->id, 'customer_code' => 'API-CUST-01', 'name' => 'Sara Khan', 'email' => 'sara@example.com']);
        $this->product = Product::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Mechanical Keyboard RGB',
            'sku' => 'KB-RGB-01',
            'category' => 'Computer Accessories',
            'price' => 120.00,
            'is_returnable' => true,
        ]);

        $this->order = Order::create([
            'tenant_id' => $this->tenant->id,
            'company_id' => $this->company->id,
            'branch_id' => $this->branch->id,
            'customer_id' => $this->customer->id,
            'order_number' => 'ORD-API-2026',
            'order_date' => now()->subDays(1),
            'status' => 'delivered',
            'total_amount' => 120.00,
            'paid_amount' => 120.00,
            'refunded_amount' => 0.00,
            'payment_status' => 'paid',
        ]);

        $this->orderItem = OrderItem::create([
            'order_id' => $this->order->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
            'unit_price' => 120.00,
            'total_price' => 120.00,
        ]);
    }

    public function test_api_can_list_rmas(): void
    {
        Rma::create([
            'tenant_id' => $this->tenant->id,
            'company_id' => $this->company->id,
            'branch_id' => $this->branch->id,
            'customer_id' => $this->customer->id,
            'order_id' => $this->order->id,
            'rma_number' => 'RMA-API-TEST-01',
            'status' => 'pending_review',
            'type' => 'refund',
            'return_reason' => 'defective',
            'estimated_refund_amount' => 120.00,
        ]);

        $response = $this->getJson('/api/v1/rmas');

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $this->assertCount(1, $response->json('data.data'));
        $this->assertEquals('RMA-API-TEST-01', $response->json('data.data.0.rma_number'));
    }

    public function test_api_can_create_rma(): void
    {
        $payload = [
            'order_id' => $this->order->id,
            'return_reason' => 'defective_switches',
            'resolution_type' => 'refund',
            'return_method' => 'mail_in',
            'customer_notes' => 'Spacebar switch double clicks.',
            'items' => [
                [
                    'order_item_id' => $this->orderItem->id,
                    'quantity' => 1,
                    'condition_stated' => 'opened_like_new',
                ],
            ],
        ];

        $response = $this->postJson('/api/v1/rmas', $payload);

        $response->assertStatus(201);
        $response->assertJsonPath('success', true);
        $this->assertStringStartsWith('RMA-', $response->json('data.rma_number'));
    }

    public function test_api_returns_all_90_modules_in_catalog(): void
    {
        $response = $this->getJson('/api/v1/modules');

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $this->assertCount(90, $response->json('data'));
    }
}
