<?php

namespace App\Filament\Resources\Branches;

use App\Filament\Resources\Branches\Pages\CreateBranch;
use App\Filament\Resources\Branches\Pages\EditBranch;
use App\Filament\Resources\Branches\Pages\ListBranches;
use App\Filament\Resources\Branches\Pages\ViewBranch;
use App\Models\Branch;
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

class BranchResource extends Resource
{
    protected static ?string $model = Branch::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Organization';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Branch Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('code')
                            ->required()
                            ->maxLength(50),

                        TextInput::make('city')
                            ->maxLength(100),

                        TextInput::make('country')
                            ->default('US')
                            ->maxLength(2),

                        TextInput::make('phone')
                            ->tel(),

                        TextInput::make('email')
                            ->email(),

                        TextInput::make('auto_approve_threshold')
                            ->numeric()
                            ->prefix('$')
                            ->default(50.00),

                        Toggle::make('allow_cross_branch_returns')
                            ->label('Allow Cross-Branch Returns (Accept returns from other branches)')
                            ->default(true),

                        Toggle::make('is_active')
                            ->label('Operational Status')
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

                TextColumn::make('city')
                    ->searchable(),

                IconColumn::make('allow_cross_branch_returns')
                    ->label('Cross-Branch')
                    ->boolean(),

                TextColumn::make('auto_approve_threshold')
                    ->money('USD')
                    ->label('Auto-Approve Limit'),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                TextColumn::make('warehouses_count')
                    ->counts('warehouses')
                    ->label('Warehouses'),

                TextColumn::make('users_count')
                    ->counts('users')
                    ->label('Staff'),
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
            'index' => ListBranches::route('/'),
            'create' => CreateBranch::route('/create'),
            'view' => ViewBranch::route('/{record}'),
            'edit' => EditBranch::route('/{record}/edit'),
        ];
    }
}
