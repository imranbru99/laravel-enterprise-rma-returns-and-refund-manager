<?php

namespace App\Filament\Resources\ReturnPolicies\Pages;

use App\Filament\Resources\ReturnPolicies\ReturnPolicyResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewReturnPolicy extends ViewRecord
{
    protected static string $resource = ReturnPolicyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
