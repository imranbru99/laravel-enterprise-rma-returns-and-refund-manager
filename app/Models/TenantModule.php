<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantModule extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'module_key',
        'is_enabled',
        'settings',
        'enabled_at',
    ];

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
            'settings' => 'array',
            'enabled_at' => 'datetime',
        ];
    }
}
