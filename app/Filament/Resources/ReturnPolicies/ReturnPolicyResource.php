<?php

namespace App\Filament\Resources\ReturnPolicies;

use App\Filament\Resources\ReturnPolicies\Pages\CreateReturnPolicy;
use App\Filament\Resources\ReturnPolicies\Pages\EditReturnPolicy;
use App\Filament\Resources\ReturnPolicies\Pages\ListReturnPolicies;
use App\Filament\Resources\ReturnPolicies\Pages\ViewReturnPolicy;
use App\Models\ReturnPolicy;
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

class ReturnPolicyResource extends Resource
{
    protected static ?string $model = ReturnPolicy::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Returns & RMA';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Policy Configuration')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('version')
                            ->numeric()
                            ->default(1)
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('return_window_days')
                            ->numeric()
                            ->default(30)
                            ->label('Return Window (Days)')
                            ->required(),

                        TextInput::make('auto_approve_max_amount')
                            ->numeric()
                            ->prefix('$')
                            ->default(50.00)
                            ->label('Auto-Approve Threshold ($)')
                            ->helperText('Returns under this amount with eligible items can be approved automatically.'),

                        TextInput::make('restocking_fee_percent')
                            ->numeric()
                            ->suffix('%')
                            ->default(0.00)
                            ->label('Restocking Fee'),

                        Toggle::make('requires_receipt')
                            ->label('Requires Proof of Purchase')
                            ->default(true),

                        Toggle::make('requires_original_packaging')
                            ->label('Requires Original Packaging')
                            ->default(true),

                        Toggle::make('free_return_shipping')
                            ->label('Free Return Shipping')
                            ->default(false),

                        Toggle::make('is_active')
                            ->label('Active Store Policy')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('version')
                    ->badge()
                    ->formatStateUsing(fn ($state) => "v{$state}"),

                TextColumn::make('return_window_days')
                    ->suffix(' days')
                    ->sortable(),

                TextColumn::make('auto_approve_max_amount')
                    ->money('USD')
                    ->sortable(),

                TextColumn::make('restocking_fee_percent')
                    ->suffix('%'),

                IconColumn::make('free_return_shipping')
                    ->boolean(),

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
            'index' => ListReturnPolicies::route('/'),
            'create' => CreateReturnPolicy::route('/create'),
            'view' => ViewReturnPolicy::route('/{record}'),
            'edit' => EditReturnPolicy::route('/{record}/edit'),
        ];
    }
}
