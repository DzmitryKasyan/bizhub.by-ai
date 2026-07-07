<?php

declare(strict_types=1);

namespace App\Enums;

enum DealStageStatus: string
{
    case Pending = 'pending';
    case InProgress = 'in_progress';
    case Done = 'done';
    case Skipped = 'skipped';

    public function label(): string
    {
        return match($this) {
            self::Pending => 'Не начато',
            self::InProgress => 'В процессе',
            self::Done => 'Выполнено',
            self::Skipped => 'Пропущено',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Pending => 'gray',
            self::InProgress => 'warning',
            self::Done => 'success',
            self::Skipped => 'info',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
