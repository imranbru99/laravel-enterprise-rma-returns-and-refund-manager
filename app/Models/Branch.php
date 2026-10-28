<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'company_id',
        'region_id',
        'name',
        'code',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'phone',
        'email',
        'is_active',
        'allow_cross_branch_returns',
        'return_window_days',
        'auto_approve_threshold',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'allow_cross_branch_returns' => 'boolean',
            'auto_approve_threshold' => 'decimal:2',
            'settings' => 'array',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function warehouses(): HasMany
    {
        return $this->hasMany(Warehouse::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function rmas(): HasMany
    {
        return $this->hasMany(Rma::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
