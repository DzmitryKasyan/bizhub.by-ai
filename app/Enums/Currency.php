<?php

declare(strict_types=1);

namespace App\Enums;

enum Currency: string
{
    case BYN = 'BYN';
    case USD = 'USD';
    case EUR = 'EUR';
    case RUB = 'RUB';

    public function label(): string
    {
        return match($this) {
            self::BYN => 'Белорусский рубль (BYN)',
            self::USD => 'Доллар США (USD)',
            self::EUR => 'Евро (EUR)',
            self::RUB => 'Российский рубль (RUB)',
        };
    }

    public function symbol(): string
    {
        return match($this) {
            self::BYN => 'Br',
            self::USD => '$',
            self::EUR => '€',
            self::RUB => '₽',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
