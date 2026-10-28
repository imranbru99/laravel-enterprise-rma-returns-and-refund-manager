<?php

namespace App\Filament\Finance\Resources\FinancialJournals\Pages;

use App\Filament\Finance\Resources\FinancialJournals\FinancialJournalResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditFinancialJournal extends EditRecord
{
    protected static string $resource = FinancialJournalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
