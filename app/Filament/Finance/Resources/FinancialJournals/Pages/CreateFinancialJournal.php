<?php

namespace App\Filament\Finance\Resources\FinancialJournals\Pages;

use App\Filament\Finance\Resources\FinancialJournals\FinancialJournalResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFinancialJournal extends CreateRecord
{
    protected static string $resource = FinancialJournalResource::class;
}
