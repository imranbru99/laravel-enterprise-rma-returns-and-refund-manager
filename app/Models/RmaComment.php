<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RmaComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'rma_id',
        'user_id',
        'is_internal',
        'message',
        'attachments',
    ];

    protected function casts(): array
    {
        return [
            'is_internal' => 'boolean',
            'attachments' => 'array',
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
