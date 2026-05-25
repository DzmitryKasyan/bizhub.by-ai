<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function show(Article $article): View
    {
        abort_unless($article->is_published, 404);

        return view('articles.show', compact('article'));
    }
}
