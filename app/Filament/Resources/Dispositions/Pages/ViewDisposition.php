<?php

namespace App\Filament\Resources\Dispositions\Pages;

use App\Filament\Resources\Dispositions\DispositionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDisposition extends ViewRecord
{
    protected static string $resource = DispositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
