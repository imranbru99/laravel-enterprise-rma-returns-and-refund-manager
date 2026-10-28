<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExchangeOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'rma_id',
        'original_order_id',
        'new_order_id',
        'replacement_product_id',
        'replacement_variant_id',
        'source_branch_id',
        'price_difference',
        'payment_status',
        'shipping_status',
    ];

    protected function casts(): array
    {
        return [
            'price_difference' => 'decimal:2',
        ];
    }

    public function rma(): BelongsTo
    {
        return $this->belongsTo(Rma::class);
    }

    public function originalOrder(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'original_order_id');
    }

    public function newOrder(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'new_order_id');
    }

    public function replacementProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'replacement_product_id');
    }

    public function replacementVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'replacement_variant_id');
    }

    public function sourceBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'source_branch_id');
    }
}
