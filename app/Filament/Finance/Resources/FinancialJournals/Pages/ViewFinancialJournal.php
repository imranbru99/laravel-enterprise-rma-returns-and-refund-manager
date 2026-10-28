<?php

namespace App\Filament\Finance\Resources\FinancialJournals\Pages;

use App\Filament\Finance\Resources\FinancialJournals\FinancialJournalResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewFinancialJournal extends ViewRecord
{
    protected static string $resource = FinancialJournalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
