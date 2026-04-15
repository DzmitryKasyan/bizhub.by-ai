<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    public function index(): View
    {
        $listings = auth()->user()
            ->favoritedListings()
            ->with(['category', 'location', 'images'])
            ->active()
            ->paginate(20);

        return view('dashboard.favorites.index', compact('listings'));
    }

    public function toggle(Listing $listing): JsonResponse
    {
        $user = auth()->user();
        $exists = $user->favorites()->where('listing_id', $listing->id)->exists();

        if ($exists) {
            $user->favorites()->where('listing_id', $listing->id)->delete();
            $listing->decrement('favorites_count');
            $favorited = false;
        } else {
            $user->favorites()->create(['listing_id' => $listing->id]);
            $listing->increment('favorites_count');
            $favorited = true;
        }

        return response()->json([
            'favorited' => $favorited,
            'count' => $listing->fresh()->favorites_count,
        ]);
    }
}
