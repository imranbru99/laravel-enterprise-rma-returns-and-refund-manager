<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\TenantModule;
use App\Services\ModuleManager;
use App\Services\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModuleManagementTest extends TestCase
{
    use RefreshDatabase;

    protected Tenant $tenant;

    protected ModuleManager $moduleManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'name' => 'Apex Modular Corp',
            'slug' => 'apex-modular-corp',
            'status' => 'active',
            'email' => 'modules@apex.test',
        ]);
        app(TenantContext::class)->setTenant($this->tenant);
        $this->moduleManager = app(ModuleManager::class);
    }

    public function test_module_catalog_contains_exactly_90_enterprise_modules(): void
    {
        $allModules = $this->moduleManager->getCatalog();
        $this->assertCount(90, $allModules);
        $this->assertArrayHasKey('rma_core', $allModules);
        $this->assertArrayHasKey('warranty', $allModules);
        $this->assertArrayHasKey('ai_intelligence', $allModules);
        $this->assertArrayHasKey('financial_ledger', $allModules);
    }

    public function test_tenant_can_toggle_module_without_losing_historical_data(): void
    {
        // 1. Enable module
        $this->moduleManager->enable('spare_parts', $this->tenant->id);
        $this->assertTrue($this->moduleManager->isEnabled('spare_parts', $this->tenant->id));

        // Verify record in database
        $record = TenantModule::where('tenant_id', $this->tenant->id)
            ->where('module_key', 'spare_parts')
            ->first();
        $this->assertNotNull($record);
        $this->assertTrue($record->is_enabled);

        // 2. Disable module safely
        $this->moduleManager->disable('spare_parts', $this->tenant->id);
        $this->assertFalse($this->moduleManager->isEnabled('spare_parts', $this->tenant->id));

        // Verify historical record is preserved in database with is_enabled = false
        $recordAfter = TenantModule::where('tenant_id', $this->tenant->id)
            ->where('module_key', 'spare_parts')
            ->first();
        $this->assertNotNull($recordAfter);
        $this->assertFalse($recordAfter->is_enabled);
    }

    public function test_core_modules_cannot_be_disabled(): void
    {
        $result = $this->moduleManager->disable('rma_core', $this->tenant->id);
        $this->assertFalse($result);
        $this->assertTrue($this->moduleManager->isEnabled('rma_core', $this->tenant->id));
    }
}
