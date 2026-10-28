<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rma_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('refund_number')->index();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('method')->default('original_payment_method');
            // original_payment_method, store_credit, gift_card, bank_transfer, cash
            $table->string('gateway_name')->default('mock_gateway'); // stripe, paypal, adyen, mock_gateway, manual_cash
            $table->string('gateway_transaction_id')->nullable()->index();
            $table->string('status')->default('pending_approval')->index();
            // pending_approval, approved, processing, settled, failed, cancelled
            $table->text('failure_reason')->nullable();
            $table->string('idempotency_key')->unique();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'refund_number']);
        });

        Schema::create('credit_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rma_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('refund_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('credit_note_number')->index();
            $table->decimal('amount', 12, 2);
            $table->decimal('tax_amount', 12, 2)->default(0.00);
            $table->decimal('balance_remaining', 12, 2);
            $table->string('status')->default('active'); // active, partially_redeemed, redeemed, expired, void
            $table->timestamp('issued_at')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'credit_note_number']);
        });

        Schema::create('financial_journals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('reference_type')->index(); // refund, rma, restock, scrap, exchange
            $table->unsignedBigInteger('reference_id')->nullable()->index();
            $table->date('entry_date')->index();
            $table->text('description');
            $table->decimal('total_debit', 12, 2)->default(0.00);
            $table->decimal('total_credit', 12, 2)->default(0.00);
            $table->timestamps();
        });

        Schema::create('financial_journal_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_id')->constrained('financial_journals')->cascadeOnDelete();
            $table->string('account_code')->index(); // 1010-Cash, 2010-RefundPayable, 4010-ReturnsExpense, 1020-Inventory
            $table->string('account_name');
            $table->decimal('debit', 12, 2)->default(0.00);
            $table->decimal('credit', 12, 2)->default(0.00);
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('store_credit_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->decimal('balance', 12, 2)->default(0.00);
            $table->string('currency', 3)->default('USD');
            $table->timestamps();

            $table->unique(['tenant_id', 'customer_id']);
        });

        Schema::create('store_credit_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('store_credit_accounts')->cascadeOnDelete();
            $table->foreignId('rma_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type')->index(); // credit, debit, refund_issued, redemption
            $table->decimal('amount', 12, 2);
            $table->decimal('balance_after', 12, 2);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_credit_transactions');
        Schema::dropIfExists('store_credit_accounts');
        Schema::dropIfExists('financial_journal_lines');
        Schema::dropIfExists('financial_journals');
        Schema::dropIfExists('credit_notes');
        Schema::dropIfExists('refunds');
    }
};
