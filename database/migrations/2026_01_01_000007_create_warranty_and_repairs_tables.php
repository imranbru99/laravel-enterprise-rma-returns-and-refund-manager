<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warranty_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('claim_number')->index();
            $table->string('serial_number')->nullable()->index();
            $table->date('purchase_date');
            $table->date('warranty_expires_at');
            $table->text('issue_description');
            $table->string('status')->default('submitted')->index();
            // submitted, under_review, approved, repair_in_progress, replacement_approved, rejected, closed
            $table->string('resolution')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['tenant_id', 'claim_number']);
        });

        Schema::create('spare_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('part_number')->index();
            $table->decimal('cost_price', 12, 2)->default(0.00);
            $table->decimal('sale_price', 12, 2)->default(0.00);
            $table->integer('stock_quantity')->default(0);
            $table->timestamps();

            $table->unique(['tenant_id', 'part_number']);
        });

        Schema::create('repair_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('rma_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('warranty_claim_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('technician_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('job_number')->index();
            $table->text('diagnosis')->nullable();
            $table->string('status')->default('queued')->index();
            // queued, diagnosed, awaiting_parts, in_progress, tested, completed, unrepairable
            $table->decimal('labor_cost', 12, 2)->default(0.00);
            $table->decimal('parts_cost', 12, 2)->default(0.00);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'job_number']);
        });

        Schema::create('exchange_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rma_id')->constrained()->cascadeOnDelete();
            $table->foreignId('original_order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('new_order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->foreignId('replacement_product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('replacement_variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->foreignId('source_branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->decimal('price_difference', 12, 2)->default(0.00);
            $table->string('payment_status')->default('settled'); // settled, payment_required, refund_due
            $table->string('shipping_status')->default('pending'); // pending, dispatched, delivered
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exchange_orders');
        Schema::dropIfExists('repair_jobs');
        Schema::dropIfExists('spare_parts');
        Schema::dropIfExists('warranty_claims');
    }
};
