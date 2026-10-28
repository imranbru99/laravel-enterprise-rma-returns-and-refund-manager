<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RmaStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'rma_id',
        'user_id',
        'from_status',
        'to_status',
        'notes',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    public function rma(): BelongsTo
    {
        return $this->belongsTo(Rma::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
