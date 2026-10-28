<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreCreditTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'rma_id',
        'type',
        'amount',
        'balance_after',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'balance_after' => 'decimal:2',
        ];
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(StoreCreditAccount::class, 'account_id');
    }

    public function rma(): BelongsTo
    {
        return $this->belongsTo(Rma::class);
    }
}
