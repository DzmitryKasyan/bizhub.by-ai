<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\ListingType;
use App\Models\Category;
use App\Models\Listing;
use App\Models\ListingTypeModel;
use App\Services\ExchangeRateService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(ExchangeRateService $rates): View
    {
        $exchangeRates = $rates->getRatesFlat();

        $featuredListings = Listing::query()
            ->active()
            ->with(['category', 'location', 'images'])
            ->where('is_promoted', true)
            ->orWhere('is_top', true)
            ->latest()
            ->limit(6)
            ->get();

        $recentListings = Listing::query()
            ->active()
            ->with(['category', 'location', 'images'])
            ->whereHas('images')
            ->latest()
            ->limit(12)
            ->get();

        $categories = Category::query()
            ->active()
            ->root()
            ->ordered()
            ->get();

        $typeCounts = ListingTypeModel::query()
            ->whereIn('code', [
                ListingType::SellBusiness->value,
                ListingType::OfferInvestment->value,
                ListingType::Franchise->value,
            ])
            ->pluck('listings_count', 'code');

        $stats = [
            'total_listings' => Listing::active()->count(),
            'sell_business' => $typeCounts->get(ListingType::SellBusiness->value, 0),
            'investors' => $typeCounts->get(ListingType::OfferInvestment->value, 0),
            'franchises' => $typeCounts->get(ListingType::Franchise->value, 0),
        ];

        return view('home', compact('featuredListings', 'recentListings', 'categories', 'stats', 'exchangeRates'));
    }

    public function rates(ExchangeRateService $rates): View
    {
        $data = $rates->getRates();

        return view('rates', [
            'rates' => $data['rates'],
            'updatedAt' => $data['updated_at'],
            'labels' => $rates->labels(),
        ]);
    }
}
