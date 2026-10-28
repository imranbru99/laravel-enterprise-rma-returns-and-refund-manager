<?php

namespace App\Filament\Resources\Customers;

use App\Filament\Resources\Customers\Pages\CreateCustomer;
use App\Filament\Resources\Customers\Pages\EditCustomer;
use App\Filament\Resources\Customers\Pages\ListCustomers;
use App\Filament\Resources\Customers\Pages\ViewCustomer;
use App\Models\Customer;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Commerce';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Customer Profile')
                    ->columns(2)
                    ->schema([
                        TextInput::make('customer_code')
                            ->required()
                            ->default(fn () => 'CUST-'.strtoupper(bin2hex(random_bytes(3)))),

                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),

                        TextInput::make('phone')
                            ->tel()
                            ->maxLength(50),

                        TextInput::make('risk_score')
                            ->numeric()
                            ->default(0)
                            ->helperText('0-100 fraud risk indicator'),

                        Toggle::make('is_vip')
                            ->label('VIP Customer Status')
                            ->default(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer_code')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('phone')
                    ->searchable(),

                IconColumn::make('is_vip')
                    ->label('VIP')
                    ->boolean(),

                TextColumn::make('risk_score')
                    ->label('Risk Score')
                    ->badge()
                    ->colors([
                        'success' => fn ($state) => $state < 30,
                        'warning' => fn ($state) => $state >= 30 && $state < 70,
                        'danger' => fn ($state) => $state >= 70,
                    ]),

                TextColumn::make('orders_count')
                    ->counts('orders')
                    ->label('Orders'),

                TextColumn::make('rmas_count')
                    ->counts('rmas')
                    ->label('Returns'),
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
            'index' => ListCustomers::route('/'),
            'create' => CreateCustomer::route('/create'),
            'view' => ViewCustomer::route('/{record}'),
            'edit' => EditCustomer::route('/{record}/edit'),
        ];
    }
}
