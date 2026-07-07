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
use App\Rules\ValidImageContent;
use App\Rules\ValidListingPrice;
use App\Services\ListingValidationService;
use App\Services\ProhibitedContentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ListingController extends Controller
{
    public function index(Request $request): View
    {
        $query = Listing::query()
            ->active()
            ->notExpired()
            ->with(['user', 'category', 'location', 'images']);

        // Sorting
        $sort = $request->input('sort', 'newest');
        match ($sort) {
            'oldest' => $query->orderBy('created_at'),
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'popular' => $query->orderByDesc('views_count'),
            default => $query->orderByDesc('is_top')->orderByDesc('is_promoted')->orderByDesc('created_at'),
        };

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

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        $listings = $query->paginate(20)->withQueryString();

        $categories = Category::active()->root()->ordered()->get();
        $locations = Location::regions()->orderBy('name')->get();
        $types = ListingType::cases();
        $currencies = Currency::cases();

        return view('listings.index', compact('listings', 'categories', 'locations', 'types', 'currencies'));
    }

    public function map(Request $request): View
    {
        $query = Listing::query()
            ->active()
            ->notExpired()
            ->has('coordinate')
            ->with(['user', 'category', 'location', 'images', 'coordinate']);

        // Apply same filters as index
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

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        $listings = $query->limit(500)->get();

        $categories = Category::active()->root()->ordered()->get();
        $locations = Location::regions()->orderBy('name')->get();
        $types = ListingType::cases();
        $currencies = Currency::cases();

        $mapPoints = $listings->map(fn (Listing $listing) => [
            'id' => $listing->id,
            'slug' => $listing->slug,
            'title' => $listing->title,
            'price' => $listing->formatted_price,
            'latitude' => (float) $listing->coordinate->latitude,
            'longitude' => (float) $listing->coordinate->longitude,
            'address' => $listing->coordinate->address,
            'url' => route('listings.show', $listing->slug),
            'image' => $listing->main_image,
        ]);

        return view('listings.map', compact('mapPoints', 'categories', 'locations', 'types', 'currencies'));
    }

    public function show(Listing $listing): View
    {
        $user = auth()->user();
        $canView = $listing->isActive()
            || ($user && $user->isAdmin())
            || ($user && $listing->isOwnedBy($user));
        abort_unless($canView, 404);

        $listing->incrementViews();
        $listing->load(['user.profile', 'category', 'subcategory', 'location', 'images', 'documents', 'contacts', 'coordinate', 'verifications']);

        $dealService = new \App\Services\ListingDealService();
        $canAccessDataRoom = $dealService->canAccessDataRoom($listing, $user);
        $hasSignedNda = $user ? $dealService->hasSignedNda($listing, $user) : false;
        $dealProgress = ($user && ($listing->isOwnedBy($user) || $hasSignedNda || $user->isModerator()))
            ? $dealService->dealProgress($listing, $user)
            : [];

        $similar = Listing::query()
            ->active()
            ->where('id', '!=', $listing->id)
            ->where('category_id', $listing->category_id)
            ->with(['category', 'location', 'images'])
            ->limit(4)
            ->get();

        return view('listings.show', compact(
            'listing',
            'similar',
            'canAccessDataRoom',
            'hasSignedNda',
            'dealProgress'
        ));
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
        $request->merge(['price_strategy' => $request->input('price_strategy', 'auto')]);

        $validated = $request->validate([
            'type'              => 'required|in:' . implode(',', ListingType::values()),
            'title'             => 'required|string|max:255',
            'description'       => 'required|string|min:50',
            'price'             => ['nullable', 'numeric', 'min:0'],
            'price_max'         => ['nullable', 'numeric', 'min:0'],
            'currency'          => 'required|in:' . implode(',', Currency::values()),
            'price_negotiable'  => ['nullable', 'boolean'],
            'price_on_request'  => ['nullable', 'boolean'],
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
            'listing_format'    => 'nullable|in:' . implode(',', \App\Enums\ListingFormat::values()),
            'rent_conditions'   => 'nullable|string|max:500',
            'included_in_deal'  => 'nullable|string|max:1000',
            'ready_documents'   => 'nullable|string|max:1000',
            'deal_support_requested' => 'nullable|boolean',
            'images.*'          => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120', new ValidImageContent],
            'contacts'          => 'nullable|array',
            'contacts.phone'    => 'nullable|string|max:255',
            'contacts.telegram' => 'nullable|string|max:255',
            'coordinate'        => 'nullable|array',
            'coordinate.latitude'  => 'nullable|numeric',
            'coordinate.longitude' => 'nullable|numeric',
            'coordinate.address'   => 'nullable|string|max:255',
            'price_strategy'    => ['required', new ValidListingPrice],
        ]);

        $this->ensureNoProhibitedContent($validated);
        (new ListingValidationService())->validateBusinessFields($validated);

        $validated['status'] = $request->input('action') === 'publish'
            ? ListingStatus::Pending
            : ListingStatus::Draft;

        $listingData = $validated;
        unset($listingData['contacts'], $listingData['coordinate']);

        $listing = auth()->user()->listings()->create($listingData);

        $listing->saveContactsAndCoordinate($validated);
        $this->saveImages($request, $listing);

        return redirect()->route('my-listings.edit', $listing)
            ->with('success', $validated['status'] === ListingStatus::Pending
                ? 'Объявление отправлено на модерацию.'
                : 'Черновик сохранён.');
    }

    public function edit(Listing $listing): View
    {
        abort_unless($listing->isOwnedBy(auth()->user()) || auth()->user()->isModerator(), 403);

        $listing->load(['images', 'documents', 'category', 'contacts', 'coordinate']);
        $categories = Category::active()->root()->ordered()->with('children')->get();
        $locations = Location::regions()->with('children')->orderBy('name')->get();
        $types = array_column(array_map(fn($t) => ['value' => $t->value, 'label' => $t->label()], ListingType::cases()), 'label', 'value');
        $currencies = array_column(array_map(fn($c) => ['value' => $c->value, 'label' => $c->label()], Currency::cases()), 'label', 'value');

        return view('dashboard.listings.edit', compact('listing', 'categories', 'locations', 'types', 'currencies'));
    }

    public function update(Request $request, Listing $listing): RedirectResponse
    {
        abort_unless($listing->isOwnedBy(auth()->user()) || auth()->user()->isModerator(), 403);

        $request->merge(['price_strategy' => $request->input('price_strategy', 'auto')]);

        $validated = $request->validate([
            'type'              => 'required|in:' . implode(',', ListingType::values()),
            'title'             => 'required|string|max:255',
            'description'       => 'required|string|min:50',
            'price'             => ['nullable', 'numeric', 'min:0'],
            'price_max'         => ['nullable', 'numeric', 'min:0'],
            'currency'          => 'required|in:' . implode(',', Currency::values()),
            'price_negotiable'  => ['nullable', 'boolean'],
            'price_on_request'  => ['nullable', 'boolean'],
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
            'listing_format'    => 'nullable|in:' . implode(',', \App\Enums\ListingFormat::values()),
            'rent_conditions'   => 'nullable|string|max:500',
            'included_in_deal'  => 'nullable|string|max:1000',
            'ready_documents'   => 'nullable|string|max:1000',
            'deal_support_requested' => 'nullable|boolean',
            'images.*'          => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120', new ValidImageContent],
            'contacts'          => 'nullable|array',
            'contacts.phone'    => 'nullable|string|max:255',
            'contacts.telegram' => 'nullable|string|max:255',
            'coordinate'        => 'nullable|array',
            'coordinate.latitude'  => 'nullable|numeric',
            'coordinate.longitude' => 'nullable|numeric',
            'coordinate.address'   => 'nullable|string|max:255',
            'price_strategy'    => ['required', new ValidListingPrice],
        ]);

        $this->ensureNoProhibitedContent($validated);
        (new ListingValidationService())->validateBusinessFields($validated);

        $listingData = $validated;
        unset($listingData['contacts'], $listingData['coordinate']);

        $listing->update($listingData);

        $listing->saveContactsAndCoordinate($validated);
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

    public function trustManagement(Request $request): View
    {
        return $this->index($request->merge(['type' => ListingType::TrustManagement->value]));
    }

    private function ensureNoProhibitedContent(array $validated): void
    {
        $prohibited = new ProhibitedContentService();
        $errors = [];

        foreach (['title', 'description'] as $field) {
            if ($prohibited->contains($validated[$field] ?? '')) {
                $errors[$field] = 'Объявление содержит запрещённую тематику: ' . implode(', ', $prohibited->detect($validated[$field]));
            }
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }
    }

    private function saveImages(Request $request, Listing $listing): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        $existingCount = $listing->images()->count();
        $manager = new ImageManager(new Driver());

        foreach ($request->file('images') as $i => $file) {
            $mime = $file->getMimeType();
            if (! in_array($mime, ['image/jpeg', 'image/png', 'image/gif', 'image/webp'], true)) {
                continue;
            }

            $image = $manager->read($file->getPathname());

            $image->resize(1920, 1920, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $filename = Str::uuid() . '.webp';
            $path = 'listings/' . $filename;
            Storage::disk('public')->put($path, $image->toWebp(80));

            $listing->images()->create([
                'path'       => $path,
                'is_main'    => $existingCount === 0 && $i === 0,
                'sort_order' => $existingCount + $i,
            ]);
        }
    }
}
