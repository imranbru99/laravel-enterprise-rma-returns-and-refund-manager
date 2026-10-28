<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'code',
        'legal_name',
        'tax_number',
        'currency',
        'timezone',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'settings' => 'array',
        ];
    }

    public function regions(): HasMany
    {
        return $this->hasMany(Region::class);
    }

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }
}
