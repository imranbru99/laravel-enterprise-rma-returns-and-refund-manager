<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReturnPolicy extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'version',
        'return_window_days',
        'requires_receipt',
        'requires_original_packaging',
        'allow_opened',
        'restocking_fee_percent',
        'free_return_shipping',
        'auto_approve_max_amount',
        'rules',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'requires_receipt' => 'boolean',
            'requires_original_packaging' => 'boolean',
            'allow_opened' => 'boolean',
            'free_return_shipping' => 'boolean',
            'is_active' => 'boolean',
            'restocking_fee_percent' => 'decimal:2',
            'auto_approve_max_amount' => 'decimal:2',
            'rules' => 'array',
        ];
    }

    public function rmas(): HasMany
    {
        return $this->hasMany(Rma::class);
    }
}
