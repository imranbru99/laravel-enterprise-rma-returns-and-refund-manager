<?php

namespace App\Filament\Finance\Resources\FinancialJournals;

use App\Filament\Finance\Resources\FinancialJournals\Pages\ListFinancialJournals;
use App\Filament\Finance\Resources\FinancialJournals\Pages\ViewFinancialJournal;
use App\Models\FinancialJournal;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FinancialJournalResource extends Resource
{
    protected static ?string $model = FinancialJournal::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedScale;

    protected static string|\UnitEnum|null $navigationGroup = 'Financial Operations';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('General Ledger Journal')
                    ->columns(2)
                    ->schema([
                        DatePicker::make('entry_date')
                            ->required(),

                        TextInput::make('reference_type')
                            ->disabled(),

                        TextInput::make('total_debit')
                            ->numeric()
                            ->prefix('$')
                            ->disabled(),

                        TextInput::make('total_credit')
                            ->numeric()
                            ->prefix('$')
                            ->disabled(),

                        Textarea::make('description')
                            ->columnSpanFull()
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('entry_date')
                    ->date('M d, Y')
                    ->sortable(),

                TextColumn::make('description')
                    ->searchable()
                    ->limit(50),

                TextColumn::make('reference_type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => strtoupper($state)),

                TextColumn::make('total_debit')
                    ->money('USD')
                    ->label('Debit Total'),

                TextColumn::make('total_credit')
                    ->money('USD')
                    ->label('Credit Total'),

                TextColumn::make('balance_status')
                    ->label('Ledger Balance')
                    ->badge()
                    ->getStateUsing(fn (FinancialJournal $record) => abs((float) $record->total_debit - (float) $record->total_credit) < 0.01 ? 'Balanced' : 'Imbalanced')
                    ->colors([
                        'success' => 'Balanced',
                        'danger' => 'Imbalanced',
                    ]),

                TextColumn::make('created_at')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
            ])
            ->recordActions([
                ViewAction::make(),
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
            'index' => ListFinancialJournals::route('/'),
            'view' => ViewFinancialJournal::route('/{record}'),
        ];
    }
}
