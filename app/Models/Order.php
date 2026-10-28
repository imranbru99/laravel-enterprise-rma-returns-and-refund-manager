<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'customer_id',
        'order_number',
        'channel',
        'status',
        'total_amount',
        'tax_amount',
        'shipping_fee',
        'discount_amount',
        'paid_amount',
        'refunded_amount',
        'currency',
        'payment_method',
        'payment_reference',
        'ordered_at',
        'delivered_at',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'shipping_fee' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'refunded_amount' => 'decimal:2',
            'ordered_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function rmas(): HasMany
    {
        return $this->hasMany(Rma::class);
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class);
    }

    public function getRemainingRefundableAmountAttribute(): float
    {
        return max(0.00, (float) $this->paid_amount - (float) $this->refunded_amount);
    }
}
