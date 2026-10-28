<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialJournalLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'journal_id',
        'account_code',
        'account_name',
        'debit',
        'credit',
        'branch_id',
    ];

    protected function casts(): array
    {
        return [
            'debit' => 'decimal:2',
            'credit' => 'decimal:2',
        ];
    }

    public function journal(): BelongsTo
    {
        return $this->belongsTo(FinancialJournal::class, 'journal_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
