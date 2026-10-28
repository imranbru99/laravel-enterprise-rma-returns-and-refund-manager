<?php

namespace App\Filament\Resources\WarrantyClaims;

use App\Filament\Resources\WarrantyClaims\Pages\CreateWarrantyClaim;
use App\Filament\Resources\WarrantyClaims\Pages\EditWarrantyClaim;
use App\Filament\Resources\WarrantyClaims\Pages\ListWarrantyClaims;
use App\Filament\Resources\WarrantyClaims\Pages\ViewWarrantyClaim;
use App\Models\WarrantyClaim;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class WarrantyClaimResource extends Resource
{
    protected static ?string $model = WarrantyClaim::class;

    protected static string|\UnitEnum|null $navigationGroup = 'After-Sales & Warranty';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCheckBadge;

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Warranty Claim Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('claim_number')
                            ->required()
                            ->default(fn () => 'WAR-'.date('Ym').'-'.strtoupper(bin2hex(random_bytes(3)))),

                        Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('product_id')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('serial_number')
                            ->label('Device Serial Number')
                            ->maxLength(100),

                        DatePicker::make('purchase_date')
                            ->required(),

                        DatePicker::make('warranty_expires_at')
                            ->required(),

                        Select::make('status')
                            ->options([
                                'submitted' => 'Submitted',
                                'under_review' => 'Under Review',
                                'approved' => 'Approved',
                                'repair_in_progress' => 'Repair In Progress',
                                'replacement_approved' => 'Replacement Approved',
                                'rejected' => 'Rejected',
                                'closed' => 'Closed',
                            ])
                            ->default('submitted')
                            ->required(),

                        Textarea::make('issue_description')
                            ->columnSpanFull()
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('claim_number')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('customer.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('product.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('serial_number')
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'submitted',
                        'info' => ['under_review', 'repair_in_progress'],
                        'success' => ['approved', 'replacement_approved', 'closed'],
                        'danger' => 'rejected',
                    ]),

                TextColumn::make('warranty_expires_at')
                    ->date('M d, Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'submitted' => 'Submitted',
                        'approved' => 'Approved',
                        'repair_in_progress' => 'Repair In Progress',
                    ]),
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
            'index' => ListWarrantyClaims::route('/'),
            'create' => CreateWarrantyClaim::route('/create'),
            'view' => ViewWarrantyClaim::route('/{record}'),
            'edit' => EditWarrantyClaim::route('/{record}/edit'),
        ];
    }
}
