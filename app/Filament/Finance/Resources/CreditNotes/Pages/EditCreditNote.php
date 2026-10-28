<?php

namespace App\Filament\Finance\Resources\CreditNotes\Pages;

use App\Filament\Finance\Resources\CreditNotes\CreditNoteResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCreditNote extends EditRecord
{
    protected static string $resource = CreditNoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
