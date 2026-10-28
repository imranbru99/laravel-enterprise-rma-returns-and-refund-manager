<?php

namespace App\Traits;

use App\Models\Tenant;
use App\Scopes\TenantScope;
use App\Services\TenantContext;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant(): void
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function ($model) {
            if (empty($model->tenant_id)) {
                $tenantContext = app(TenantContext::class);
                $model->tenant_id = $tenantContext->getTenantId();
            }
        });
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
