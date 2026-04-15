@extends('layouts.app')

@section('title', $post->title)
@section('meta_description', isset($post->excerpt) && $post->excerpt ? $post->excerpt : Str::limit(strip_tags($post->body ?? ''), 160))

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

        <!-- Main Article -->
        <div class="lg:col-span-3">

            <!-- Breadcrumb -->
            <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
                <a href="{{ route('blog.index') }}" class="hover:text-blue-600 transition-colors">Блог</a>
                <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                @if(isset($post->category) && $post->category)
                    <a href="{{ route('blog.index', ['category' => $post->category]) }}"
                       class="hover:text-blue-600 transition-colors">
                        {{ $post->category }}
                    </a>
                    <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                @endif
                <span class="text-gray-700 truncate max-w-xs">{{ $post->title }}</span>
            </nav>

            <!-- Article Card -->
            <article class="bg-white rounded-xl border border-gray-100 overflow-hidden">

                <!-- Header Image -->
                @if(isset($post->image) && $post->image)
                    <div class="aspect-video overflow-hidden">
                        <img src="{{ asset('storage/' . $post->image) }}"
                             alt="{{ $post->title }}"
                             class="w-full h-full object-cover">
                    </div>
                @else
                    <div class="h-48 bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 flex items-center justify-center">
                        <svg class="w-16 h-16 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                    </div>
                @endif

                <div class="p-6 sm:p-8">

                    <!-- Meta -->
                    <div class="flex flex-wrap items-center gap-3 mb-5">
                        @if(isset($post->category) && $post->category)
                            <a href="{{ route('blog.index', ['category' => $post->category]) }}"
                               class="text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-full transition-colors">
                                {{ $post->category }}
                            </a>
                        @endif
                        @if(isset($post->published_at) && $post->published_at)
                            <div class="flex items-center gap-1.5 text-xs text-gray-400">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ \Carbon\Carbon::parse($post->published_at)->format('d.m.Y') }}
                            </div>
                        @endif
                        @if(isset($post->author))
                            <div class="flex items-center gap-1.5 text-xs text-gray-400">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                {{ is_object($post->author) ? $post->author->name : $post->author }}
                            </div>
                        @endif
                    </div>

                    <!-- Title -->
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 leading-tight mb-6">
                        {{ $post->title }}
                    </h1>

                    <!-- Body Content -->
                    <div class="prose prose-gray max-w-none text-gray-700 leading-relaxed
                                [&>h2]:text-xl [&>h2]:font-bold [&>h2]:text-gray-900 [&>h2]:mt-8 [&>h2]:mb-4
                                [&>h3]:text-lg [&>h3]:font-semibold [&>h3]:text-gray-900 [&>h3]:mt-6 [&>h3]:mb-3
                                [&>p]:mb-4 [&>p]:text-gray-700
                                [&>ul]:mb-4 [&>ul]:list-disc [&>ul]:pl-5 [&>ul>li]:mb-1
                                [&>ol]:mb-4 [&>ol]:list-decimal [&>ol]:pl-5 [&>ol>li]:mb-1
                                [&>blockquote]:border-l-4 [&>blockquote]:border-blue-300 [&>blockquote]:pl-4 [&>blockquote]:italic [&>blockquote]:text-gray-600 [&>blockquote]:my-4
                                [&>a]:text-blue-600 [&>a]:underline [&>a]:hover:text-blue-700
                                [&>img]:rounded-xl [&>img]:my-4">
                        {!! $post->body !!}
                    </div>

                    <!-- Share -->
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-medium text-gray-500">Поделиться:</span>
                            <a href="https://t.me/share/url?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}"
                               target="_blank" rel="noopener noreferrer"
                               class="w-9 h-9 bg-blue-500 hover:bg-blue-600 text-white rounded-full flex items-center justify-center transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.248l-2.04 9.61c-.148.67-.546.834-1.107.518l-3.063-2.257-1.478 1.422c-.164.163-.3.3-.616.3l.22-3.11 5.66-5.11c.245-.22-.054-.34-.382-.12L7.17 14.05l-3.03-.947c-.66-.206-.673-.66.138-.977l11.843-4.567c.55-.2 1.031.13.84.69z"/>
                                </svg>
                            </a>
                            <a href="https://vk.com/share.php?url={{ urlencode(request()->url()) }}"
                               target="_blank" rel="noopener noreferrer"
                               class="w-9 h-9 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full flex items-center justify-center transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M21.547 7h-3.29a.743.743 0 00-.655.392s-1.312 2.416-1.734 3.23C14.734 12.813 14 12.126 14 11.11V7.603A1.104 1.104 0 0012.896 6.5h-2.474a1.982 1.982 0 00-1.75.813s1.255-.204 1.255 1.7c0 .386.022 1.931.053 3.043C9.24 12.504 7.816 10.5 7 7.617 6.795 6.932 6.159 6.5 5.453 6.5H2.233C1.85 6.5 1.5 6.81 1.5 7.262c0 .253.09.498.254.704 1.342 1.673 5.826 9.109 11.62 9.109 3.804 0 4.376-3.163 4.376-3.163l.876 1.82c.392.815 1.012.876 1.012.876h2.408c.773 0 1.246-.817.907-1.55l-1.88-3.976c0-.001 2.38-3.147 2.38-5.082C23.453 5.81 22.473 5 21.547 7z"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                </div>
            </article>

            <!-- Related Posts -->
            @if(isset($related) && $related->count())
                <div class="mt-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-5">Похожие статьи</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        @foreach($related as $relatedPost)
                            <a href="{{ route('blog.show', $relatedPost->slug) }}"
                               class="bg-white rounded-xl border border-gray-100 p-5 hover:shadow-md hover:border-gray-200 transition-all group">
                                <div class="flex items-center gap-2 mb-2">
                                    @if(isset($relatedPost->category) && $relatedPost->category)
                                        <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">
                                            {{ $relatedPost->category }}
                                        </span>
                                    @endif
                                    @if(isset($relatedPost->published_at) && $relatedPost->published_at)
                                        <span class="text-xs text-gray-400">
                                            {{ \Carbon\Carbon::parse($relatedPost->published_at)->format('d.m.Y') }}
                                        </span>
                                    @endif
                                </div>
                                <h3 class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors leading-snug line-clamp-2">
                                    {{ $relatedPost->title }}
                                </h3>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">

            <!-- Back to Blog -->
            <a href="{{ route('blog.index') }}"
               class="flex items-center gap-2 text-sm text-blue-600 hover:text-blue-700 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Все статьи
            </a>

            <!-- Category Block -->
            @if(isset($post->category) && $post->category)
                <div class="bg-white rounded-xl border border-gray-100 p-5">
                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Категория</p>
                    <a href="{{ route('blog.index', ['category' => $post->category]) }}"
                       class="inline-flex items-center gap-1.5 text-sm font-medium text-blue-600 hover:text-blue-700">
                        {{ $post->category }}
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            @endif

            <!-- CTA -->
            <div class="bg-blue-600 rounded-xl p-5 text-white">
                <h3 class="font-bold mb-2">Разместить объявление</h3>
                <p class="text-blue-100 text-sm mb-4 leading-relaxed">
                    Продайте бизнес или найдите партнёра на BizHub.by.
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
