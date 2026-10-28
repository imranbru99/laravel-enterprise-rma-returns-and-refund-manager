<?php

namespace App\Filament\Finance\Resources\CreditNotes\Pages;

use App\Filament\Finance\Resources\CreditNotes\CreditNoteResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCreditNote extends ViewRecord
{
    protected static string $resource = CreditNoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
