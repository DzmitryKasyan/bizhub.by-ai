<?php

declare(strict_types=1);

namespace App\Enums;

enum VerificationType: string
{
    case Phone = 'phone';
    case Identity = 'identity';
    case BusinessDocs = 'business_docs';
    case Financials = 'financials';
    case Vetted = 'vetted';

    public function label(): string
    {
        return match($this) {
            self::Phone => 'Телефон подтверждён',
            self::Identity => 'Личность подтверждена',
            self::BusinessDocs => 'Документы бизнеса подтверждены',
            self::Financials => 'Финансы подтверждены',
            self::Vetted => 'Проверено BizHub',
        };
    }

    public function shortLabel(): string
    {
        return match($this) {
            self::Phone => 'Телефон',
            self::Identity => 'KYC-lite',
            self::BusinessDocs => 'Документы',
            self::Financials => 'Финансы',
            self::Vetted => 'Vetted',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Phone => 'blue',
            self::Identity => 'indigo',
            self::BusinessDocs => 'emerald',
            self::Financials => 'amber',
            self::Vetted => 'purple',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
