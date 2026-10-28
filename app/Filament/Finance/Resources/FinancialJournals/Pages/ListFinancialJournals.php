<?php

namespace App\Filament\Finance\Resources\FinancialJournals\Pages;

use App\Filament\Finance\Resources\FinancialJournals\FinancialJournalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFinancialJournals extends ListRecords
{
    protected static string $resource = FinancialJournalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
