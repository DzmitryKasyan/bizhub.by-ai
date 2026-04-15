<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\ListingStatus;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $stats = [
            'total_listings' => $user->listings()->count(),
            'active_listings' => $user->listings()->where('status', ListingStatus::Active->value)->count(),
            'pending_listings' => $user->listings()->where('status', ListingStatus::Pending->value)->count(),
            'total_views' => $user->listings()->sum('views_count'),
            'total_favorites' => $user->listings()->sum('favorites_count'),
            'unread_messages' => 0, // computed via conversations
            'favorites_count' => $user->favorites()->count(),
        ];

        $recentListings = $user->listings()
            ->with(['category', 'location'])
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.index', compact('stats', 'recentListings'));
    }
}
