<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showRegistrationForm(): View
    {
        $roles = [
            UserRole::User,
            UserRole::Entrepreneur,
            UserRole::Investor,
            UserRole::Broker,
        ];

        return view('auth.register', compact('roles'));
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => 'required|in:' . implode(',', [
                UserRole::User->value,
                UserRole::Entrepreneur->value,
                UserRole::Investor->value,
                UserRole::Broker->value,
            ]),
            'phone' => 'nullable|string|max:30',
            'agree_terms' => 'required|accepted',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Добро пожаловать в BizHub.by! Пожалуйста, подтвердите ваш email.');
    }
}
