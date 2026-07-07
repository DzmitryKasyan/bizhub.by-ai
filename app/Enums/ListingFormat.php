<?php

declare(strict_types=1);

namespace App\Enums;

enum ListingFormat: string
{
    case EstablishedBusiness = 'established_business';
    case AssetSale = 'asset_sale';
    case RealEstateOnly = 'real_estate_only';

    public function label(): string
    {
        return match($this) {
            self::EstablishedBusiness => 'Действующий бизнес',
            self::AssetSale => 'Продажа активов',
            self::RealEstateOnly => 'Только недвижимость / помещение',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
