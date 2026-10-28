<?php

namespace App\Filament\Resources\Warehouses;

use App\Filament\Resources\Warehouses\Pages\CreateWarehouse;
use App\Filament\Resources\Warehouses\Pages\EditWarehouse;
use App\Filament\Resources\Warehouses\Pages\ListWarehouses;
use App\Filament\Resources\Warehouses\Pages\ViewWarehouse;
use App\Models\Warehouse;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WarehouseResource extends Resource
{
    protected static ?string $model = Warehouse::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Logistics & Warehouse';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Warehouse Facility')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('code')
                            ->required()
                            ->maxLength(50),

                        Select::make('branch_id')
                            ->relationship('branch', 'name')
                            ->searchable()
                            ->preload(),

                        Select::make('type')
                            ->options([
                                'returns_hub' => 'Central Returns Hub',
                                'primary' => 'Primary Distribution Center',
                                'refurb_center' => 'Refurbishment Center',
                                'scrap' => 'Salvage / Scrap Facility',
                            ])
                            ->default('returns_hub')
                            ->required(),

                        TextInput::make('address')
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Toggle::make('is_active')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('branch.name')
                    ->sortable(),

                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => ucwords(str_replace('_', ' ', $state))),

                IconColumn::make('is_active')
                    ->boolean(),
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
            'index' => ListWarehouses::route('/'),
            'create' => CreateWarehouse::route('/create'),
            'view' => ViewWarehouse::route('/{record}'),
            'edit' => EditWarehouse::route('/{record}/edit'),
        ];
    }
}
