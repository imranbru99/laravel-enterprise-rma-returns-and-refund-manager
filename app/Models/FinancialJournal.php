<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinancialJournal extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'reference_type',
        'reference_id',
        'entry_date',
        'description',
        'total_debit',
        'total_credit',
    ];

    protected function casts(): array
    {
        return [
            'entry_date' => 'date',
            'total_debit' => 'decimal:2',
            'total_credit' => 'decimal:2',
        ];
    }

    public function lines(): HasMany
    {
        return $this->hasMany(FinancialJournalLine::class, 'journal_id');
    }
}
