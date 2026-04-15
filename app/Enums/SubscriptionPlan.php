<?php

declare(strict_types=1);

namespace App\Enums;

enum SubscriptionPlan: string
{
    case Free = 'free';
    case Start = 'start';
    case Business = 'business';
    case Premium = 'premium';

    public function label(): string
    {
        return match($this) {
            self::Free => 'Бесплатно',
            self::Start => 'Старт',
            self::Business => 'Бизнес',
            self::Premium => 'Премиум',
        };
    }

    public function maxListings(): int
    {
        return match($this) {
            self::Free => 1,
            self::Start => 5,
            self::Business => 20,
            self::Premium => 100,
        };
    }

    public function maxImages(): int
    {
        return match($this) {
            self::Free => 3,
            self::Start => 10,
            self::Business => 20,
            self::Premium => 50,
        };
    }

    public function hasAnalytics(): bool
    {
        return in_array($this, [self::Business, self::Premium]);
    }

    public function hasPrioritySupport(): bool
    {
        return $this === self::Premium;
    }

    public function priceMonthly(): float
    {
        return match($this) {
            self::Free => 0,
            self::Start => 19.90,
            self::Business => 49.90,
            self::Premium => 99.90,
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
