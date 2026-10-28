<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Company;
use App\Models\Customer;
use App\Models\FinancialJournal;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Rma;
use App\Models\RmaItem;
use App\Models\StoreCreditAccount;
use App\Models\Tenant;
use App\Services\RefundEngineService;
use App\Services\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class RefundSafetyEngineTest extends TestCase
{
    use RefreshDatabase;

    protected Tenant $tenant;

    protected Company $company;

    protected Branch $branch;

    protected Customer $customer;

    protected Product $product;

    protected Order $order;

    protected OrderItem $orderItem;

    protected Rma $rma;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'name' => 'TechNova Electronics',
            'slug' => 'technova-electronics',
            'status' => 'active',
            'email' => 'finance@technova.test',
        ]);
        app(TenantContext::class)->setTenant($this->tenant);

        $this->company = Company::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'TechNova Ltd',
            'code' => 'TNV-01',
        ]);

        $this->branch = Branch::create([
            'tenant_id' => $this->tenant->id,
            'company_id' => $this->company->id,
            'name' => 'Sylhet Flagship Store',
            'code' => 'SYL-01',
        ]);

        $this->customer = Customer::create([
            'tenant_id' => $this->tenant->id,
            'customer_code' => 'CUST-TNV-01',
            'name' => 'Rahim Chowdhury',
            'email' => 'rahim@technova.test',
        ]);

        $this->product = Product::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Curved Gaming Monitor 34"',
            'sku' => 'MON-34-CRV',
            'category' => 'Computers & Displays',
            'price' => 450.00,
            'is_returnable' => true,
        ]);

        $this->order = Order::create([
            'tenant_id' => $this->tenant->id,
            'company_id' => $this->company->id,
            'branch_id' => $this->branch->id,
            'customer_id' => $this->customer->id,
            'order_number' => 'ORD-TNV-1001',
            'order_date' => now()->subDays(3),
            'status' => 'delivered',
            'total_amount' => 450.00,
            'paid_amount' => 450.00,
            'refunded_amount' => 0.00,
            'payment_status' => 'paid',
        ]);

        $this->orderItem = OrderItem::create([
            'order_id' => $this->order->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
            'unit_price' => 450.00,
            'total_price' => 450.00,
        ]);

        $this->rma = Rma::create([
            'tenant_id' => $this->tenant->id,
            'company_id' => $this->company->id,
            'branch_id' => $this->branch->id,
            'customer_id' => $this->customer->id,
            'order_id' => $this->order->id,
            'rma_number' => 'RMA-TNV-2026-001',
            'status' => 'inspection_completed',
            'type' => 'refund',
            'return_reason' => 'dead_pixels',
            'estimated_refund_amount' => 450.00,
            'approved_refund_amount' => 450.00,
        ]);

        RmaItem::create([
            'rma_id' => $this->rma->id,
            'order_item_id' => $this->orderItem->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
            'unit_price' => 450.00,
            'total_price' => 450.00,
            'return_reason' => 'dead_pixels',
            'status' => 'approved',
        ]);
    }

    public function test_refund_cannot_exceed_order_remaining_refundable_balance(): void
    {
        $refundService = app(RefundEngineService::class);

        $this->expectException(ValidationException::class);
        $refundService->processRefund(
            rma: $this->rma,
            amount: 500.00, // Order paid amount is 450.00, so 500.00 must be rejected
            method: 'original_payment_method'
        );
    }

    public function test_idempotency_key_blocks_duplicate_refund_attempts(): void
    {
        $refundService = app(RefundEngineService::class);
        $idempotencyKey = 'idem_unique_test_key_999';

        $firstRefund = $refundService->processRefund(
            rma: $this->rma,
            amount: 100.00,
            method: 'original_payment_method',
            idempotencyKey: $idempotencyKey
        );

        $this->assertNotNull($firstRefund->id);
        $this->assertEquals('settled', $firstRefund->status);

        // Attempt second refund with same idempotency key
        $this->expectException(ValidationException::class);
        $refundService->processRefund(
            rma: $this->rma,
            amount: 100.00,
            method: 'original_payment_method',
            idempotencyKey: $idempotencyKey
        );
    }

    public function test_successful_refund_generates_credit_note_and_balanced_ledger_entries(): void
    {
        $refundService = app(RefundEngineService::class);

        $refund = $refundService->processRefund(
            rma: $this->rma,
            amount: 250.00,
            method: 'original_payment_method'
        );

        $this->assertEquals(250.00, (float) $refund->amount);
        $this->assertEquals('settled', $refund->status);

        // Verify Order updated refunded amount
        $this->assertEquals(250.00, (float) $this->order->fresh()->refunded_amount);
        $this->assertEquals(200.00, (float) $this->order->fresh()->remaining_refundable_amount);

        // Verify Credit Note generated
        $creditNote = $refund->creditNote;
        $this->assertNotNull($creditNote);
        $this->assertEquals(250.00, (float) $creditNote->amount);

        // Verify Financial Journal Entry is Balanced (Total Debit = Total Credit)
        $journal = FinancialJournal::where('reference_type', 'refund')
            ->where('reference_id', $refund->id)
            ->first();
        $this->assertNotNull($journal);
        $lines = $journal->lines;
        $totalDebit = (float) $lines->sum('debit');
        $totalCredit = (float) $lines->sum('credit');
        $this->assertEquals($totalDebit, $totalCredit);
        $this->assertEquals(250.00, $totalDebit);
    }

    public function test_store_credit_refund_issues_wallet_credit(): void
    {
        $refundService = app(RefundEngineService::class);

        $refund = $refundService->processRefund(
            rma: $this->rma,
            amount: 150.00,
            method: 'store_credit'
        );

        $this->assertEquals('settled', $refund->status);
        $this->assertEquals('store_credit', $refund->method);

        // Verify store credit account balance
        $wallet = StoreCreditAccount::where('customer_id', $this->customer->id)->first();
        $this->assertNotNull($wallet);
        $this->assertEquals(150.00, (float) $wallet->balance);
        $this->assertCount(1, $wallet->transactions);
    }
}
