<?php

namespace App\Filament\Finance\Resources\CreditNotes;

use App\Filament\Finance\Resources\CreditNotes\Pages\ListCreditNotes;
use App\Filament\Finance\Resources\CreditNotes\Pages\ViewCreditNote;
use App\Models\CreditNote;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CreditNoteResource extends Resource
{
    protected static ?string $model = CreditNote::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCurrencyBangladeshi;

    protected static string|\UnitEnum|null $navigationGroup = 'Financial Operations';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Credit Note')
                    ->columns(2)
                    ->schema([
                        TextInput::make('credit_note_number')
                            ->required()
                            ->disabled(),

                        Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->required()
                            ->disabled(),

                        TextInput::make('amount')
                            ->numeric()
                            ->prefix('$')
                            ->required(),

                        TextInput::make('balance_remaining')
                            ->numeric()
                            ->prefix('$')
                            ->required(),

                        Select::make('status')
                            ->options([
                                'active' => 'Active (Available)',
                                'partially_redeemed' => 'Partially Redeemed',
                                'redeemed' => 'Fully Redeemed',
                                'void' => 'Void',
                            ])
                            ->required(),

                        DateTimePicker::make('issued_at'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('credit_note_number')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('rma.rma_number')
                    ->label('RMA #')
                    ->searchable(),

                TextColumn::make('amount')
                    ->money('USD')
                    ->sortable(),

                TextColumn::make('balance_remaining')
                    ->money('USD')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'active',
                        'warning' => 'partially_redeemed',
                        'gray' => 'redeemed',
                        'danger' => 'void',
                    ]),

                TextColumn::make('issued_at')
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->recordActions([
                ViewAction::make(),

                Action::make('pdf_en')
                    ->label('PDF (English)')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(fn (CreditNote $record) => route('documents.credit_note', ['creditNote' => $record, 'locale' => 'en']))
                    ->openUrlInNewTab(),

                Action::make('pdf_bn')
                    ->label('PDF (বাংলা)')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(fn (CreditNote $record) => route('documents.credit_note', ['creditNote' => $record, 'locale' => 'bn']))
                    ->openUrlInNewTab(),

                Action::make('pdf_ar')
                    ->label('PDF (العربية)')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(fn (CreditNote $record) => route('documents.credit_note', ['creditNote' => $record, 'locale' => 'ar']))
                    ->openUrlInNewTab(),
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
            'index' => ListCreditNotes::route('/'),
            'view' => ViewCreditNote::route('/{record}'),
        ];
    }
}
