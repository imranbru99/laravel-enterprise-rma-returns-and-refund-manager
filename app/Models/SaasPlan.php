<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaasPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'description',
        'price_monthly',
        'price_annual',
        'max_users',
        'max_branches',
        'max_monthly_rmas',
        'has_ai_access',
        'has_custom_domain',
        'has_white_label',
        'features',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price_monthly' => 'decimal:2',
            'price_annual' => 'decimal:2',
            'has_ai_access' => 'boolean',
            'has_custom_domain' => 'boolean',
            'has_white_label' => 'boolean',
            'is_active' => 'boolean',
            'features' => 'array',
        ];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(TenantSubscription::class, 'plan_id');
    }
}
