<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Disposition extends Model
{
    use HasFactory;

    protected $fillable = [
        'rma_id',
        'rma_item_id',
        'destination_type',
        'destination_warehouse_id',
        'quantity',
        'scrap_loss_amount',
        'restocked_amount',
        'completed_by',
        'completed_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'scrap_loss_amount' => 'decimal:2',
            'restocked_amount' => 'decimal:2',
            'completed_at' => 'datetime',
        ];
    }

    public function rma(): BelongsTo
    {
        return $this->belongsTo(Rma::class);
    }

    public function rmaItem(): BelongsTo
    {
        return $this->belongsTo(RmaItem::class);
    }

    public function destinationWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'destination_warehouse_id');
    }

    public function completedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }
}
