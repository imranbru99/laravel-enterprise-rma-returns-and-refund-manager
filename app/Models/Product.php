<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'sku',
        'barcode',
        'description',
        'category',
        'price',
        'cost_price',
        'is_returnable',
        'return_window_days',
        'warranty_period_months',
        'serial_tracking_required',
        'image_url',
        'attributes',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'is_returnable' => 'boolean',
            'serial_tracking_required' => 'boolean',
            'attributes' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name).'-'.Str::lower(Str::random(5));
            }
        });
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(InventoryStock::class);
    }
}
