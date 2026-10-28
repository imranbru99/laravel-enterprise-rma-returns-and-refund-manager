<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RmaItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'rma_id',
        'order_item_id',
        'product_id',
        'product_variant_id',
        'quantity',
        'unit_price',
        'refund_amount',
        'return_reason',
        'condition_stated',
        'serial_number',
        'customer_photos',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'refund_amount' => 'decimal:2',
            'customer_photos' => 'array',
        ];
    }

    public function rma(): BelongsTo
    {
        return $this->belongsTo(Rma::class);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function dispositions(): HasMany
    {
        return $this->hasMany(Disposition::class);
    }
}
