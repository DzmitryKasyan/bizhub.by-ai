<?php

declare(strict_types=1);

namespace App\Enums;

enum DealStage: string
{
    case Meeting = 'meeting';
    case Loi = 'loi';
    case DueDiligence = 'due_diligence';
    case Notary = 'notary';
    case Closing = 'closing';

    public function label(): string
    {
        return match($this) {
            self::Meeting => 'Встреча',
            self::Loi => 'LOI / предварительное соглашение',
            self::DueDiligence => 'Due diligence',
            self::Notary => 'Сделка у нотариуса',
            self::Closing => 'Закрытие сделки',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
