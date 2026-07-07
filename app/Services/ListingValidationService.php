<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\ListingFormat;
use App\Enums\ListingType;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

final class ListingValidationService
{
    /**
     * Validate business-specific fields depending on listing type.
     * For sell_business, required: location, format, rent conditions,
     * included in deal, employees count, sale reason, ready documents,
     * and at least one financial metric.
     */
    public function validateBusinessFields(array $data): array
    {
        $type = $data['type'] ?? null;

        if ($type !== ListingType::SellBusiness->value) {
            return $data;
        }

        $rules = [
            'listing_format' => 'required|in:' . implode(',', ListingFormat::values()),
            'location_id' => 'required|exists:locations,id',
            'rent_conditions' => 'required|string|max:500',
            'included_in_deal' => 'required|string|max:1000',
            'employees_count' => 'required|integer|min:0',
            'sale_reason' => 'required|string|max:255',
            'ready_documents' => 'required|string|max:1000',
        ];

        $validator = \Illuminate\Support\Facades\Validator::make($data, $rules);

        $validator->after(function ($validator) use ($data) {
            $financials = [
                $data['monthly_revenue'] ?? null,
                $data['monthly_profit'] ?? null,
                $data['investment_amount'] ?? null,
            ];

            if (! array_filter($financials, fn ($v) => $v !== null && $v !== '' && $v != 0)) {
                $validator->errors()->add(
                    'monthly_revenue',
                    'Укажите хотя бы один финансовый показатель: выручку, прибыль или сумму инвестиций.'
                );
            }
        });

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $data;
    }
}
