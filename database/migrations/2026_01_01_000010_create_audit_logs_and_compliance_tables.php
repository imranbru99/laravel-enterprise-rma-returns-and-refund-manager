<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('event')->index(); // rma.created, rma.approved, refund.processed, module.toggled, etc.
            $table->string('auditable_type')->nullable()->index();
            $table->unsignedBigInteger('auditable_id')->nullable()->index();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('justification')->nullable();
            $table->timestamps();
        });

        Schema::create('integration_endpoints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('type')->index(); // shopify, woocommerce, stripe, paypal, quickbooks, webhook_outgoing
            $table->string('name');
            $table->string('status')->default('active'); // active, inactive, testing, error
            $table->string('api_key')->nullable();
            $table->string('webhook_secret')->nullable();
            $table->json('settings')->nullable();
            $table->timestamp('last_sync_at')->nullable();
            $table->timestamps();
        });

        Schema::create('customer_privacy_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('type')->default('export'); // export, erase, rectify
            $table->string('status')->default('pending'); // pending, completed, rejected
            $table->text('details')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_privacy_requests');
        Schema::dropIfExists('integration_endpoints');
        Schema::dropIfExists('audit_logs');
    }
};
