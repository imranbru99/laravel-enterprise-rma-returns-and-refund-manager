<?php

namespace App\Filament\Platform\Resources\Tenants;

use App\Filament\Platform\Resources\Tenants\Pages\CreateTenant;
use App\Filament\Platform\Resources\Tenants\Pages\EditTenant;
use App\Filament\Platform\Resources\Tenants\Pages\ListTenants;
use App\Filament\Platform\Resources\Tenants\Pages\ViewTenant;
use App\Models\Tenant;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice;

    protected static string|\UnitEnum|null $navigationGroup = 'SaaS Management';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Tenant Organization')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true),

                        TextInput::make('subdomain')
                            ->suffix('.returnsmanager.io'),

                        TextInput::make('domain')
                            ->label('Custom Domain'),

                        Select::make('plan_id')
                            ->relationship('plan', 'name')
                            ->searchable()
                            ->preload(),

                        Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'trial' => 'Trial Period',
                                'suspended' => 'Suspended (Access Blocked, Data Retained)',
                                'archived' => 'Archived',
                            ])
                            ->default('active')
                            ->required(),
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

                TextColumn::make('slug')
                    ->searchable(),

                TextColumn::make('plan.name')
                    ->label('SaaS Plan')
                    ->badge(),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'active',
                        'info' => 'trial',
                        'danger' => 'suspended',
                        'gray' => 'archived',
                    ]),

                TextColumn::make('branches_count')
                    ->counts('branches')
                    ->label('Branches'),

                TextColumn::make('rmas_count')
                    ->counts('rmas')
                    ->label('Total RMAs'),

                TextColumn::make('created_at')
                    ->date('M d, Y')
                    ->sortable(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),

                Action::make('suspend')
                    ->label('Suspend')
                    ->color('danger')
                    ->icon('heroicon-o-no-symbol')
                    ->visible(fn (Tenant $record) => $record->status === 'active')
                    ->requiresConfirmation()
                    ->action(function (Tenant $record) {
                        $record->update(['status' => 'suspended']);
                        Notification::make()->title('Tenant Suspended (Data Preserved)')->warning()->send();
                    }),

                Action::make('activate')
                    ->label('Activate')
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->visible(fn (Tenant $record) => $record->status === 'suspended')
                    ->action(function (Tenant $record) {
                        $record->update(['status' => 'active']);
                        Notification::make()->title('Tenant Re-Activated')->success()->send();
                    }),
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
            'index' => ListTenants::route('/'),
            'create' => CreateTenant::route('/create'),
            'view' => ViewTenant::route('/{record}'),
            'edit' => EditTenant::route('/{record}/edit'),
        ];
    }
}
