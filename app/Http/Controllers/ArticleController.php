<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(): View
    {
        $categories = ArticleCategory::query()
            ->where('slug', '!=', 'o-servise')
            ->with(['articles' => fn ($query) => $query->published()->orderBy('title')])
            ->whereHas('articles', fn ($query) => $query->published())
            ->orderBy('name')
            ->get();

        return view('articles.index', compact('categories'));
    }

    public function show(Article $article): View
    {
        abort_unless($article->is_published, 404);

        return view('articles.show', compact('article'));
    }
}
