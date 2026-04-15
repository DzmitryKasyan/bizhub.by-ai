<?php

declare(strict_types=1);

namespace App\Enums;

enum ListingType: string
{
    case SellBusiness = 'sell_business';
    case BuyBusiness = 'buy_business';
    case SeekInvestment = 'seek_investment';
    case OfferInvestment = 'offer_investment';
    case Franchise = 'franchise';
    case Partnership = 'partnership';
    case RealEstate = 'real_estate';
    case Equipment = 'equipment';

    public function label(): string
    {
        return match($this) {
            self::SellBusiness => 'Продажа бизнеса',
            self::BuyBusiness => 'Куплю бизнес',
            self::SeekInvestment => 'Ищу инвестиции',
            self::OfferInvestment => 'Предлагаю инвестиции',
            self::Franchise => 'Франшиза',
            self::Partnership => 'Партнёрство',
            self::RealEstate => 'Коммерческая недвижимость',
            self::Equipment => 'Оборудование',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::SellBusiness => 'heroicon-o-building-storefront',
            self::BuyBusiness => 'heroicon-o-shopping-bag',
            self::SeekInvestment => 'heroicon-o-arrow-trending-up',
            self::OfferInvestment => 'heroicon-o-banknotes',
            self::Franchise => 'heroicon-o-squares-2x2',
            self::Partnership => 'heroicon-o-user-group',
            self::RealEstate => 'heroicon-o-home',
            self::Equipment => 'heroicon-o-wrench-screwdriver',
        };
    }

    public function isInvestmentRelated(): bool
    {
        return in_array($this, [self::SeekInvestment, self::OfferInvestment]);
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
