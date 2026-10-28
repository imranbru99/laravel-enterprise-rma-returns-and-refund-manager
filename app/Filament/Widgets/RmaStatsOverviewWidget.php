<?php

namespace App\Filament\Widgets;

use App\Models\Refund;
use App\Models\Rma;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RmaStatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalRmas = Rma::count();
        $pendingRmas = Rma::where('status', 'pending_review')->count();
        $approvedRmas = Rma::where('status', 'approved')->count();
        $totalRefunded = Refund::where('status', 'settled')->sum('amount');

        return [
            Stat::make('Total RMA Requests', $totalRmas)
                ->description('All time return volume')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary'),

            Stat::make('Pending Action / Review', $pendingRmas)
                ->description('Requires staff approval')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Approved & In-Flight', $approvedRmas)
                ->description('Awaiting warehouse delivery')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('info'),

            Stat::make('Total Settled Refunds', '$'.number_format($totalRefunded, 2))
                ->description('Reconciled to ledger')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}
