<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class AuditLogService
{
    public function __construct(protected TenantContext $tenantContext) {}

    /**
     * Log an auditable event with details and justification.
     */
    public function log(
        string $event,
        ?Model $auditable = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $justification = null,
        ?int $userId = null,
        ?int $tenantId = null
    ): AuditLog {
        $tenantId = $tenantId ?? $this->tenantContext->getTenantId();
        if (! $tenantId && $auditable && isset($auditable->tenant_id)) {
            $tenantId = $auditable->tenant_id;
        }

        $userId = $userId ?? auth()->id();

        return AuditLog::create([
            'tenant_id' => $tenantId,
            'user_id' => $userId,
            'event' => $event,
            'auditable_type' => $auditable ? get_class($auditable) : null,
            'auditable_id' => $auditable?->getKey(),
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip() ?? '127.0.0.1',
            'user_agent' => Request::userAgent() ?? 'System / Console',
            'justification' => $justification,
        ]);
    }
}
