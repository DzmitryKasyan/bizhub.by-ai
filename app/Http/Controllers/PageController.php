<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\View\View;

class PageController extends Controller
{
    public function show(Page $page): View
    {
        abort_unless($page->is_published || optional(auth()->user())->isModerator(), 404);

        return view('pages.show', compact('page'));
    }

    public function about(): View
    {
        $page = Page::where('slug', 'about')->where('is_published', true)->firstOrFail();
        return view('pages.show', compact('page'));
    }

    public function contacts(): View
    {
        return view('pages.contacts');
    }

    public function terms(): View
    {
        $page = Page::where('slug', 'terms')->where('is_published', true)->firstOrFail();
        return view('pages.show', compact('page'));
    }

    public function privacy(): View
    {
        $page = Page::where('slug', 'privacy')->where('is_published', true)->firstOrFail();
        return view('pages.show', compact('page'));
    }

    public function feedback(\Illuminate\Http\Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Store feedback in DB
        \Illuminate\Support\Facades\DB::table('feedback')->insert([
            'name'       => $validated['name'],
            'email'      => $validated['email'],
            'subject'    => $validated['subject'] ?? '',
            'message'    => $validated['message'],
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return back()->with('success', 'Спасибо! Ваше сообщение отправлено. Мы ответим в ближайшее время.');
    }
}
