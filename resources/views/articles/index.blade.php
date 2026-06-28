@extends('layouts.app')

@section('title', 'Блог — полезные статьи о бизнесе в Беларуси')
@section('meta_description', 'Полезные статьи для предпринимателей Беларуси: как открыть бизнес, купить или продать готовый бизнес, привлечь инвестиции и управлять компанией.')
@section('canonical', route('articles.index'))
@section('og_type', 'website')
@section('og_url', route('articles.index'))
@section('og_title', 'Блог — полезные статьи о бизнесе в Беларуси')
@section('og_description', 'Полезные статьи для предпринимателей Беларуси: как открыть бизнес, купить или продать готовый бизнес, привлечь инвестиции и управлять компанией.')

@section('content')

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-slate-400 mb-8">
        <a href="{{ route('home') }}" class="hover:text-primary-600 transition-colors">Главная</a>
        <span>/</span>
        <span class="text-slate-600">Блог</span>
    </nav>

    <div class="mb-10">
        <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4 tracking-tight">Блог о бизнесе в Беларуси</h1>
        <p class="text-lg text-slate-600 max-w-3xl">Полезные материалы для предпринимателей: от регистрации ИП и выбора ниши до покупки, продажи и масштабирования бизнеса.</p>
    </div>

    @forelse($categories as $category)
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center gap-3">
                <span class="w-2 h-8 bg-gradient-to-b from-primary-500 to-accent-500 rounded-full"></span>
                {{ $category->name }}
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($category->articles as $article)
                    <a href="{{ route('article.show', $article->slug) }}"
                       class="group block bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:border-primary-200 transition-all card-3d">
                        <h3 class="text-lg font-semibold text-slate-900 mb-3 group-hover:text-primary-600 transition-colors line-clamp-2">
                            {{ $article->title }}
                        </h3>
                        <p class="text-sm text-slate-600 line-clamp-3 mb-4">
                            {{ Str::limit(strip_tags($article->content), 140) }}
                        </p>
                        <span class="inline-flex items-center text-sm font-medium text-primary-600 group-hover:text-primary-700 transition-colors">
                            Читать далее
                            <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    @empty
        <div class="text-center py-16 bg-white rounded-2xl border border-slate-100">
            <p class="text-slate-500">Пока нет опубликованных статей.</p>
        </div>
    @endforelse
</section>

@endsection
