<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('subdomain')->nullable()->unique();
            $table->string('domain')->nullable()->unique();
            $table->string('status')->default('active')->index(); // active, trial, suspended, archived
            $table->unsignedBigInteger('plan_id')->nullable()->index();
            $table->string('billing_cycle')->default('monthly'); // monthly, annual
            $table->timestamp('trial_ends_at')->nullable();
            $table->json('branding')->nullable(); // logo_url, primary_color, secondary_color, portal_title
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->index();
            $table->string('legal_name')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('currency', 3)->default('USD');
            $table->string('timezone')->default('UTC');
            $table->json('settings')->nullable();
            $table->timestamps();
        });

        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->index();
            $table->timestamps();
        });

        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('region_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('code')->index();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country', 2)->default('US');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('allow_cross_branch_returns')->default(true);
            $table->unsignedInteger('return_window_days')->default(30);
            $table->decimal('auto_approve_threshold', 12, 2)->default(50.00);
            $table->json('settings')->nullable();
            $table->timestamps();
        });

        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('code')->index();
            $table->string('type')->default('returns_hub'); // primary, returns_hub, refurb_center, scrap
            $table->string('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouses');
        Schema::dropIfExists('branches');
        Schema::dropIfExists('regions');
        Schema::dropIfExists('companies');
        Schema::dropIfExists('tenants');
    }
};
