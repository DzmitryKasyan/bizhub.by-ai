@extends('layouts.dashboard')

@section('title', 'Личный кабинет')

@section('content')

<!-- Page Header -->
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">
        Добро пожаловать, {{ auth()->user()->name }}!
    </h1>
    <p class="text-gray-500 text-sm mt-1">{{ now()->format('d.m.Y') }} — Личный кабинет BizHub.by</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

    <!-- My Listings -->
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ $stats['my_listings'] ?? 0 }}</p>
        <p class="text-sm text-gray-500 mt-1">Моих объявлений</p>
        <a href="{{ route('my-listings.index') }}"
           class="mt-3 inline-block text-xs text-blue-600 hover:text-blue-700 font-medium">
            Управлять →
        </a>
    </div>

    <!-- Active Listings -->
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ $stats['active_listings'] ?? 0 }}</p>
        <p class="text-sm text-gray-500 mt-1">Активных</p>
    </div>

    <!-- Favorites -->
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ $stats['favorites'] ?? 0 }}</p>
        <p class="text-sm text-gray-500 mt-1">В избранном</p>
        <a href="{{ route('favorites.index') }}"
           class="mt-3 inline-block text-xs text-blue-600 hover:text-blue-700 font-medium">
            Смотреть →
        </a>
    </div>

    <!-- Messages -->
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-3 3v-3z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ $stats['messages'] ?? 0 }}</p>
        <p class="text-sm text-gray-500 mt-1">Сообщений</p>
        <a href="{{ route('messages.index') }}"
           class="mt-3 inline-block text-xs text-blue-600 hover:text-blue-700 font-medium">
            Перейти →
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Recent Listings -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-900">Последние объявления</h2>
                <a href="{{ route('my-listings.index') }}"
                   class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                    Все объявления
                </a>
            </div>

            @if(isset($recentListings) && $recentListings->count())
                <div class="divide-y divide-gray-50">
                    @foreach($recentListings as $listing)
                        <div class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50 transition-colors">
                            <!-- Image -->
                            <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                                @if($listing->main_image)
                                    <img src="{{ asset('storage/' . $listing->main_image) }}"
                                         alt="{{ $listing->title }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Info -->
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('listings.show', $listing->slug) }}"
                                   class="font-medium text-gray-900 text-sm hover:text-blue-600 transition-colors line-clamp-1">
                                    {{ $listing->title }}
                                </a>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    {{ $listing->created_at->format('d.m.Y') }}
                                    · {{ number_format($listing->views_count ?? 0, 0, '.', ' ') }} просм.
                                </p>
                            </div>

                            <!-- Status -->
                            @php
                            $statusConfig = [
                                'draft'    => 'bg-gray-100 text-gray-500',
                                'pending'  => 'bg-yellow-100 text-yellow-700',
                                'active'   => 'bg-green-100 text-green-700',
                                'sold'     => 'bg-blue-100 text-blue-600',
                                'archived' => 'bg-gray-100 text-gray-400',
                                'rejected' => 'bg-red-100 text-red-600',
                            ];
                            @endphp
                            <span class="flex-shrink-0 text-xs font-medium px-2.5 py-1 rounded-full
                                         {{ $statusConfig[$listing->status->value] ?? 'bg-gray-100 text-gray-500' }}">
                                {{ $listing->status->label() }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-12 text-center">
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-500">У вас пока нет объявлений</p>
                    <a href="{{ route('my-listings.create') }}"
                       class="mt-3 inline-flex items-center gap-1.5 text-sm text-blue-600 hover:text-blue-700 font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Подать объявление
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Profile & Quick Actions -->
    <div class="space-y-5">

        <!-- Profile Card -->
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                             alt="{{ auth()->user()->name }}"
                             class="w-full h-full object-cover rounded-full">
                    @else
                        <span class="text-blue-700 font-bold text-lg">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </span>
                    @endif
                </div>
                <div>
                    <p class="font-semibold text-gray-900 text-sm">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-400">{{ auth()->user()->email }}</p>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}"
               class="w-full flex items-center justify-center border border-gray-200 hover:border-gray-300 text-gray-700 text-sm font-medium py-2 rounded-lg hover:bg-gray-50 transition-colors">
                Редактировать профиль
            </a>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-900 text-sm mb-4">Быстрые действия</h3>
            <div class="space-y-2">
                <a href="{{ route('my-listings.create') }}"
                   class="flex items-center gap-3 p-3 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition-colors">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Подать объявление
                </a>
                <a href="{{ route('listings.index') }}"
                   class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-gray-300 text-gray-700 text-sm font-medium transition-colors hover:bg-gray-50">
                    <svg class="w-4 h-4 flex-shrink-0 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Смотреть каталог
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
