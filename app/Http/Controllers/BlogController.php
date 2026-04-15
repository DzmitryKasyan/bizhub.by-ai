<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $posts = BlogPost::published()
            ->with('author')
            ->orderByDesc('published_at')
            ->paginate(12);

        $categories = BlogPost::published()
            ->whereNotNull('category')
            ->select('category')
            ->distinct()
            ->pluck('category');

        return view('blog.index', compact('posts', 'categories'));
    }

    public function show(BlogPost $post): View
    {
        abort_unless($post->is_published || optional(auth()->user())->isModerator(), 404);

        $post->incrementViews();
        $post->load('author');

        $related = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->where('category', $post->category)
            ->limit(3)
            ->get();

        return view('blog.show', compact('post', 'related'));
    }
}
