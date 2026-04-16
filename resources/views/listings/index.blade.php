@extends('layouts.app')

@section('title', 'Каталог объявлений')
@section('meta_description', 'Каталог объявлений о продаже и покупке бизнеса в Беларуси. Фильтрация по типу, категории, цене и городу.')

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

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Каталог объявлений</h1>
        <p class="text-gray-500 text-sm mt-1">
            @if(isset($listings))
                Найдено {{ $listings->total() }} {{ trans_choice('объявление|объявления|объявлений', $listings->total()) }}
            @endif
        </p>
    </div>

    <div class="flex flex-col lg:flex-row gap-6" x-data="{ filtersOpen: false }">

        <!-- Sidebar Filters -->
        <aside class="lg:w-72 flex-shrink-0">
            <!-- Mobile filter toggle -->
            <button @click="filtersOpen = !filtersOpen"
                    class="lg:hidden w-full flex items-center justify-between bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium text-gray-700 mb-4">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Фильтры
                </span>
                <svg class="w-4 h-4 transition-transform" :class="filtersOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div class="hidden lg:block" :class="filtersOpen ? '!block' : ''" x-show="filtersOpen || window.innerWidth >= 1024" style="display: block;">
                <form action="{{ route('listings.index') }}" method="GET"
                      class="bg-white rounded-xl border border-gray-200 p-5 space-y-5">

                    <h2 class="font-semibold text-gray-900 text-sm uppercase tracking-wider">Фильтры</h2>

                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Тип объявления</label>
                        <select name="type"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white">
                            <option value="">Все типы</option>
                            @foreach($typeLabels as $value => $label)
                                <option value="{{ $value }}" {{ request('type') === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Category -->
                    @if(isset($categories))
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Категория</label>
                        <select name="category"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white">
                            <option value="">Все категории</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->slug }}" {{ request('category') === $category->slug ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <!-- Location -->
                    @if(isset($locations))
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Город / Регион</label>
                        <select name="location"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white">
                            <option value="">Вся Беларусь</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc }}" {{ request('location') === $loc ? 'selected' : '' }}>
                                    {{ $loc }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <!-- Price Range -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Цена, BYN</label>
                        <div class="flex gap-2">
                            <input type="number"
                                   name="price_min"
                                   value="{{ request('price_min') }}"
                                   placeholder="от"
                                   min="0"
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300">
                            <input type="number"
                                   name="price_max"
                                   value="{{ request('price_max') }}"
                                   placeholder="до"
                                   min="0"
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300">
                        </div>
                    </div>

                    <!-- Currency -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Валюта</label>
                        <div class="flex gap-2 flex-wrap">
                            @foreach(['BYN', 'USD', 'EUR', 'RUB'] as $cur)
                                <label class="flex items-center gap-1.5 cursor-pointer">
                                    <input type="radio"
                                           name="currency"
                                           value="{{ $cur }}"
                                           {{ request('currency', 'BYN') === $cur ? 'checked' : '' }}
                                           class="text-blue-600 focus:ring-blue-300">
                                    <span class="text-sm text-gray-700">{{ $cur }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Search keyword -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ключевое слово</label>
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Поиск..."
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300">
                    </div>

                    <div class="flex gap-2 pt-1">
                        <button type="submit"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2.5 rounded-lg transition-colors">
                            Применить
                        </button>
                        <a href="{{ route('listings.index') }}"
                           class="px-4 py-2.5 border border-gray-200 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            Сбросить
                        </a>
                    </div>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 min-w-0">

            <!-- Toolbar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                <!-- Active filters pills -->
                <div class="flex flex-wrap gap-2">
                    @if(request('type'))
                        <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 text-xs px-3 py-1.5 rounded-full font-medium">
                            {{ $typeLabels[request('type')] ?? request('type') }}
                            <a href="{{ request()->fullUrlWithQuery(['type' => null]) }}" class="hover:text-blue-900">×</a>
                        </span>
                    @endif
                    @if(request('location'))
                        <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-700 text-xs px-3 py-1.5 rounded-full font-medium">
                            {{ request('location') }}
                            <a href="{{ request()->fullUrlWithQuery(['location' => null]) }}" class="hover:text-gray-900">×</a>
                        </span>
                    @endif
                    @if(request('search'))
                        <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-700 text-xs px-3 py-1.5 rounded-full font-medium">
                            «{{ request('search') }}»
                            <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="hover:text-gray-900">×</a>
                        </span>
                    @endif
                </div>

                <!-- Sort -->
                <div class="flex items-center gap-2 flex-shrink-0">
                    <label class="text-sm text-gray-500 whitespace-nowrap">Сортировка:</label>
                    <form method="GET" action="{{ route('listings.index') }}" id="sortForm">
                        @foreach(request()->except('sort') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <select name="sort"
                                onchange="document.getElementById('sortForm').submit()"
                                class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white text-gray-700">
                            <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Сначала новые</option>
                            <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Сначала старые</option>
                            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Цена: по возрастанию</option>
                            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Цена: по убыванию</option>
                            <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Популярные</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Listings Grid -->
            @if(isset($listings) && $listings->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                    @foreach($listings as $listing)

                    @php
                    $badgeClass = $typeBadgeColors[$listing->type->value] ?? 'bg-gray-100 text-gray-700';
                    $typeLabel  = $listing->type->label();
                    @endphp

                    <article class="bg-white rounded-xl overflow-hidden border border-gray-100 hover:shadow-md hover:border-gray-200 transition-all group">
                        <!-- Image -->
                        <a href="{{ route('listings.show', $listing->slug) }}" class="block relative overflow-hidden bg-gray-100" style="aspect-ratio: 16/10;">
                            @if($listing->main_image)
                                <img src="{{ asset('storage/' . $listing->main_image) }}"
                                     alt="{{ $listing->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="absolute top-2 left-2">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold {{ $badgeClass }}">
                                    {{ $typeLabel }}
                                </span>
                            </div>
                        </a>

                        <!-- Content -->
                        <div class="p-4">
                            <a href="{{ route('listings.show', $listing->slug) }}"
                               class="block font-semibold text-gray-900 hover:text-blue-600 transition-colors leading-snug mb-2 line-clamp-2 text-sm">
                                {{ $listing->title }}
                            </a>

                            <div class="mb-3">
                                @if($listing->price)
                                    <span class="text-lg font-bold text-gray-900">
                                        {{ number_format($listing->price, 0, '.', ' ') }}
                                    </span>
                                    <span class="text-gray-500 text-sm ml-1">{{ $listing->currency ?? 'BYN' }}</span>
                                @else
                                    <span class="text-gray-500 text-sm">По договорённости</span>
                                @endif
                            </div>

                            <div class="flex items-center justify-between text-xs text-gray-400 pt-3 border-t border-gray-50">
                                <div class="flex items-center gap-1 truncate">
                                    @if($listing->location)
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span class="truncate">{{ $listing->location?->name }}</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-3 flex-shrink-0">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        {{ number_format($listing->views_count ?? 0, 0, '.', ' ') }}
                                    </span>
                                    <span>{{ $listing->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </article>

                    @endforeach
                </div>

                <!-- Pagination -->
                @if($listings->hasPages())
                    <div class="mt-8">
                        {{ $listings->appends(request()->query())->links() }}
                    </div>
                @endif

            @else
                <div class="bg-white rounded-xl border border-gray-100 p-16 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Объявления не найдены</h3>
                    <p class="text-gray-500 text-sm mb-5">Попробуйте изменить параметры фильтрации</p>
                    <a href="{{ route('listings.index') }}"
                       class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                        Сбросить фильтры
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
