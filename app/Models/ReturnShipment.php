<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnShipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'rma_id',
        'carrier_code',
        'tracking_number',
        'label_url',
        'label_barcode',
        'status',
        'tracking_events',
        'dispatched_at',
        'delivered_at',
    ];

    protected function casts(): array
    {
        return [
            'tracking_events' => 'array',
            'dispatched_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    public function rma(): BelongsTo
    {
        return $this->belongsTo(Rma::class);
    }
}
