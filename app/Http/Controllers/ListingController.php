<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Currency;
use App\Enums\ListingStatus;
use App\Enums\ListingType;
use App\Models\Category;
use App\Models\Listing;
use App\Models\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListingController extends Controller
{
    public function index(Request $request): View
    {
        $query = Listing::query()
            ->active()
            ->notExpired()
            ->with(['user', 'category', 'location', 'images'])
            ->orderByDesc('is_top')
            ->orderByDesc('is_promoted')
            ->orderByDesc('created_at');

        // Filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('location')) {
            $query->where('location_id', $request->location);
        }

        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        if ($request->filled('currency')) {
            $query->where('currency', $request->currency);
        }

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->q}%")
                    ->orWhere('description', 'like', "%{$request->q}%");
            });
        }

        $listings = $query->paginate(20)->withQueryString();

        $categories = Category::active()->root()->ordered()->get();
        $locations = Location::regions()->orderBy('name')->get();
        $types = ListingType::cases();
        $currencies = Currency::cases();

        return view('listings.index', compact('listings', 'categories', 'locations', 'types', 'currencies'));
    }

    public function show(Listing $listing): View
    {
        abort_unless($listing->isActive() || optional(auth()->user())->isAdmin(), 404);

        $listing->incrementViews();
        $listing->load(['user.profile', 'category', 'subcategory', 'location', 'images', 'documents']);

        $similar = Listing::query()
            ->active()
            ->where('id', '!=', $listing->id)
            ->where('category_id', $listing->category_id)
            ->with(['category', 'location', 'images'])
            ->limit(4)
            ->get();

        return view('listings.show', compact('listing', 'similar'));
    }

    public function myListings(Request $request): View
    {
        $listings = auth()->user()
            ->listings()
            ->with(['category', 'location'])
            ->latest()
            ->paginate(20);

        return view('dashboard.listings.index', compact('listings'));
    }

    public function create(): View
    {
        $categories = Category::active()->root()->ordered()->with('children')->get();
        $locations = Location::regions()->with('children')->orderBy('name')->get();
        $types = ListingType::cases();
        $currencies = Currency::cases();

        return view('dashboard.listings.create', compact('categories', 'locations', 'types', 'currencies'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:' . implode(',', ListingType::values()),
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'price' => 'nullable|numeric|min:0',
            'currency' => 'required|in:' . implode(',', Currency::values()),
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'nullable|exists:locations,id',
        ]);

        $listing = auth()->user()->listings()->create($validated);

        return redirect()->route('my-listings.edit', $listing)
            ->with('success', 'Объявление создано. Добавьте фотографии и отправьте на проверку.');
    }

    public function edit(Listing $listing): View
    {
        abort_unless($listing->isOwnedBy(auth()->user()) || auth()->user()->isModerator(), 403);

        $listing->load(['images', 'documents', 'category']);
        $categories = Category::active()->root()->ordered()->with('children')->get();
        $locations = Location::regions()->with('children')->orderBy('name')->get();
        $types = ListingType::cases();
        $currencies = Currency::cases();

        return view('dashboard.listings.edit', compact('listing', 'categories', 'locations', 'types', 'currencies'));
    }

    public function update(Request $request, Listing $listing): RedirectResponse
    {
        abort_unless($listing->isOwnedBy(auth()->user()) || auth()->user()->isModerator(), 403);

        $validated = $request->validate([
            'type' => 'required|in:' . implode(',', ListingType::values()),
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'price' => 'nullable|numeric|min:0',
            'currency' => 'required|in:' . implode(',', Currency::values()),
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'nullable|exists:locations,id',
        ]);

        $listing->update($validated);

        return redirect()->route('my-listings.edit', $listing)
            ->with('success', 'Объявление обновлено.');
    }

    public function destroy(Listing $listing): RedirectResponse
    {
        abort_unless($listing->isOwnedBy(auth()->user()) || auth()->user()->isModerator(), 403);

        $listing->delete();

        return redirect()->route('my-listings.index')
            ->with('success', 'Объявление удалено.');
    }

    public function publish(Listing $listing): RedirectResponse
    {
        abort_unless($listing->isOwnedBy(auth()->user()), 403);
        abort_unless($listing->status === ListingStatus::Draft, 422);

        $listing->update(['status' => ListingStatus::Pending]);

        return back()->with('success', 'Объявление отправлено на модерацию.');
    }

    public function archive(Listing $listing): RedirectResponse
    {
        abort_unless($listing->isOwnedBy(auth()->user()), 403);

        $listing->update(['status' => ListingStatus::Archived]);

        return back()->with('success', 'Объявление помещено в архив.');
    }

    public function trackView(Listing $listing): \Illuminate\Http\JsonResponse
    {
        $listing->incrementViews();
        return response()->json(['views' => $listing->views_count]);
    }

    public function sellBusiness(Request $request): View
    {
        return $this->index($request->merge(['type' => ListingType::SellBusiness->value]));
    }

    public function buyBusiness(Request $request): View
    {
        return $this->index($request->merge(['type' => ListingType::BuyBusiness->value]));
    }

    public function investments(Request $request): View
    {
        return $this->index($request->merge(['type' => ListingType::SeekInvestment->value]));
    }

    public function franchises(Request $request): View
    {
        return $this->index($request->merge(['type' => ListingType::Franchise->value]));
    }
}
