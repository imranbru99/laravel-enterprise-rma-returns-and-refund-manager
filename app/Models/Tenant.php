<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'subdomain',
        'domain',
        'status',
        'plan_id',
        'billing_cycle',
        'trial_ends_at',
        'branding',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'branding' => 'array',
            'settings' => 'array',
            'trial_ends_at' => 'datetime',
        ];
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SaasPlan::class, 'plan_id');
    }

    public function companies(): HasMany
    {
        return $this->hasMany(Company::class);
    }

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    public function warehouses(): HasMany
    {
        return $this->hasMany(Warehouse::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function modules(): HasMany
    {
        return $this->hasMany(TenantModule::class);
    }

    public function rmas(): HasMany
    {
        return $this->hasMany(Rma::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function returnPolicies(): HasMany
    {
        return $this->hasMany(ReturnPolicy::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(TenantSubscription::class);
    }
}
