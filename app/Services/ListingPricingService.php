<?php

declare(strict_types=1);

namespace App\Services;

class ListingPricingService
{
    public function isValid(
        ?float $price,
        ?float $priceMax,
        bool $priceNegotiable,
        bool $priceOnRequest,
        bool $allowOne = false,
    ): bool {
        if ($priceOnRequest || $priceNegotiable) {
            return true;
        }

        if ($price !== null && $price > 0) {
            if (!$allowOne && $price === 1.0) {
                return false;
            }
            return true;
        }

        if ($priceMax !== null && $priceMax > 0) {
            return true;
        }

        return false;
    }
}
