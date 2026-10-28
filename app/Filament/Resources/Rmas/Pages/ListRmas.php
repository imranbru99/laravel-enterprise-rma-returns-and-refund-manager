<?php

namespace App\Filament\Resources\Rmas\Pages;

use App\Filament\Resources\Rmas\RmaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRmas extends ListRecords
{
    protected static string $resource = RmaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
