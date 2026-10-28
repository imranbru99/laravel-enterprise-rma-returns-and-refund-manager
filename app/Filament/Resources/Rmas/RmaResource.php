<?php

namespace App\Filament\Resources\Rmas;

use App\Filament\Resources\Rmas\Pages\CreateRma;
use App\Filament\Resources\Rmas\Pages\EditRma;
use App\Filament\Resources\Rmas\Pages\ListRmas;
use App\Filament\Resources\Rmas\Pages\ViewRma;
use App\Models\Order;
use App\Models\Rma;
use App\Services\InventoryEngineService;
use App\Services\RefundEngineService;
use App\Services\RmaEngineService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RmaResource extends Resource
{
    protected static ?string $model = Rma::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Returns & RMA';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowPath;

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Return Authorization Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('rma_number')
                            ->label('RMA Number')
                            ->default(fn () => app(RmaEngineService::class)->generateRmaNumber())
                            ->disabled()
                            ->dehydrated(),

                        Select::make('order_id')
                            ->label('Original Order')
                            ->relationship('order', 'order_number')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $order = Order::find($state);
                                if ($order) {
                                    $set('customer_id', $order->customer_id);
                                    $set('branch_id', $order->branch_id);
                                    $set('currency', $order->currency);
                                }
                            }),

                        Select::make('customer_id')
                            ->label('Customer')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('branch_id')
                            ->label('Processing Branch')
                            ->relationship('branch', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('resolution_type')
                            ->label('Requested Resolution')
                            ->options([
                                'refund' => 'Refund to Payment / Credit',
                                'exchange' => 'Product / Size Exchange',
                                'replacement' => 'Defect Replacement',
                                'repair' => 'Warranty Repair',
                                'store_credit' => 'Store Credit',
                            ])
                            ->default('refund')
                            ->required(),

                        Select::make('status')
                            ->label('Workflow Status')
                            ->options([
                                'draft' => 'Draft',
                                'pending_review' => 'Pending Review',
                                'approved' => 'Approved',
                                'label_generated' => 'Label Generated',
                                'item_shipped' => 'Item In Transit',
                                'item_received' => 'Received at Warehouse',
                                'inspecting' => 'Under Inspection',
                                'inspection_completed' => 'Inspection Completed',
                                'resolution_pending' => 'Resolution Pending',
                                'refunded' => 'Refunded',
                                'exchanged' => 'Exchanged',
                                'replacement_sent' => 'Replacement Dispatched',
                                'repaired' => 'Repaired',
                                'closed' => 'Closed',
                                'rejected' => 'Rejected',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('pending_review')
                            ->required(),

                        Select::make('return_reason')
                            ->label('Return Reason')
                            ->options([
                                'defective' => 'Defective / Not Working',
                                'damaged' => 'Damaged during delivery',
                                'wrong_item' => 'Received Incorrect Item',
                                'size_fit' => 'Size / Fit Mismatch',
                                'not_as_described' => 'Item Not As Described',
                                'customer_remorse' => 'Customer Changed Mind',
                            ])
                            ->required(),

                        Select::make('priority')
                            ->label('Priority')
                            ->options([
                                'low' => 'Low',
                                'normal' => 'Normal',
                                'high' => 'High',
                                'urgent' => 'Urgent / VIP',
                            ])
                            ->default('normal'),

                        TextInput::make('estimated_refund_amount')
                            ->label('Estimated Refund Amount')
                            ->numeric()
                            ->prefix('$')
                            ->default(0.00),

                        TextInput::make('approved_refund_amount')
                            ->label('Approved Refund Amount')
                            ->numeric()
                            ->prefix('$')
                            ->default(0.00),

                        Textarea::make('customer_notes')
                            ->label('Customer Explanation')
                            ->columnSpanFull(),

                        Textarea::make('internal_notes')
                            ->label('Internal Staff Notes')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('rma_number')
                    ->label('RMA #')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),

                TextColumn::make('order.order_number')
                    ->label('Order #')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'warning' => ['pending_review', 'inspecting'],
                        'info' => ['approved', 'label_generated', 'item_shipped'],
                        'primary' => ['item_received', 'inspection_completed'],
                        'success' => ['refunded', 'exchanged', 'replacement_sent', 'repaired', 'closed'],
                        'danger' => ['rejected', 'cancelled'],
                    ]),

                TextColumn::make('resolution_type')
                    ->label('Resolution')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                TextColumn::make('estimated_refund_amount')
                    ->label('Est. Refund')
                    ->money(fn ($record) => $record->currency ?? 'USD')
                    ->sortable(),

                TextColumn::make('branch.name')
                    ->label('Branch')
                    ->sortable(),

                TextColumn::make('priority')
                    ->label('Priority')
                    ->badge()
                    ->colors([
                        'danger' => 'urgent',
                        'warning' => 'high',
                        'info' => 'normal',
                        'gray' => 'low',
                    ]),

                TextColumn::make('created_at')
                    ->label('Requested')
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending_review' => 'Pending Review',
                        'approved' => 'Approved',
                        'item_received' => 'Received at Warehouse',
                        'inspection_completed' => 'Inspection Completed',
                        'refunded' => 'Refunded',
                        'closed' => 'Closed',
                        'rejected' => 'Rejected',
                    ]),

                SelectFilter::make('resolution_type')
                    ->options([
                        'refund' => 'Refund',
                        'exchange' => 'Exchange',
                        'replacement' => 'Replacement',
                        'repair' => 'Repair',
                    ]),

                SelectFilter::make('branch_id')
                    ->label('Branch')
                    ->relationship('branch', 'name'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),

                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Rma $record) => $record->status === 'pending_review')
                    ->requiresConfirmation()
                    ->action(function (Rma $record) {
                        app(RmaEngineService::class)->transitionStatus(
                            $record,
                            'approved',
                            'Approved by RMA staff'
                        );
                        Notification::make()->title('RMA Approved Successfully')->success()->send();
                    }),

                Action::make('receive')
                    ->label('Receive')
                    ->icon('heroicon-o-inbox-arrow-down')
                    ->color('info')
                    ->visible(fn (Rma $record) => in_array($record->status, ['approved', 'label_generated', 'item_shipped']))
                    ->action(function (Rma $record) {
                        $warehouseId = $record->branch?->warehouses()->first()?->id ?? 1;
                        app(InventoryEngineService::class)->receiveItem($record, $warehouseId);
                        Notification::make()->title('Package Marked Received at Warehouse')->info()->send();
                    }),

                Action::make('process_refund')
                    ->label('Issue Refund')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->visible(fn (Rma $record) => in_array($record->status, ['inspection_completed', 'resolution_pending', 'approved']))
                    ->form([
                        TextInput::make('amount')
                            ->label('Refund Amount ($)')
                            ->numeric()
                            ->default(fn (Rma $record) => (float) ($record->approved_refund_amount > 0 ? $record->approved_refund_amount : $record->estimated_refund_amount))
                            ->required(),
                        Select::make('method')
                            ->label('Refund Method')
                            ->options([
                                'original_payment_method' => 'Original Payment Method',
                                'store_credit' => 'Store Credit Wallet',
                                'bank_transfer' => 'Bank Wire Transfer',
                                'cash' => 'Cash (Store Pickup)',
                            ])
                            ->default('original_payment_method')
                            ->required(),
                        Select::make('gateway_name')
                            ->label('Payment Gateway')
                            ->options([
                                'mock_gateway' => 'Simulated Secure Sandbox Gateway',
                                'stripe' => 'Stripe Payments',
                                'paypal' => 'PayPal Commerce',
                            ])
                            ->default('mock_gateway')
                            ->required(),
                    ])
                    ->action(function (Rma $record, array $data) {
                        app(RefundEngineService::class)->processRefund(
                            rma: $record,
                            amount: (float) $data['amount'],
                            method: $data['method'],
                            gatewayName: $data['gateway_name']
                        );
                        Notification::make()->title('Refund Settled and Ledger Updated')->success()->send();
                    }),

                Action::make('print_label')
                    ->label('Shipping Label')
                    ->icon('heroicon-o-printer')
                    ->color('gray')
                    ->url(fn (Rma $record) => route('documents.rma.label', $record))
                    ->openUrlInNewTab(),

                Action::make('print_voucher')
                    ->label('RMA Voucher')
                    ->icon('heroicon-o-document-text')
                    ->color('gray')
                    ->url(fn (Rma $record) => route('documents.rma.voucher', $record))
                    ->openUrlInNewTab(),
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
            'index' => ListRmas::route('/'),
            'create' => CreateRma::route('/create'),
            'view' => ViewRma::route('/{record}'),
            'edit' => EditRma::route('/{record}/edit'),
        ];
    }
}
