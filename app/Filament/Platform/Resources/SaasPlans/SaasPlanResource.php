<?php

namespace App\Filament\Platform\Resources\SaasPlans;

use App\Filament\Platform\Resources\SaasPlans\Pages\CreateSaasPlan;
use App\Filament\Platform\Resources\SaasPlans\Pages\EditSaasPlan;
use App\Filament\Platform\Resources\SaasPlans\Pages\ListSaasPlans;
use App\Filament\Platform\Resources\SaasPlans\Pages\ViewSaasPlan;
use App\Models\SaasPlan;
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

class SaasPlanResource extends Resource
{
    protected static ?string $model = SaasPlan::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static string|\UnitEnum|null $navigationGroup = 'SaaS Management';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Subscription Tier Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('key')
                            ->required()
                            ->maxLength(100),

                        TextInput::make('price_monthly')
                            ->numeric()
                            ->prefix('$')
                            ->required(),

                        TextInput::make('price_annual')
                            ->numeric()
                            ->prefix('$')
                            ->required(),

                        TextInput::make('max_branches')
                            ->numeric()
                            ->default(1)
                            ->label('Max Branches Included'),

                        TextInput::make('max_monthly_rmas')
                            ->numeric()
                            ->default(100)
                            ->label('Monthly RMA Quota'),

                        Toggle::make('has_ai_access')
                            ->label('Includes AI Intelligence & Automation')
                            ->default(true),

                        Toggle::make('has_white_label')
                            ->label('Includes White-Labeling & Custom Domain')
                            ->default(false),

                        Toggle::make('is_active')
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

                TextColumn::make('key')
                    ->badge(),

                TextColumn::make('price_monthly')
                    ->money('USD')
                    ->suffix('/mo')
                    ->sortable(),

                TextColumn::make('price_annual')
                    ->money('USD')
                    ->suffix('/yr')
                    ->sortable(),

                TextColumn::make('max_branches')
                    ->label('Branches Limit'),

                TextColumn::make('max_monthly_rmas')
                    ->label('Monthly RMAs'),

                IconColumn::make('has_ai_access')
                    ->label('AI')
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
            'index' => ListSaasPlans::route('/'),
            'create' => CreateSaasPlan::route('/create'),
            'view' => ViewSaasPlan::route('/{record}'),
            'edit' => EditSaasPlan::route('/{record}/edit'),
        ];
    }
}
