<?php

namespace App\Filament\Widgets;

use App\Models\Rma;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ReturnsByReasonChartWidget extends ChartWidget
{
    protected ?string $heading = 'Returns Distribution by Reason';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = Rma::select('return_reason', DB::raw('count(*) as total'))
            ->groupBy('return_reason')
            ->pluck('total', 'return_reason')
            ->toArray();

        $labels = [];
        $values = [];

        foreach ($data as $reason => $total) {
            $labels[] = ucwords(str_replace('_', ' ', $reason));
            $values[] = $total;
        }

        if (empty($labels)) {
            $labels = ['Defective', 'Wrong Item', 'Size / Fit', 'Changed Mind'];
            $values = [12, 5, 8, 4];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Return Cases',
                    'data' => $values,
                    'backgroundColor' => ['#6366f1', '#f59e0b', '#10b981', '#ef4444', '#8b5cf6'],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
