<?php

declare(strict_types=1);

namespace App\Enums;

enum ListingStatus: string
{
    case Draft = 'draft';
    case Pending = 'pending';
    case Active = 'active';
    case Sold = 'sold';
    case Archived = 'archived';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match($this) {
            self::Draft => 'Черновик',
            self::Pending => 'На модерации',
            self::Active => 'Активно',
            self::Sold => 'Продано',
            self::Archived => 'В архиве',
            self::Rejected => 'Отклонено',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Draft => 'gray',
            self::Pending => 'warning',
            self::Active => 'success',
            self::Sold => 'info',
            self::Archived => 'gray',
            self::Rejected => 'danger',
        };
    }

    public function isPubliclyVisible(): bool
    {
        return $this === self::Active;
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
