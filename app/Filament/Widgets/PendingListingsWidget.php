<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\ListingStatus;
use App\Models\Listing;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PendingListingsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('На модерации', Listing::where('status', ListingStatus::Pending)->count())
                ->description('Ожидают модерации')
                ->icon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Одобрено сегодня', Listing::where('status', ListingStatus::Active)
                ->whereDate('updated_at', today())
                ->count())
                ->description('Одобрено сегодня')
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Отклонено сегодня', Listing::where('status', ListingStatus::Rejected)
                ->whereDate('updated_at', today())
                ->count())
                ->description('Отклонено сегодня')
                ->icon('heroicon-o-x-circle')
                ->color('danger'),
        ];
    }
}
