<?php

declare(strict_types=1);

namespace App\Enums;

enum OwnershipType: string
{
    case IP = 'ip';
    case OOO = 'ooo';
    case ZAO = 'zao';
    case UP = 'up';
    case RUE = 'rue';
    case Other = 'other';

    public function label(): string
    {
        return match($this) {
            self::IP => 'ИП (Индивидуальный предприниматель)',
            self::OOO => 'ООО (Общество с ограниченной ответственностью)',
            self::ZAO => 'ЗАО (Закрытое акционерное общество)',
            self::UP => 'УП (Унитарное предприятие)',
            self::RUE => 'РУП (Республиканское унитарное предприятие)',
            self::Other => 'Другое',
        };
    }

    public function shortLabel(): string
    {
        return match($this) {
            self::IP => 'ИП',
            self::OOO => 'ООО',
            self::ZAO => 'ЗАО',
            self::UP => 'УП',
            self::RUE => 'РУП',
            self::Other => 'Другое',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
