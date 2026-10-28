<?php

namespace App\Filament\Finance\Resources\CreditNotes\Pages;

use App\Filament\Finance\Resources\CreditNotes\CreditNoteResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCreditNote extends CreateRecord
{
    protected static string $resource = CreditNoteResource::class;
}
