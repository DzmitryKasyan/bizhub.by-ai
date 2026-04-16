<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Currency;
use App\Enums\ListingStatus;
use App\Enums\ListingType;
use App\Models\Category;
use App\Models\Listing;
use App\Models\ListingImage;
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
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
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
        $user = auth()->user();
        $canView = $listing->isActive()
            || ($user && $user->isAdmin())
            || ($user && $listing->isOwnedBy($user));
        abort_unless($canView, 404);

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
        $query = auth()->user()
            ->listings()
            ->with(['category', 'location'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $listings = $query->paginate(20);

        return view('dashboard.listings.index', compact('listings'));
    }

    public function create(): View
    {
        $categories = Category::active()->root()->ordered()->with('children')->get();
        $locations = Location::regions()->with('children')->orderBy('name')->get();
        $types = array_column(array_map(fn($t) => ['value' => $t->value, 'label' => $t->label()], ListingType::cases()), 'label', 'value');
        $currencies = array_column(array_map(fn($c) => ['value' => $c->value, 'label' => $c->label()], Currency::cases()), 'label', 'value');

        return view('dashboard.listings.create', compact('categories', 'locations', 'types', 'currencies'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type'              => 'required|in:' . implode(',', ListingType::values()),
            'title'             => 'required|string|max:255',
            'description'       => 'required|string|min:50',
            'price'             => 'nullable|numeric|min:0',
            'price_max'         => 'nullable|numeric|min:0',
            'currency'          => 'required|in:' . implode(',', Currency::values()),
            'price_negotiable'  => 'nullable|boolean',
            'category_id'       => 'required|exists:categories,id',
            'subcategory_id'    => 'nullable|exists:categories,id',
            'location_id'       => 'nullable|exists:locations,id',
            'monthly_revenue'   => 'nullable|numeric|min:0',
            'monthly_profit'    => 'nullable|numeric|min:0',
            'payback_months'    => 'nullable|integer|min:1|max:360',
            'investment_amount' => 'nullable|numeric|min:0',
            'year_founded'      => 'nullable|integer|min:1900|max:' . date('Y'),
            'employees_count'   => 'nullable|integer|min:0',
            'ownership_type'    => 'nullable|in:' . implode(',', \App\Enums\OwnershipType::values()),
            'sale_reason'       => 'nullable|string|max:255',
            'images.*'          => 'nullable|image|mimes:jpeg,png,webp|max:5120',
        ]);

        $validated['status'] = $request->input('action') === 'publish'
            ? ListingStatus::Pending
            : ListingStatus::Draft;

        $listing = auth()->user()->listings()->create($validated);

        $this->saveImages($request, $listing);

        return redirect()->route('my-listings.edit', $listing)
            ->with('success', $validated['status'] === ListingStatus::Pending
                ? 'Объявление отправлено на модерацию.'
                : 'Черновик сохранён.');
    }

    public function edit(Listing $listing): View
    {
        abort_unless($listing->isOwnedBy(auth()->user()) || auth()->user()->isModerator(), 403);

        $listing->load(['images', 'documents', 'category']);
        $categories = Category::active()->root()->ordered()->with('children')->get();
        $locations = Location::regions()->with('children')->orderBy('name')->get();
        $types = array_column(array_map(fn($t) => ['value' => $t->value, 'label' => $t->label()], ListingType::cases()), 'label', 'value');
        $currencies = array_column(array_map(fn($c) => ['value' => $c->value, 'label' => $c->label()], Currency::cases()), 'label', 'value');

        return view('dashboard.listings.edit', compact('listing', 'categories', 'locations', 'types', 'currencies'));
    }

    public function update(Request $request, Listing $listing): RedirectResponse
    {
        abort_unless($listing->isOwnedBy(auth()->user()) || auth()->user()->isModerator(), 403);

        $validated = $request->validate([
            'type'              => 'required|in:' . implode(',', ListingType::values()),
            'title'             => 'required|string|max:255',
            'description'       => 'required|string|min:50',
            'price'             => 'nullable|numeric|min:0',
            'price_max'         => 'nullable|numeric|min:0',
            'currency'          => 'required|in:' . implode(',', Currency::values()),
            'price_negotiable'  => 'nullable|boolean',
            'category_id'       => 'required|exists:categories,id',
            'subcategory_id'    => 'nullable|exists:categories,id',
            'location_id'       => 'nullable|exists:locations,id',
            'monthly_revenue'   => 'nullable|numeric|min:0',
            'monthly_profit'    => 'nullable|numeric|min:0',
            'payback_months'    => 'nullable|integer|min:1|max:360',
            'investment_amount' => 'nullable|numeric|min:0',
            'year_founded'      => 'nullable|integer|min:1900|max:' . date('Y'),
            'employees_count'   => 'nullable|integer|min:0',
            'ownership_type'    => 'nullable|in:' . implode(',', \App\Enums\OwnershipType::values()),
            'sale_reason'       => 'nullable|string|max:255',
            'images.*'          => 'nullable|image|mimes:jpeg,png,webp|max:5120',
        ]);

        $listing->update($validated);

        $this->saveImages($request, $listing);

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

    private function saveImages(Request $request, Listing $listing): void
    {
        if (!$request->hasFile('images')) {
            return;
        }

        $existingCount = $listing->images()->count();

        foreach ($request->file('images') as $i => $file) {
            $path = $file->store('listings', 'public');
            $listing->images()->create([
                'path'       => $path,
                'is_main'    => $existingCount === 0 && $i === 0,
                'sort_order' => $existingCount + $i,
            ]);
        }
    }
}
