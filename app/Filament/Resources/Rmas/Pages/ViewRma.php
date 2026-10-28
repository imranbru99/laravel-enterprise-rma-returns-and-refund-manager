<?php

namespace App\Filament\Resources\Rmas\Pages;

use App\Filament\Resources\Rmas\RmaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRma extends ViewRecord
{
    protected static string $resource = RmaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
