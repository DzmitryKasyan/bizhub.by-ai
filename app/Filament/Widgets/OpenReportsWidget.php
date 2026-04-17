<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Report;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OpenReportsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Открытые жалобы', Report::where('status', 'pending')->count())
                ->description('Открытые жалобы')
                ->icon('heroicon-o-flag')
                ->color('warning'),

            Stat::make('На рассмотрении', Report::where('status', 'reviewed')->count())
                ->description('На рассмотрении')
                ->icon('heroicon-o-eye')
                ->color('info'),
        ];
    }
}
