<?php

namespace App\Filament\Resources\Rmas\Pages;

use App\Filament\Resources\Rmas\RmaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditRma extends EditRecord
{
    protected static string $resource = RmaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
