<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\ListingStatus;
use App\Enums\ListingType;
use App\Models\Category;
use App\Models\Listing;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
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
            ->latest()
            ->limit(12)
            ->get();

        $categories = Category::query()
            ->active()
            ->root()
            ->ordered()
            ->withCount(['listings' => fn ($q) => $q->where('status', ListingStatus::Active->value)])
            ->get();

        $stats = [
            'total_listings' => Listing::active()->count(),
            'sell_business' => Listing::active()->ofType(ListingType::SellBusiness)->count(),
            'investors' => Listing::active()->ofType(ListingType::OfferInvestment)->count(),
            'franchises' => Listing::active()->ofType(ListingType::Franchise)->count(),
        ];

        return view('home', compact('featuredListings', 'recentListings', 'categories', 'stats'));
    }
}
