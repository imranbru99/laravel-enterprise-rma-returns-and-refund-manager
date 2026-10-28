<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntegrationEndpoint extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'type',
        'name',
        'status',
        'api_key',
        'webhook_secret',
        'settings',
        'last_sync_at',
    ];

    protected function casts(): array
    {
        return [
            'settings' => 'array',
            'last_sync_at' => 'datetime',
        ];
    }
}
