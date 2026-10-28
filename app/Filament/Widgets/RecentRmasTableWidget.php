<?php

namespace App\Filament\Widgets;

use App\Models\Rma;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentRmasTableWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Rma::query()->latest()->limit(5)
            )
            ->heading('Recent Return Merchandise Authorizations')
            ->columns([
                TextColumn::make('rma_number')
                    ->label('RMA #')
                    ->weight('bold'),

                TextColumn::make('order.order_number')
                    ->label('Order #'),

                TextColumn::make('customer.name')
                    ->label('Customer'),

                TextColumn::make('status')
                    ->badge(),

                TextColumn::make('estimated_refund_amount')
                    ->money('USD'),

                TextColumn::make('priority')
                    ->badge(),

                TextColumn::make('created_at')
                    ->dateTime('M d, Y H:i'),
            ]);
    }
}
