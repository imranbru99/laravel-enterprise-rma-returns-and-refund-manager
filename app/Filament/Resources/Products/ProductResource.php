<?php

namespace App\Filament\Resources\Products;

use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Filament\Resources\Products\Pages\ViewProduct;
use App\Models\Product;
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

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Commerce';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Product Information')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('sku')
                            ->label('SKU')
                            ->required()
                            ->maxLength(100),

                        TextInput::make('category')
                            ->required()
                            ->maxLength(100),

                        TextInput::make('price')
                            ->numeric()
                            ->prefix('$')
                            ->required(),

                        TextInput::make('cost_price')
                            ->numeric()
                            ->prefix('$')
                            ->default(0.00),

                        TextInput::make('return_window_days')
                            ->numeric()
                            ->default(30)
                            ->label('Return Window (Days)'),

                        TextInput::make('warranty_period_months')
                            ->numeric()
                            ->default(12)
                            ->label('Warranty (Months)'),

                        Toggle::make('is_returnable')
                            ->label('Eligible for Return')
                            ->default(true),

                        Toggle::make('serial_tracking_required')
                            ->label('Requires Serial Number Verification')
                            ->default(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sku')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category')
                    ->badge()
                    ->sortable(),

                TextColumn::make('price')
                    ->money('USD')
                    ->sortable(),

                IconColumn::make('is_returnable')
                    ->label('Returnable')
                    ->boolean(),

                TextColumn::make('return_window_days')
                    ->label('Return Window')
                    ->suffix(' days'),

                TextColumn::make('warranty_period_months')
                    ->label('Warranty')
                    ->suffix(' mos'),
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
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'view' => ViewProduct::route('/{record}'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }
}
