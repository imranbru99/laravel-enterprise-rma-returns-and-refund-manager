<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditNote extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'rma_id',
        'refund_id',
        'customer_id',
        'credit_note_number',
        'amount',
        'tax_amount',
        'balance_remaining',
        'status',
        'issued_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'balance_remaining' => 'decimal:2',
            'issued_at' => 'datetime',
        ];
    }

    public function rma(): BelongsTo
    {
        return $this->belongsTo(Rma::class);
    }

    public function refund(): BelongsTo
    {
        return $this->belongsTo(Refund::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
