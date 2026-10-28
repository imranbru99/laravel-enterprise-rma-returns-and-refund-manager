<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('trigger_event')->index();
            // rma_created, rma_approved, item_received, inspection_passed, inspection_failed, refund_settled, sla_breached
            $table->json('conditions')->nullable(); // rules array
            $table->json('actions')->nullable(); // action list
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('execution_count')->default(0);
            $table->timestamps();
        });

        Schema::create('workflow_executions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id')->constrained()->cascadeOnDelete();
            $table->string('entity_type')->index();
            $table->unsignedBigInteger('entity_id')->index();
            $table->string('status')->default('success'); // success, failed, skipped
            $table->json('logs')->nullable();
            $table->timestamp('executed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('ai_request_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('feature')->index(); // classification, fraud_risk, response_gen, defect_summary, product_matching
            $table->unsignedInteger('prompt_tokens')->default(0);
            $table->unsignedInteger('completion_tokens')->default(0);
            $table->decimal('cost_estimate', 8, 4)->default(0.0000);
            $table->json('input_data')->nullable();
            $table->json('output_data')->nullable();
            $table->boolean('is_mock')->default(true);
            $table->timestamps();
        });

        Schema::create('knowledge_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->index();
            $table->string('category')->default('general')->index();
            $table->longText('content');
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('knowledge_documents');
        Schema::dropIfExists('ai_request_audits');
        Schema::dropIfExists('workflow_executions');
        Schema::dropIfExists('workflows');
    }
};
