<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('return_policies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->unsignedInteger('version')->default(1);
            $table->unsignedInteger('return_window_days')->default(30);
            $table->boolean('requires_receipt')->default(true);
            $table->boolean('requires_original_packaging')->default(true);
            $table->boolean('allow_opened')->default(true);
            $table->decimal('restocking_fee_percent', 5, 2)->default(0.00);
            $table->boolean('free_return_shipping')->default(false);
            $table->decimal('auto_approve_max_amount', 12, 2)->default(50.00);
            $table->json('rules')->nullable(); // category rules, condition rules
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('rmas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('destination_branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('return_policy_id')->nullable()->constrained()->nullOnDelete();
            $table->string('rma_number')->index();
            $table->string('status')->default('pending_review')->index();
            // draft, pending_review, approved, rejected, label_generated, item_shipped, item_received,
            // inspecting, inspection_completed, resolution_pending, refund_processing, refunded,
            // exchanged, replacement_sent, repaired, closed, cancelled
            $table->string('resolution_type')->default('refund'); // refund, exchange, replacement, repair, store_credit
            $table->string('return_reason')->index();
            $table->text('customer_notes')->nullable();
            $table->text('internal_notes')->nullable();
            $table->string('return_method')->default('mail_in'); // mail_in, store_dropoff, courier_pickup
            $table->string('carrier_code')->nullable();
            $table->string('tracking_number')->nullable()->index();
            $table->decimal('return_shipping_fee', 12, 2)->default(0.00);
            $table->decimal('estimated_refund_amount', 12, 2)->default(0.00);
            $table->decimal('approved_refund_amount', 12, 2)->default(0.00);
            $table->string('currency', 3)->default('USD');
            $table->unsignedInteger('policy_version')->default(1);
            $table->unsignedInteger('risk_score')->default(0); // 0-100
            $table->json('risk_reasons')->nullable();
            $table->string('priority')->default('normal'); // low, normal, high, urgent
            $table->timestamp('sla_due_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->foreignId('assigned_to_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['tenant_id', 'rma_number']);
        });

        Schema::create('rma_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rma_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 12, 2)->default(0.00);
            $table->decimal('refund_amount', 12, 2)->default(0.00);
            $table->string('return_reason');
            $table->string('condition_stated')->nullable(); // unopened, like_new, opened, damaged, defective
            $table->string('serial_number')->nullable();
            $table->json('customer_photos')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected, received, inspected, settled
            $table->timestamps();
        });

        Schema::create('rma_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rma_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('from_status')->nullable();
            $table->string('to_status');
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('rma_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rma_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_internal')->default(false);
            $table->text('message');
            $table->json('attachments')->nullable();
            $table->timestamps();
        });

        Schema::create('rma_disputes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rma_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->text('reason');
            $table->string('status')->default('open'); // open, under_review, resolved, rejected
            $table->text('resolution')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rma_disputes');
        Schema::dropIfExists('rma_comments');
        Schema::dropIfExists('rma_status_histories');
        Schema::dropIfExists('rma_items');
        Schema::dropIfExists('rmas');
        Schema::dropIfExists('return_policies');
    }
};
