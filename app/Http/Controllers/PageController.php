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
}
