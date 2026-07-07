<?php

declare(strict_types=1);

namespace App\Rules;

use App\Services\ListingPricingService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidListingPrice implements ValidationRule
{
    /**
     * @param array<string, mixed> $pricingData
     */
    public function __construct(
        private readonly array $pricingData = [],
    ) {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $data = $this->pricingData ?: request()->only(['price', 'price_max', 'price_negotiable', 'price_on_request']);

        $price = $data['price'] ?? null;
        $priceMax = $data['price_max'] ?? null;
        $negotiable = filter_var($data['price_negotiable'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $onRequest = filter_var($data['price_on_request'] ?? false, FILTER_VALIDATE_BOOLEAN);

        $service = new ListingPricingService();

        if (!$service->isValid(
            $price !== null ? (float) $price : null,
            $priceMax !== null ? (float) $priceMax : null,
            $negotiable,
            $onRequest,
        )) {
            $fail('Укажите цену, диапазон, «торг» или «цена по запросу». Цена 1 BYN без явного подтверждения не допускается.');
        }
    }
}
