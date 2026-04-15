@extends('layouts.app')

@section('title', 'Блог')
@section('meta_description', 'Статьи и новости о покупке, продаже бизнеса, инвестициях и франшизах в Беларуси.')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Page Header -->
    <div class="mb-10">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Блог</h1>
        <p class="text-gray-500 text-lg">Полезные статьи о бизнесе, инвестициях и рынке Беларуси</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

        <!-- Main Content -->
        <div class="lg:col-span-3">

            @if($posts->count())

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                    @foreach($posts as $post)
                        <article class="bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-md hover:border-gray-200 transition-all group">

                            <!-- Thumbnail (placeholder gradient if no image) -->
                            @if(isset($post->image) && $post->image)
                                <a href="{{ route('blog.show', $post->slug) }}" class="block overflow-hidden aspect-video">
                                    <img src="{{ asset('storage/' . $post->image) }}"
                                         alt="{{ $post->title }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                </a>
                            @else
                                <a href="{{ route('blog.show', $post->slug) }}"
                                   class="block aspect-video bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                    </svg>
                                </a>
                            @endif

                            <div class="p-5">
                                <!-- Category & Date -->
                                <div class="flex items-center gap-3 mb-3">
                                    @if(isset($post->category) && $post->category)
                                        <a href="{{ route('blog.index', ['category' => $post->category]) }}"
                                           class="text-xs font-semibold text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 px-2.5 py-1 rounded-full transition-colors">
                                            {{ $post->category }}
                                        </a>
                                    @endif
                                    @if(isset($post->published_at) && $post->published_at)
                                        <span class="text-xs text-gray-400">
                                            {{ \Carbon\Carbon::parse($post->published_at)->format('d.m.Y') }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Title -->
                                <a href="{{ route('blog.show', $post->slug) }}"
                                   class="block font-bold text-gray-900 hover:text-blue-600 transition-colors leading-snug mb-2 line-clamp-2 group-hover:text-blue-600">
                                    {{ $post->title }}
                                </a>

                                <!-- Excerpt -->
                                @php
                                    $excerpt = isset($post->excerpt) && $post->excerpt
                                        ? $post->excerpt
                                        : Str::limit(strip_tags($post->body ?? ''), 150);
                                @endphp
                                <p class="text-sm text-gray-500 leading-relaxed line-clamp-3 mb-4">
                                    {{ $excerpt }}
                                </p>

                                <!-- Author & Read More -->
                                <div class="flex items-center justify-between">
                                    @if(isset($post->author) && $post->author)
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center">
                                                <span class="text-gray-600 font-semibold" style="font-size: 10px;">
                                                    {{ substr($post->author->name ?? $post->author, 0, 1) }}
                                                </span>
                                            </div>
                                            <span class="text-xs text-gray-500">
                                                {{ is_object($post->author) ? $post->author->name : $post->author }}
                                            </span>
                                        </div>
                                    @else
                                        <div></div>
                                    @endif

                                    <a href="{{ route('blog.show', $post->slug) }}"
                                       class="text-sm text-blue-600 hover:text-blue-700 font-medium flex items-center gap-1">
                                        Читать
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($posts->hasPages())
                    <div>
                        {{ $posts->links() }}
                    </div>
                @endif

            @else

                <div class="bg-white rounded-xl border border-gray-100 py-20 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Статей пока нет</h3>
                    <p class="text-gray-500 text-sm">Скоро здесь появятся полезные материалы</p>
                </div>

            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">

            <!-- Categories -->
            @if($categories && $categories->count())
                <div class="bg-white rounded-xl border border-gray-100 p-5">
                    <h3 class="font-semibold text-gray-900 mb-4 text-sm uppercase tracking-wide">Категории</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('blog.index') }}"
                               class="flex items-center justify-between text-sm py-1.5 {{ !request('category') ? 'text-blue-600 font-medium' : 'text-gray-600 hover:text-blue-600' }} transition-colors">
                                <span>Все статьи</span>
                                <span class="text-xs text-gray-400">{{ $posts->total() }}</span>
                            </a>
                        </li>
                        @foreach($categories as $category)
                            <li>
                                <a href="{{ route('blog.index', ['category' => $category]) }}"
                                   class="flex items-center text-sm py-1.5 {{ request('category') === $category ? 'text-blue-600 font-medium' : 'text-gray-600 hover:text-blue-600' }} transition-colors">
                                    <svg class="w-3 h-3 mr-2 text-gray-300" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3"/>
                                    </svg>
                                    {{ $category }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- CTA Block -->
            <div class="bg-blue-600 rounded-xl p-5 text-white">
                <h3 class="font-bold mb-2">Продаёте бизнес?</h3>
                <p class="text-blue-100 text-sm mb-4 leading-relaxed">
                    Разместите объявление и найдите покупателя среди тысяч заинтересованных пользователей.
                </p>
                <a href="{{ route('my-listings.create') }}"
                   class="block text-center bg-white text-blue-700 hover:bg-blue-50 font-semibold text-sm px-4 py-2.5 rounded-lg transition-colors">
                    Подать объявление
                </a>
            </div>

        </div>
    </div>
</div>

@endsection
