<?php

declare(strict_types=1);

namespace App\Enums;

enum UserRole: string
{
    case User = 'user';
    case Entrepreneur = 'entrepreneur';
    case Investor = 'investor';
    case Broker = 'broker';
    case Moderator = 'moderator';
    case Admin = 'admin';

    public function label(): string
    {
        return match($this) {
            self::User => 'Пользователь',
            self::Entrepreneur => 'Предприниматель',
            self::Investor => 'Инвестор',
            self::Broker => 'Брокер',
            self::Moderator => 'Модератор',
            self::Admin => 'Администратор',
        };
    }

    public function isStaff(): bool
    {
        return in_array($this, [self::Moderator, self::Admin]);
    }

    public function canPublishListings(): bool
    {
        return in_array($this, [
            self::Entrepreneur,
            self::Investor,
            self::Broker,
            self::Moderator,
            self::Admin,
        ]);
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
