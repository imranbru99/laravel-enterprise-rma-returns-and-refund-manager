<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'customer_code',
        'name',
        'email',
        'phone',
        'addresses',
        'tags',
        'is_vip',
        'risk_score',
    ];

    protected function casts(): array
    {
        return [
            'addresses' => 'array',
            'tags' => 'array',
            'is_vip' => 'boolean',
            'risk_score' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function rmas(): HasMany
    {
        return $this->hasMany(Rma::class);
    }

    public function storeCreditAccount(): HasOne
    {
        return $this->hasOne(StoreCreditAccount::class);
    }
}
