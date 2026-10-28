<?php

namespace App\Filament\Resources\Inspections;

use App\Filament\Resources\Inspections\Pages\CreateInspection;
use App\Filament\Resources\Inspections\Pages\EditInspection;
use App\Filament\Resources\Inspections\Pages\ListInspections;
use App\Filament\Resources\Inspections\Pages\ViewInspection;
use App\Models\Inspection;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InspectionResource extends Resource
{
    protected static ?string $model = Inspection::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Logistics & Warehouse';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Quality Inspection Report')
                    ->columns(2)
                    ->schema([
                        Select::make('rma_id')
                            ->relationship('rma', 'rma_number')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('warehouse_id')
                            ->relationship('warehouse', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('overall_grade')
                            ->options([
                                'unopened' => 'Brand New (Unopened)',
                                'like_new' => 'Like New (Open Box)',
                                'good' => 'Good Condition',
                                'used' => 'Normal Wear & Tear',
                                'damaged' => 'Physical Damage',
                                'defective' => 'Functional Hardware Defect',
                                'counterfeit' => 'Suspected Counterfeit',
                                'scrap' => 'Unrecoverable / Scrap',
                            ])
                            ->default('good')
                            ->required(),

                        Select::make('suggested_disposition')
                            ->options([
                                'restock' => 'Restock to Inventory',
                                'refurbish' => 'Send for Refurbishing',
                                'repair' => 'Send for Diagnostic Repair',
                                'return_to_vendor' => 'Return to Vendor (RTV)',
                                'scrap' => 'Scrap / Recycle',
                            ])
                            ->default('restock')
                            ->required(),

                        TextInput::make('estimated_restocking_value')
                            ->numeric()
                            ->prefix('$')
                            ->default(0.00),

                        Toggle::make('passed_inspection')
                            ->label('Passed Return Inspection')
                            ->default(true),

                        DateTimePicker::make('inspected_at')
                            ->default(now()),

                        Textarea::make('notes')
                            ->label('Inspector Observations')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('rma.rma_number')
                    ->label('RMA #')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('warehouse.name')
                    ->label('Warehouse')
                    ->sortable(),

                TextColumn::make('overall_grade')
                    ->label('Grade')
                    ->badge()
                    ->colors([
                        'success' => ['unopened', 'like_new', 'good'],
                        'warning' => ['used', 'damaged'],
                        'danger' => ['defective', 'counterfeit', 'scrap'],
                    ]),

                IconColumn::make('passed_inspection')
                    ->label('Passed')
                    ->boolean(),

                TextColumn::make('suggested_disposition')
                    ->badge(),

                TextColumn::make('estimated_restocking_value')
                    ->money('USD'),

                TextColumn::make('inspected_at')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
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
            'index' => ListInspections::route('/'),
            'create' => CreateInspection::route('/create'),
            'view' => ViewInspection::route('/{record}'),
            'edit' => EditInspection::route('/{record}/edit'),
        ];
    }
}
