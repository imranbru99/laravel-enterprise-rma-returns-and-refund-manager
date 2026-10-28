<?php

namespace App\Filament\Finance\Resources\Refunds;

use App\Filament\Finance\Resources\Refunds\Pages\ListRefunds;
use App\Filament\Finance\Resources\Refunds\Pages\ViewRefund;
use App\Models\Refund;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RefundResource extends Resource
{
    protected static ?string $model = Refund::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static string|\UnitEnum|null $navigationGroup = 'Financial Operations';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Refund Transaction Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('refund_number')
                            ->required()
                            ->disabled(),

                        Select::make('rma_id')
                            ->relationship('rma', 'rma_number')
                            ->required()
                            ->disabled(),

                        TextInput::make('amount')
                            ->numeric()
                            ->prefix('$')
                            ->required(),

                        Select::make('method')
                            ->options([
                                'original_payment_method' => 'Original Payment Method',
                                'store_credit' => 'Store Credit Wallet',
                                'bank_transfer' => 'Bank Wire Transfer',
                                'cash' => 'Cash',
                            ])
                            ->required(),

                        Select::make('gateway_name')
                            ->options([
                                'mock_gateway' => 'Sandbox Secure Gateway',
                                'stripe' => 'Stripe Payments',
                                'paypal' => 'PayPal Commerce',
                                'manual_cash' => 'Branch Cash Drawer',
                            ])
                            ->required(),

                        TextInput::make('gateway_transaction_id')
                            ->label('Gateway Transaction ID'),

                        Select::make('status')
                            ->options([
                                'pending_approval' => 'Pending Approval',
                                'approved' => 'Approved',
                                'processing' => 'Processing',
                                'settled' => 'Settled',
                                'failed' => 'Failed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required(),

                        DateTimePicker::make('processed_at'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('refund_number')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('rma.rma_number')
                    ->label('RMA #')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('order.order_number')
                    ->label('Order #')
                    ->searchable(),

                TextColumn::make('amount')
                    ->money(fn ($record) => $record->currency ?? 'USD')
                    ->sortable(),

                TextColumn::make('method')
                    ->badge()
                    ->formatStateUsing(fn ($state) => ucwords(str_replace('_', ' ', $state))),

                TextColumn::make('gateway_name')
                    ->badge()
                    ->colors(['info']),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending_approval',
                        'info' => 'processing',
                        'success' => 'settled',
                        'danger' => 'failed',
                    ]),

                TextColumn::make('processed_at')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'settled' => 'Settled',
                        'pending_approval' => 'Pending Approval',
                        'failed' => 'Failed',
                    ]),
                SelectFilter::make('method')
                    ->options([
                        'original_payment_method' => 'Original Payment Method',
                        'store_credit' => 'Store Credit',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),

                Action::make('retry_settlement')
                    ->label('Retry Payout')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn (Refund $record) => in_array($record->status, ['failed', 'pending_approval']))
                    ->action(function (Refund $record) {
                        $record->update(['status' => 'settled', 'processed_at' => now()]);
                        Notification::make()->title('Refund Retried & Settled Successfully')->success()->send();
                    }),
            ])
            ->toolbarActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRefunds::route('/'),
            'view' => ViewRefund::route('/{record}'),
        ];
    }
}
