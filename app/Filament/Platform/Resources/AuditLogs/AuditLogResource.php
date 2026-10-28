<?php

namespace App\Filament\Platform\Resources\AuditLogs;

use App\Filament\Platform\Resources\AuditLogs\Pages\ListAuditLogs;
use App\Filament\Platform\Resources\AuditLogs\Pages\ViewAuditLog;
use App\Models\AuditLog;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AuditLogResource extends Resource
{
    protected static ?string $model = AuditLog::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentMagnifyingGlass;

    protected static string|\UnitEnum|null $navigationGroup = 'Platform Governance';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Audit Trail Record (Immutable)')
                    ->columns(2)
                    ->schema([
                        TextInput::make('event')
                            ->disabled(),

                        TextInput::make('ip_address')
                            ->disabled(),

                        TextInput::make('auditable_type')
                            ->disabled(),

                        TextInput::make('auditable_id')
                            ->disabled(),

                        Textarea::make('justification')
                            ->disabled()
                            ->columnSpanFull(),

                        KeyValue::make('old_values')
                            ->disabled(),

                        KeyValue::make('new_values')
                            ->disabled(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('event')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tenant.name')
                    ->label('Tenant')
                    ->searchable(),

                TextColumn::make('user.name')
                    ->label('Actor')
                    ->searchable(),

                TextColumn::make('auditable_type')
                    ->label('Target Entity')
                    ->formatStateUsing(fn ($state) => class_basename($state)),

                TextColumn::make('ip_address')
                    ->label('IP'),

                TextColumn::make('justification')
                    ->limit(40),

                TextColumn::make('created_at')
                    ->dateTime('M d, Y H:i:s')
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
            'index' => ListAuditLogs::route('/'),
            'view' => ViewAuditLog::route('/{record}'),
        ];
    }
}
