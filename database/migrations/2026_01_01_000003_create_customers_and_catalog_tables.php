<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_code')->index();
            $table->string('name');
            $table->string('email')->index();
            $table->string('phone')->nullable()->index();
            $table->json('addresses')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('is_vip')->default(false);
            $table->unsignedInteger('risk_score')->default(0); // 0-100
            $table->timestamps();

            $table->unique(['tenant_id', 'email']);
            $table->unique(['tenant_id', 'customer_code']);
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->index();
            $table->string('sku')->index();
            $table->string('barcode')->nullable()->index();
            $table->text('description')->nullable();
            $table->string('category')->index();
            $table->decimal('price', 12, 2)->default(0.00);
            $table->decimal('cost_price', 12, 2)->default(0.00);
            $table->boolean('is_returnable')->default(true);
            $table->unsignedInteger('return_window_days')->default(30);
            $table->unsignedInteger('warranty_period_months')->default(12);
            $table->boolean('serial_tracking_required')->default(false);
            $table->string('image_url')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'sku']);
        });

        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('sku')->index();
            $table->string('name');
            $table->decimal('price', 12, 2)->default(0.00);
            $table->integer('stock_quantity')->default(0);
            $table->json('attributes')->nullable(); // size, color, etc.
            $table->timestamps();

            $table->unique(['tenant_id', 'sku']);
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('order_number')->index();
            $table->string('channel')->default('online'); // online, in_store, pos, marketplace
            $table->string('status')->default('completed'); // pending, processing, shipped, delivered, completed, cancelled
            $table->decimal('total_amount', 12, 2)->default(0.00);
            $table->decimal('tax_amount', 12, 2)->default(0.00);
            $table->decimal('shipping_fee', 12, 2)->default(0.00);
            $table->decimal('discount_amount', 12, 2)->default(0.00);
            $table->decimal('paid_amount', 12, 2)->default(0.00);
            $table->decimal('refunded_amount', 12, 2)->default(0.00);
            $table->string('currency', 3)->default('USD');
            $table->string('payment_method')->default('credit_card');
            $table->string('payment_reference')->nullable();
            $table->timestamp('ordered_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'order_number']);
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 12, 2)->default(0.00);
            $table->decimal('tax_amount', 12, 2)->default(0.00);
            $table->decimal('total_amount', 12, 2)->default(0.00);
            $table->decimal('refunded_amount', 12, 2)->default(0.00);
            $table->string('return_status')->default('eligible'); // eligible, returned, partially_returned, non_returnable
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('products');
        Schema::dropIfExists('customers');
    }
};
