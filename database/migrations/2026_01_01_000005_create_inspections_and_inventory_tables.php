<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rma_id')->constrained()->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();
            $table->foreignId('inspector_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('overall_grade')->default('good');
            // unopened, like_new, good, used, damaged, defective, missing_parts, counterfeit, scrap
            $table->json('checklist_results')->nullable();
            $table->text('notes')->nullable();
            $table->json('photos')->nullable();
            $table->decimal('estimated_restocking_value', 12, 2)->default(0.00);
            $table->string('suggested_disposition')->default('restock'); // restock, refurbish, repair, return_to_vendor, scrap, recycle
            $table->boolean('passed_inspection')->default(true);
            $table->timestamp('inspected_at')->nullable();
            $table->timestamps();
        });

        Schema::create('dispositions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rma_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rma_item_id')->constrained()->cascadeOnDelete();
            $table->string('destination_type')->default('restock');
            // restock, refurbish, repair, return_to_vendor, recycle, scrap, donate
            $table->foreignId('destination_warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('scrap_loss_amount', 12, 2)->default(0.00);
            $table->decimal('restocked_amount', 12, 2)->default(0.00);
            $table->foreignId('completed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('inventory_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('quantity_on_hand')->default(0);
            $table->integer('quantity_reserved')->default(0);
            $table->timestamps();

            $table->unique(['warehouse_id', 'product_id', 'product_variant_id'], 'warehouse_product_variant_unique');
        });

        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reference_type')->index(); // rma, return_receipt, exchange, stock_transfer, manual_adjustment
            $table->unsignedBigInteger('reference_id')->nullable()->index();
            $table->string('movement_type')->index(); // in, out, transfer, scrap, restock
            $table->integer('quantity');
            $table->decimal('unit_cost', 12, 2)->default(0.00);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
        Schema::dropIfExists('inventory_stocks');
        Schema::dropIfExists('dispositions');
        Schema::dropIfExists('inspections');
    }
};
