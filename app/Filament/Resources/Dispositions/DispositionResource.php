<?php

namespace App\Filament\Resources\Dispositions;

use App\Filament\Resources\Dispositions\Pages\CreateDisposition;
use App\Filament\Resources\Dispositions\Pages\EditDisposition;
use App\Filament\Resources\Dispositions\Pages\ListDispositions;
use App\Filament\Resources\Dispositions\Pages\ViewDisposition;
use App\Models\Disposition;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DispositionResource extends Resource
{
    protected static ?string $model = Disposition::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Logistics & Warehouse';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowTopRightOnSquare;

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Disposition Settlement')
                    ->columns(2)
                    ->schema([
                        Select::make('rma_id')
                            ->relationship('rma', 'rma_number')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('destination_type')
                            ->options([
                                'restock' => 'Restock to Inventory',
                                'refurbish' => 'Send to Refurbishment',
                                'repair' => 'Send to Repair Job',
                                'return_to_vendor' => 'Return to Vendor (RTV)',
                                'recycle' => 'Eco-Recycle',
                                'scrap' => 'Scrap / Dispose',
                            ])
                            ->default('restock')
                            ->required(),

                        TextInput::make('quantity')
                            ->numeric()
                            ->default(1)
                            ->required(),

                        TextInput::make('restocked_amount')
                            ->numeric()
                            ->prefix('$')
                            ->default(0.00),

                        TextInput::make('scrap_loss_amount')
                            ->numeric()
                            ->prefix('$')
                            ->default(0.00),

                        DateTimePicker::make('completed_at')
                            ->default(now()),

                        Textarea::make('notes')
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

                TextColumn::make('destination_type')
                    ->badge()
                    ->colors([
                        'success' => 'restock',
                        'warning' => ['refurbish', 'repair'],
                        'danger' => 'scrap',
                        'info' => 'return_to_vendor',
                    ])
                    ->formatStateUsing(fn ($state) => ucwords(str_replace('_', ' ', $state))),

                TextColumn::make('quantity')
                    ->sortable(),

                TextColumn::make('restocked_amount')
                    ->money('USD')
                    ->sortable(),

                TextColumn::make('scrap_loss_amount')
                    ->money('USD')
                    ->sortable(),

                TextColumn::make('completed_at')
                    ->dateTime('M d, Y')
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
            'index' => ListDispositions::route('/'),
            'create' => CreateDisposition::route('/create'),
            'view' => ViewDisposition::route('/{record}'),
            'edit' => EditDisposition::route('/{record}/edit'),
        ];
    }
}
