<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class NewUsersWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Новые сегодня', User::whereDate('created_at', today())->count())
                ->description('Новые сегодня')
                ->icon('heroicon-o-user-plus')
                ->color('success'),

            Stat::make('Всего пользователей', User::count())
                ->description('Всего пользователей')
                ->icon('heroicon-o-users')
                ->color('primary'),
        ];
    }
}
