<?php

namespace App\Filament\Resources\RepairJobs;

use App\Filament\Resources\RepairJobs\Pages\CreateRepairJob;
use App\Filament\Resources\RepairJobs\Pages\EditRepairJob;
use App\Filament\Resources\RepairJobs\Pages\ListRepairJobs;
use App\Filament\Resources\RepairJobs\Pages\ViewRepairJob;
use App\Models\RepairJob;
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

class RepairJobResource extends Resource
{
    protected static ?string $model = RepairJob::class;

    protected static string|\UnitEnum|null $navigationGroup = 'After-Sales & Warranty';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Repair Job Order')
                    ->columns(2)
                    ->schema([
                        TextInput::make('job_number')
                            ->required()
                            ->default(fn () => 'REP-'.date('Ym').'-'.strtoupper(bin2hex(random_bytes(3)))),

                        Select::make('branch_id')
                            ->relationship('branch', 'name')
                            ->searchable()
                            ->preload(),

                        Select::make('technician_id')
                            ->relationship('technician', 'name')
                            ->searchable()
                            ->preload(),

                        Select::make('status')
                            ->options([
                                'queued' => 'Queued',
                                'diagnosed' => 'Diagnosed',
                                'awaiting_parts' => 'Awaiting Spare Parts',
                                'in_progress' => 'Repair in Progress',
                                'tested' => 'Quality Tested',
                                'completed' => 'Completed',
                                'unrepairable' => 'Unrepairable',
                            ])
                            ->default('queued')
                            ->required(),

                        TextInput::make('labor_cost')
                            ->numeric()
                            ->prefix('$')
                            ->default(0.00),

                        TextInput::make('parts_cost')
                            ->numeric()
                            ->prefix('$')
                            ->default(0.00),

                        DateTimePicker::make('completed_at'),

                        Textarea::make('diagnosis')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('job_number')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('branch.name')
                    ->sortable(),

                TextColumn::make('technician.name')
                    ->label('Technician'),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'gray' => 'queued',
                        'warning' => ['diagnosed', 'awaiting_parts'],
                        'info' => 'in_progress',
                        'success' => ['tested', 'completed'],
                        'danger' => 'unrepairable',
                    ])
                    ->formatStateUsing(fn ($state) => ucwords(str_replace('_', ' ', $state))),

                TextColumn::make('labor_cost')
                    ->money('USD'),

                TextColumn::make('parts_cost')
                    ->money('USD'),

                TextColumn::make('created_at')
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
            'index' => ListRepairJobs::route('/'),
            'create' => CreateRepairJob::route('/create'),
            'view' => ViewRepairJob::route('/{record}'),
            'edit' => EditRepairJob::route('/{record}/edit'),
        ];
    }
}
