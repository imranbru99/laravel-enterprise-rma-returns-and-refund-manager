<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ReturnPolicy;
use App\Models\Tenant;
use App\Services\PdfDocumentService;
use App\Services\RmaEngineService;
use App\Services\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RmaWorkflowTest extends TestCase
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
            'name' => 'Apex Retailers',
            'slug' => 'apex-retailers',
            'status' => 'active',
            'email' => 'contact@apex.test',
        ]);
        app(TenantContext::class)->setTenant($this->tenant);

        $this->company = Company::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Apex Corp',
            'code' => 'APX-01',
        ]);

        $this->branch = Branch::create([
            'tenant_id' => $this->tenant->id,
            'company_id' => $this->company->id,
            'name' => 'Dhaka Central Hub',
            'code' => 'DAC-01',
        ]);

        $this->customer = Customer::create([
            'tenant_id' => $this->tenant->id,
            'customer_code' => 'CUST-APX-01',
            'name' => 'Tanvir Hossain',
            'email' => 'tanvir@example.com',
            'phone' => '+8801711223344',
        ]);

        $this->product = Product::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Wireless ANC Headphones',
            'sku' => 'WANC-01',
            'barcode' => '8940001234567',
            'category' => 'Electronics',
            'price' => 150.00,
            'is_returnable' => true,
            'return_window_days' => 30,
        ]);

        $this->order = Order::create([
            'tenant_id' => $this->tenant->id,
            'company_id' => $this->company->id,
            'branch_id' => $this->branch->id,
            'customer_id' => $this->customer->id,
            'order_number' => 'ORD-APX-9901',
            'order_date' => now()->subDays(5),
            'status' => 'delivered',
            'subtotal' => 150.00,
            'tax_amount' => 0.00,
            'total_amount' => 150.00,
            'paid_amount' => 150.00,
            'refunded_amount' => 0.00,
            'payment_status' => 'paid',
            'delivered_at' => now()->subDays(4),
        ]);

        $this->orderItem = OrderItem::create([
            'order_id' => $this->order->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
            'unit_price' => 150.00,
            'total_price' => 150.00,
        ]);

        ReturnPolicy::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Standard 30-Day Return Policy',
            'return_window_days' => 30,
            'requires_receipt' => true,
            'auto_approve' => true,
            'is_active' => true,
        ]);
    }

    public function test_rma_eligibility_evaluation_identifies_valid_order_items(): void
    {
        $rmaService = app(RmaEngineService::class);

        $evaluation = $rmaService->evaluateEligibility($this->order, [
            ['order_item_id' => $this->orderItem->id, 'quantity' => 1],
        ]);

        $this->assertTrue($evaluation['is_eligible']);
        $this->assertCount(1, $evaluation['eligible_items']);
        $this->assertEquals(150.00, $evaluation['eligible_items'][0]['unit_price']);
    }

    public function test_rma_creation_and_lifecycle_state_machine_transitions(): void
    {
        $rmaService = app(RmaEngineService::class);

        $rma = $rmaService->createRma([
            'tenant_id' => $this->tenant->id,
            'company_id' => $this->company->id,
            'branch_id' => $this->branch->id,
            'customer_id' => $this->customer->id,
            'order_id' => $this->order->id,
            'type' => 'refund',
            'return_reason' => 'defective',
            'customer_comments' => 'Left earbud crackles and does not charge.',
            'items' => [
                [
                    'order_item_id' => $this->orderItem->id,
                    'product_id' => $this->product->id,
                    'quantity' => 1,
                    'unit_price' => 150.00,
                    'reason' => 'defective',
                    'action' => 'refund',
                ],
            ],
        ]);

        $this->assertNotNull($rma->id);
        $this->assertStringStartsWith('RMA-', $rma->rma_number);
        $this->assertEquals(150.00, (float) $rma->estimated_refund_amount);

        // Transition through lifecycle
        $rmaService->transitionStatus($rma, 'approved', 'Auto-approved per policy');
        $this->assertEquals('approved', $rma->fresh()->status);

        $rmaService->transitionStatus($rma, 'item_received', 'Received at Dhaka Central Hub');
        $this->assertEquals('item_received', $rma->fresh()->status);

        $rmaService->transitionStatus($rma, 'inspecting', 'Assigned to technician for grading');
        $this->assertEquals('inspecting', $rma->fresh()->status);

        $rmaService->transitionStatus($rma, 'inspection_completed', 'Graded Grade-A clean');
        $this->assertEquals('inspection_completed', $rma->fresh()->status);

        $rmaService->transitionStatus($rma, 'refunded', 'Refund authorized and completed');
        $this->assertEquals('refunded', $rma->fresh()->status);

        $rmaService->transitionStatus($rma, 'closed', 'RMA cycle fully closed');
        $this->assertEquals('closed', $rma->fresh()->status);

        // Check status audit history
        $this->assertGreaterThanOrEqual(6, $rma->statusHistories()->count());
    }

    public function test_pdf_document_service_generates_valid_documents(): void
    {
        $rmaService = app(RmaEngineService::class);
        $rma = $rmaService->createRma([
            'tenant_id' => $this->tenant->id,
            'company_id' => $this->company->id,
            'branch_id' => $this->branch->id,
            'customer_id' => $this->customer->id,
            'order_id' => $this->order->id,
            'type' => 'refund',
            'return_reason' => 'defective',
            'items' => [
                [
                    'order_item_id' => $this->orderItem->id,
                    'product_id' => $this->product->id,
                    'quantity' => 1,
                    'unit_price' => 150.00,
                ],
            ],
        ]);

        $pdfService = app(PdfDocumentService::class);

        $labelResponse = $pdfService->generateShippingLabel($rma);
        $this->assertTrue($labelResponse->isSuccessful());

        $voucherResponse = $pdfService->generateRmaVoucher($rma);
        $this->assertTrue($voucherResponse->isSuccessful());
    }
}
