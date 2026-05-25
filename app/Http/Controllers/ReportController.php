<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(Request $request, Listing $listing): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => 'required|string|in:spam,fraud,incorrect_info,other',
            'description' => 'nullable|string|max:1000',
        ]);

        Report::create([
            'reporter_id' => auth()->id(),
            'reportable_type' => Listing::class,
            'reportable_id' => $listing->id,
            'reason' => $validated['reason'],
            'description' => $validated['description'] ?? null,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Жалоба отправлена. Мы рассмотрим её в ближайшее время.');
    }
}
