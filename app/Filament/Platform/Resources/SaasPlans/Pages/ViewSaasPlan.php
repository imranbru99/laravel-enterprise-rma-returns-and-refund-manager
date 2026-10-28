<?php

namespace App\Filament\Platform\Resources\SaasPlans\Pages;

use App\Filament\Platform\Resources\SaasPlans\SaasPlanResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSaasPlan extends ViewRecord
{
    protected static string $resource = SaasPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
