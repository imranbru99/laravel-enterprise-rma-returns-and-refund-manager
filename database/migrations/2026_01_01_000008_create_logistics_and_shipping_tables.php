<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_carriers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('code')->index(); // fedex, dhl, ups, usps, mock_courier
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->string('api_adapter')->default('mock'); // mock, fedex_api, dhl_api, etc.
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'code']);
        });

        Schema::create('return_shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rma_id')->constrained()->cascadeOnDelete();
            $table->string('carrier_code')->index();
            $table->string('tracking_number')->index();
            $table->string('label_url')->nullable();
            $table->string('label_barcode')->nullable();
            $table->string('status')->default('label_created')->index();
            // label_created, picked_up, in_transit, out_for_delivery, delivered, exception, delayed
            $table->json('tracking_events')->nullable();
            $table->timestamp('dispatched_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('return_shipments');
        Schema::dropIfExists('shipping_carriers');
    }
};
