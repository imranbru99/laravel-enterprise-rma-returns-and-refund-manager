<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsageRecord extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'metric',
        'quantity',
        'recorded_date',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'recorded_date' => 'date',
            'metadata' => 'array',
        ];
    }
}
