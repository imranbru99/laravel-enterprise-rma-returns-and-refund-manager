<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saas_plans', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price_monthly', 10, 2)->default(0.00);
            $table->decimal('price_annual', 10, 2)->default(0.00);
            $table->unsignedInteger('max_users')->default(5);
            $table->unsignedInteger('max_branches')->default(1);
            $table->unsignedInteger('max_monthly_rmas')->default(100);
            $table->boolean('has_ai_access')->default(false);
            $table->boolean('has_custom_domain')->default(false);
            $table->boolean('has_white_label')->default(false);
            $table->json('features')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('tenant_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained('saas_plans')->cascadeOnDelete();
            $table->string('billing_cycle')->default('monthly'); // monthly, annual
            $table->string('status')->default('active')->index(); // active, trialing, past_due, canceled
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamps();
        });

        Schema::create('tenant_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('module_key')->index();
            $table->boolean('is_enabled')->default(true);
            $table->json('settings')->nullable();
            $table->timestamp('enabled_at')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'module_key']);
        });

        Schema::create('usage_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('metric')->index(); // rma_count, ai_tokens, storage_mb, api_calls
            $table->unsignedBigInteger('quantity')->default(1);
            $table->date('recorded_date')->index();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usage_records');
        Schema::dropIfExists('tenant_modules');
        Schema::dropIfExists('tenant_subscriptions');
        Schema::dropIfExists('saas_plans');
    }
};
