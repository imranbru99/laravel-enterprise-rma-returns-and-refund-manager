<?php

namespace App\Filament\Finance\Resources\Refunds\Pages;

use App\Filament\Finance\Resources\Refunds\RefundResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditRefund extends EditRecord
{
    protected static string $resource = RefundResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
