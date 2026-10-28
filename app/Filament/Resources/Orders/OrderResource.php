<?php

namespace App\Filament\Resources\Orders;

use App\Filament\Resources\Orders\Pages\CreateOrder;
use App\Filament\Resources\Orders\Pages\EditOrder;
use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Filament\Resources\Orders\Pages\ViewOrder;
use App\Models\Order;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Commerce';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Order Information')
                    ->columns(2)
                    ->schema([
                        TextInput::make('order_number')
                            ->required()
                            ->maxLength(255),

                        Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('branch_id')
                            ->relationship('branch', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('channel')
                            ->options([
                                'online' => 'Online Store',
                                'in_store' => 'Retail Store POS',
                                'marketplace' => 'Marketplace',
                            ])
                            ->default('online')
                            ->required(),

                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('completed')
                            ->required(),

                        TextInput::make('total_amount')
                            ->numeric()
                            ->prefix('$')
                            ->required(),

                        TextInput::make('paid_amount')
                            ->numeric()
                            ->prefix('$')
                            ->required(),

                        TextInput::make('refunded_amount')
                            ->numeric()
                            ->prefix('$')
                            ->default(0.00),

                        DateTimePicker::make('ordered_at')
                            ->default(now()),

                        DateTimePicker::make('delivered_at'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('customer.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('channel')
                    ->badge(),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'processing',
                        'info' => 'shipped',
                        'success' => ['delivered', 'completed'],
                        'danger' => 'cancelled',
                    ]),

                TextColumn::make('total_amount')
                    ->money(fn ($record) => $record->currency ?? 'USD')
                    ->sortable(),

                TextColumn::make('refunded_amount')
                    ->money(fn ($record) => $record->currency ?? 'USD')
                    ->sortable(),

                TextColumn::make('branch.name')
                    ->sortable(),

                TextColumn::make('ordered_at')
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'completed' => 'Completed',
                        'delivered' => 'Delivered',
                        'shipped' => 'Shipped',
                    ]),

                SelectFilter::make('branch_id')
                    ->label('Branch')
                    ->relationship('branch', 'name'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
            'create' => CreateOrder::route('/create'),
            'view' => ViewOrder::route('/{record}'),
            'edit' => EditOrder::route('/{record}/edit'),
        ];
    }
}
