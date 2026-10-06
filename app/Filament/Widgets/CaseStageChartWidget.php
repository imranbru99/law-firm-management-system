<?php

namespace App\Filament\Widgets;

use App\Models\Stage;
use Filament\Widgets\ChartWidget;

class CaseStageChartWidget extends ChartWidget
{
    protected static ?int $sort = 3;
    protected static ?string $heading = 'Litigation Pipeline by Procedural Stage';

    protected function getData(): array
    {
        $stages = Stage::withCount('cases')->get();

        return [
            'datasets' => [
                [
                    'label' => 'Cases',
                    'data' => $stages->pluck('cases_count')->toArray(),
                    'backgroundColor' => [
                        '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#ec4899', '#64748b'
                    ],
                ],
            ],
            'labels' => $stages->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
