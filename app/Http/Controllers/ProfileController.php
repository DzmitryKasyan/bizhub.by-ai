<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(User $user): View
    {
        $listings = $user->listings()
            ->active()
            ->with(['category', 'location', 'images'])
            ->latest()
            ->paginate(12);

        $reviews = $user->reviewsReceived()
            ->approved()
            ->with('reviewer')
            ->latest()
            ->limit(10)
            ->get();

        return view('profile.show', compact('user', 'listings', 'reviews'));
    }

    public function edit(): View
    {
        $user = auth()->user();
        $user->load('profile');

        return view('dashboard.profile', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'company_name' => 'nullable|string|max:255',
            'unp' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:30',
        ]);

        $user->update($validated);

        // Update or create profile
        $profileData = $request->validate([
            'profile.type' => 'nullable|string',
            'profile.experience_years' => 'nullable|integer|min:0|max:60',
            'profile.investment_range_min' => 'nullable|numeric|min:0',
            'profile.investment_range_max' => 'nullable|numeric|min:0',
            'profile.industries' => 'nullable|array',
            'profile.social_links' => 'nullable|array',
            'profile.social_links.telegram' => 'nullable|string|max:255',
            'profile.social_links.linkedin' => 'nullable|url|max:255',
            'profile.social_links.website' => 'nullable|url|max:255',
        ]);

        if (!empty($profileData['profile'])) {
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                $profileData['profile']
            );
        }

        return back()->with('success', 'Профиль обновлён.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        auth()->user()->update(['password' => $validated['password']]);

        return back()->with('success', 'Пароль изменён.');
    }
}
