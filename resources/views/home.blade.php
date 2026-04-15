@extends('layouts.app')

@section('title', 'Главная')
@section('meta_description', 'BizHub.by — найдите готовый бизнес, инвестиции или франшизу в Беларуси. Тысячи актуальных предложений.')

@section('content')

@php
$typeLabels = [
    'sell_business'    => 'Продажа бизнеса',
    'buy_business'     => 'Покупка бизнеса',
    'seek_investment'  => 'Поиск инвестиций',
    'offer_investment' => 'Предложение инвестиций',
    'franchise'        => 'Франшиза',
    'partnership'      => 'Поиск партнёра',
    'real_estate'      => 'Недвижимость',
    'equipment'        => 'Оборудование',
];

$typeBadgeColors = [
    'sell_business'    => 'bg-blue-100 text-blue-700',
    'buy_business'     => 'bg-purple-100 text-purple-700',
    'seek_investment'  => 'bg-green-100 text-green-700',
    'offer_investment' => 'bg-emerald-100 text-emerald-700',
    'franchise'        => 'bg-orange-100 text-orange-700',
    'partnership'      => 'bg-yellow-100 text-yellow-700',
    'real_estate'      => 'bg-indigo-100 text-indigo-700',
    'equipment'        => 'bg-gray-100 text-gray-700',
];
@endphp

<!-- Hero Section -->
<section class="bg-gradient-to-br from-blue-700 via-blue-600 to-blue-800 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28">
        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-5">
                Платформа для покупки<br>
                и продажи бизнеса<br>
                <span class="text-blue-200">в Беларуси</span>
            </h1>
            <p class="text-blue-100 text-lg md:text-xl mb-10 leading-relaxed">
                Тысячи актуальных предложений: готовый бизнес, инвестиции, франшизы и партнёрство
            </p>

            <!-- Search Form -->
            <form action="{{ route('listings.index') }}" method="GET"
                  class="bg-white rounded-2xl p-3 flex flex-col sm:flex-row gap-3 shadow-2xl">
                <div class="flex-1 relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text"
                           name="search"
                           value="{{ old('search') }}"
                           placeholder="Поиск по объявлениям..."
                           class="w-full pl-10 pr-4 py-3 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-300 text-sm">
                </div>
                <div class="sm:w-52">
                    <select name="type"
                            class="w-full px-4 py-3 text-gray-700 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-300 text-sm bg-white">
                        <option value="">Все типы</option>
                        @foreach($typeLabels as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-xl transition-colors text-sm whitespace-nowrap">
                    Найти
                </button>
            </form>

            <!-- Quick links -->
            <div class="flex flex-wrap justify-center gap-3 mt-6">
                <a href="{{ route('sell-business') }}"
                   class="bg-white/10 hover:bg-white/20 text-white text-sm px-4 py-2 rounded-full backdrop-blur-sm transition-colors border border-white/20">
                    Продажа бизнеса
                </a>
                <a href="{{ route('investments') }}"
                   class="bg-white/10 hover:bg-white/20 text-white text-sm px-4 py-2 rounded-full backdrop-blur-sm transition-colors border border-white/20">
                    Инвестиции
                </a>
                <a href="{{ route('franchises') }}"
                   class="bg-white/10 hover:bg-white/20 text-white text-sm px-4 py-2 rounded-full backdrop-blur-sm transition-colors border border-white/20">
                    Франшизы
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="text-center">
                <p class="text-3xl font-bold text-blue-600">{{ number_format($stats['total_listings'] ?? 0, 0, '.', ' ') }}</p>
                <p class="text-gray-500 text-sm mt-1">Объявлений на сайте</p>
            </div>
            <div class="text-center">
                <p class="text-3xl font-bold text-blue-600">{{ number_format($stats['sell_business'] ?? 0, 0, '.', ' ') }}</p>
                <p class="text-gray-500 text-sm mt-1">Продаётся бизнесов</p>
            </div>
            <div class="text-center">
                <p class="text-3xl font-bold text-blue-600">{{ number_format($stats['investors'] ?? 0, 0, '.', ' ') }}</p>
                <p class="text-gray-500 text-sm mt-1">Активных инвесторов</p>
            </div>
            <div class="text-center">
                <p class="text-3xl font-bold text-blue-600">{{ number_format($stats['franchises'] ?? 0, 0, '.', ' ') }}</p>
                <p class="text-gray-500 text-sm mt-1">Франшиз</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Listings Section -->
@if(isset($featuredListings) && $featuredListings->count())
<section class="py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Рекомендуемые</h2>
                <p class="text-gray-500 text-sm mt-1">Лучшие предложения от проверенных продавцов</p>
            </div>
            <a href="{{ route('listings.index') }}"
               class="text-blue-600 hover:text-blue-700 font-medium text-sm flex items-center gap-1">
                Все объявления
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredListings as $listing)
                @include('partials.listing-card', ['listing' => $listing, 'typeLabels' => $typeLabels, 'typeBadgeColors' => $typeBadgeColors])
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Categories Section -->
@if(isset($categories) && $categories->count())
<section class="py-14 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-2xl font-bold text-gray-900">Популярные категории</h2>
            <p class="text-gray-500 text-sm mt-2">Найдите бизнес в вашей сфере</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($categories as $category)
                <a href="{{ route('listings.index', ['category' => $category->slug]) }}"
                   class="bg-white rounded-xl p-4 text-center hover:shadow-md hover:border-blue-200 border border-transparent transition-all group">
                    <div class="w-12 h-12 bg-blue-50 group-hover:bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-3 transition-colors">
                        <span class="text-2xl">{{ $category->icon ?? '🏢' }}</span>
                    </div>
                    <p class="text-gray-800 font-medium text-sm leading-tight">{{ $category->name }}</p>
                    @if(isset($category->listings_count))
                        <p class="text-gray-400 text-xs mt-1">{{ $category->listings_count }} объявл.</p>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Recent Listings Section -->
@if(isset($recentListings) && $recentListings->count())
<section class="py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Свежие объявления</h2>
                <p class="text-gray-500 text-sm mt-1">Только что добавлены на сайт</p>
            </div>
            <a href="{{ route('listings.index') }}"
               class="text-blue-600 hover:text-blue-700 font-medium text-sm flex items-center gap-1">
                Смотреть все
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($recentListings as $listing)
                @include('partials.listing-card', ['listing' => $listing, 'typeLabels' => $typeLabels, 'typeBadgeColors' => $typeBadgeColors])
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold mb-4">Продаёте бизнес?</h2>
        <p class="text-blue-100 text-lg mb-8">Разместите объявление бесплатно и найдите покупателя среди тысяч заинтересованных пользователей</p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('my-listings.create') }}"
               class="bg-white text-blue-700 font-semibold px-8 py-3 rounded-xl hover:bg-blue-50 transition-colors">
                Подать объявление
            </a>
            <a href="#"
               class="border border-white/40 text-white font-semibold px-8 py-3 rounded-xl hover:bg-white/10 transition-colors">
                Узнать подробнее
            </a>
        </div>
    </div>
</section>

@endsection
