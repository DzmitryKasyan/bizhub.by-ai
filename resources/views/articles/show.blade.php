@extends('layouts.app')

@section('title', $article->title)
@section('meta_description', $article->meta_description ?? Str::limit(strip_tags($article->content), 160))
@section('canonical', route('article.show', $article->slug))
@section('og_type', 'article')
@section('og_url', route('article.show', $article->slug))
@section('og_title', $article->title)
@section('og_description', $article->meta_description ?? Str::limit(strip_tags($article->content), 160))

@section('content')

<article class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-slate-400 mb-8">
        <a href="{{ route('home') }}" class="hover:text-primary-600 transition-colors">Главная</a>
        <span>/</span>
        <span class="text-slate-600">{{ $article->title }}</span>
    </nav>

    <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-6 tracking-tight">{{ $article->title }}</h1>

    <div class="article-content text-slate-700 leading-relaxed space-y-4
        [&_h1]:text-3xl [&_h1]:font-extrabold [&_h1]:text-slate-900 [&_h1]:mb-6 [&_h1]:mt-10
        [&_h2]:text-2xl [&_h2]:font-bold [&_h2]:text-slate-900 [&_h2]:mb-4 [&_h2]:mt-8
        [&_h3]:text-xl [&_h3]:font-semibold [&_h3]:text-slate-900 [&_h3]:mb-3 [&_h3]:mt-6
        [&_p]:text-slate-600 [&_p]:leading-relaxed [&_p]:mb-4
        [&_ul]:list-disc [&_ul]:list-inside [&_ul]:text-slate-600 [&_ul]:space-y-2 [&_ul]:mb-4
        [&_ol]:list-decimal [&_ol]:list-inside [&_ol]:text-slate-600 [&_ol]:space-y-2 [&_ol]:mb-4
        [&_li]:text-slate-600 [&_li]:leading-relaxed
        [&_a]:text-primary-600 [&_a]:underline [&_a]:decoration-primary-300 hover:[&_a]:decoration-primary-500
        [&_strong]:text-slate-900 [&_strong]:font-semibold
        [&_em]:italic [&_em]:text-slate-500
        [&_img]:rounded-xl [&_img]:shadow-lg [&_img]:my-6
        [&_blockquote]:border-l-4 [&_blockquote]:border-primary-300 [&_blockquote]:pl-4 [&_blockquote]:italic [&_blockquote]:text-slate-500 [&_blockquote]:my-6
        [&_hr]:border-slate-200 [&_hr]:my-8">
        {!! $article->content !!}
    </div>

    <div class="mt-12 pt-8 border-t border-slate-100">
        <a href="{{ route('home') }}"
           class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-medium text-sm transition-colors group">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            На главную
        </a>
    </div>
</article>

@endsection
