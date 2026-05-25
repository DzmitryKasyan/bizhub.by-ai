@extends('layouts.app')

@section('title', 'Бизнес хаб')
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
    'sell_business'    => 'bg-primary-100 text-primary-700',
    'buy_business'     => 'bg-purple-100 text-purple-700',
    'seek_investment'  => 'bg-emerald-100 text-emerald-700',
    'offer_investment' => 'bg-teal-100 text-teal-700',
    'franchise'        => 'bg-orange-100 text-orange-700',
    'partnership'      => 'bg-amber-100 text-amber-700',
    'real_estate'      => 'bg-indigo-100 text-indigo-700',
    'equipment'        => 'bg-slate-100 text-slate-700',
];
@endphp

<!-- Hero Section -->
<section class="relative overflow-hidden bg-slate-900 text-white">
    <!-- Background effects -->
    <div class="absolute inset-0 hero-grid opacity-50"></div>
    <div class="glow-orb w-96 h-96 bg-primary-500 top-0 left-1/4 animate-float"></div>
    <div class="glow-orb w-80 h-80 bg-accent-500 bottom-0 right-1/4 animate-float-delayed"></div>
    <div class="glow-orb w-64 h-64 bg-purple-500 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 animate-pulse-slow"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32">
        <div class="max-w-3xl mx-auto text-center relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 text-sm font-medium text-primary-200 mb-8">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Более 1000 актуальных предложений
            </div>

            <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold leading-tight mb-6 tracking-tight">
                Платформа для<br>
                <span class="bg-gradient-to-r from-primary-300 via-purple-300 to-accent-300 bg-clip-text text-transparent">покупки и продажи</span><br>
                бизнеса в Беларуси
            </h1>

            <p class="text-slate-300 text-lg md:text-xl mb-12 leading-relaxed max-w-2xl mx-auto font-light">
                Тысячи актуальных предложений: готовый бизнес, инвестиции, франшизы и партнёрство. Найдите идеальную сделку за минуты.
            </p>

            <!-- Search Form -->
            <form action="{{ route('listings.index') }}" method="GET"
                  class="bg-white/10 backdrop-blur-xl rounded-3xl p-2 shadow-2xl border border-white/20 search-glow transition-shadow duration-300 max-w-2xl mx-auto">
                <div class="flex flex-col sm:flex-row gap-2">
                    <div class="flex-1 relative">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text"
                               name="search"
                               value="{{ old('search') }}"
                               placeholder="Поиск по объявлениям..."
                               class="w-full pl-12 pr-4 py-4 bg-white/90 text-slate-900 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-400/50 text-sm font-medium placeholder:text-slate-400">
                    </div>
                    <div class="sm:w-52">
                        <select name="type"
                                class="w-full px-4 py-4 text-slate-700 rounded-2xl border-0 focus:outline-none focus:ring-2 focus:ring-primary-400/50 text-sm bg-white/90 font-medium cursor-pointer">
                            <option value="">Все типы</option>
                            @foreach($typeLabels as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit"
                            class="bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-bold px-8 py-4 rounded-2xl transition-all text-sm whitespace-nowrap shadow-lg shadow-primary-500/30 hover:shadow-primary-500/50 btn-shine">
                        Найти
                    </button>
                </div>
            </form>

            <!-- Quick links -->
            <div class="flex flex-wrap justify-center gap-3 mt-8">
                <a href="{{ route('sell-business') }}"
                   class="bg-white/5 hover:bg-white/15 text-white/90 text-sm px-5 py-2.5 rounded-full backdrop-blur-sm transition-all border border-white/10 hover:border-white/30 hover:-translate-y-0.5">
                    🔥 Продажа бизнеса
                </a>
                <a href="{{ route('investments') }}"
                   class="bg-white/5 hover:bg-white/15 text-white/90 text-sm px-5 py-2.5 rounded-full backdrop-blur-sm transition-all border border-white/10 hover:border-white/30 hover:-translate-y-0.5">
                    💰 Инвестиции
                </a>
                <a href="{{ route('franchises') }}"
                   class="bg-white/5 hover:bg-white/15 text-white/90 text-sm px-5 py-2.5 rounded-full backdrop-blur-sm transition-all border border-white/10 hover:border-white/30 hover:-translate-y-0.5">
                    🚀 Франшизы
                </a>
            </div>
        </div>
    </div>

    <!-- Bottom wave -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="#f8fafc"/>
        </svg>
    </div>
</section>

<!-- Exchange Rates Bar -->
@if(!empty($exchangeRates))
<section class="relative z-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-slate-100 shadow-sm px-6 py-3 flex flex-wrap items-center justify-center gap-x-8 gap-y-2">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Курсы валют</span>
            @foreach($exchangeRates as $code => $rate)
                <span class="inline-flex items-center gap-1.5 text-sm">
                    <span class="font-bold text-slate-700">{{ $code }}</span>
                    <span class="text-slate-500">{{ number_format($rate, $code === 'BTC' ? 0 : ($code === 'ETH' ? 0 : 4), '.', ' ') }}</span>
                    <span class="text-xs text-slate-400">Br</span>
                </span>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Stats Section -->
<section class="relative -mt-2 z-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8 md:p-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center group cursor-default">
                    <p class="text-4xl md:text-5xl font-black stat-counter mb-2 group-hover:scale-110 transition-transform duration-300 inline-block">{{ number_format($stats['total_listings'] ?? 0, 0, '.', ' ') }}</p>
                    <p class="text-slate-500 text-sm font-medium">Объявлений на сайте</p>
                </div>
                <div class="text-center group cursor-default">
                    <p class="text-4xl md:text-5xl font-black stat-counter mb-2 group-hover:scale-110 transition-transform duration-300 inline-block">{{ number_format($stats['sell_business'] ?? 0, 0, '.', ' ') }}</p>
                    <p class="text-slate-500 text-sm font-medium">Продаётся бизнесов</p>
                </div>
                <div class="text-center group cursor-default">
                    <p class="text-4xl md:text-5xl font-black stat-counter mb-2 group-hover:scale-110 transition-transform duration-300 inline-block">{{ number_format($stats['investors'] ?? 0, 0, '.', ' ') }}</p>
                    <p class="text-slate-500 text-sm font-medium">Активных инвесторов</p>
                </div>
                <div class="text-center group cursor-default">
                    <p class="text-4xl md:text-5xl font-black stat-counter mb-2 group-hover:scale-110 transition-transform duration-300 inline-block">{{ number_format($stats['franchises'] ?? 0, 0, '.', ' ') }}</p>
                    <p class="text-slate-500 text-sm font-medium">Франшиз</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Listings Section -->
@if(isset($featuredListings) && $featuredListings->count())
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-10">
            <div>
                <span class="inline-block px-4 py-1.5 rounded-full bg-primary-100 text-primary-700 text-xs font-bold uppercase tracking-wider mb-4">Рекомендации</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">Лучшие предложения</h2>
                <p class="text-slate-500 text-base mt-2">От проверенных продавцов</p>
            </div>
            <a href="{{ route('listings.index') }}"
               class="hidden sm:flex items-center gap-2 text-primary-600 hover:text-primary-700 font-semibold text-sm transition-colors group">
                Все объявления
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
<section class="py-20 bg-slate-50 relative overflow-hidden">
    <!-- Decorative elements -->
    <div class="absolute top-20 left-0 w-72 h-72 bg-primary-200/30 rounded-full blur-3xl"></div>
    <div class="absolute bottom-20 right-0 w-72 h-72 bg-accent-200/20 rounded-full blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-14">
            <span class="inline-block px-4 py-1.5 rounded-full bg-primary-100 text-primary-700 text-xs font-bold uppercase tracking-wider mb-4">Категории</span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4 tracking-tight">Популярные направления</h2>
            <p class="text-slate-500 text-base max-w-lg mx-auto">Найдите бизнес в вашей сфере деятельности среди десятков категорий</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($categories as $category)
                <a href="{{ route('listings.index', ['category' => $category->slug]) }}"
                   class="category-card bg-white rounded-2xl p-5 text-center hover:shadow-xl hover:shadow-primary-500/10 border border-slate-100 hover:border-primary-200 transition-all group card-3d">
                    <div class="w-14 h-14 bg-gradient-to-br from-slate-50 to-slate-100 group-hover:from-slate-100 group-hover:to-slate-200 rounded-2xl flex items-center justify-center mx-auto mb-4 transition-all category-icon shadow-sm">
                        <span class="text-2xl filter drop-shadow-sm">{{ $category->icon ?? '🏢' }}</span>
                    </div>
                    <p class="text-slate-800 font-semibold text-sm leading-tight mb-1">{{ $category->name }}</p>
                    @if(isset($category->listings_count))
                        <p class="text-slate-400 text-xs font-medium">{{ $category->listings_count }} объявл.</p>
                    @endif
                </a>
            @endforeach

                <!-- All Categories link -->
                <a href="{{ route('listings.index') }}"
                   class="category-card bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl p-5 text-center hover:shadow-xl hover:shadow-primary-500/25 border border-primary-400 hover:border-primary-300 transition-all group card-3d">
                    <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4 transition-all category-icon">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </div>
                    <p class="text-white font-semibold text-sm leading-tight mb-1">Все категории</p>
                    <p class="text-primary-100 text-xs font-medium">{{ number_format($stats['total_listings'] ?? 0, 0, '.', ' ') }} объявл.</p>
                </a>
        </div>
    </div>
</section>
@endif

<!-- Recent Listings Section -->
@if(isset($recentListings) && $recentListings->count())
<section class="py-20 bg-gradient-to-br from-slate-900 via-primary-900 to-slate-900 relative overflow-hidden">
    <div class="glow-orb w-80 h-80 bg-primary-600 top-0 right-0 opacity-15"></div>
    <div class="glow-orb w-64 h-64 bg-accent-600 bottom-0 left-0 opacity-10"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex items-center justify-between mb-10">
            <div>
                <span class="inline-block px-4 py-1.5 rounded-full bg-emerald-400/20 text-emerald-300 text-xs font-bold uppercase tracking-wider mb-4">Новое</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight">Свежие объявления</h2>
                <p class="text-slate-400 text-base mt-2">Только что добавлены на сайт</p>
            </div>
            <a href="{{ route('listings.index') }}"
               class="hidden sm:flex items-center gap-2 text-primary-400 hover:text-primary-300 font-semibold text-sm transition-colors group">
                Смотреть все
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

<!-- How it Works -->
<section class="py-20 bg-slate-50 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-1.5 rounded-full bg-accent-100 text-accent-700 text-xs font-bold uppercase tracking-wider mb-4">Как это работает</span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4 tracking-tight">Три простых шага к сделке</h2>
            <p class="text-slate-500 text-base max-w-lg mx-auto">Мы сделали процесс максимально простым и безопасным для всех сторон</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8 relative">
            <div class="hidden md:block absolute top-24 left-1/6 right-1/6 h-0.5 bg-gradient-to-r from-primary-200 via-primary-400 to-accent-400"></div>

            <div class="relative text-center group">
                <div class="w-20 h-20 bg-gradient-to-br from-primary-500 to-primary-600 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-xl shadow-primary-500/30 group-hover:scale-110 transition-transform duration-300 relative z-10">
                    <span class="text-3xl font-black text-white">1</span>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Разместите объявление</h3>
                <p class="text-slate-500 text-sm leading-relaxed max-w-xs mx-auto">Создайте подробное объявление о продаже бизнеса, поиске инвестиций или франшизе за 5 минут</p>
            </div>

            <div class="relative text-center group">
                <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-purple-600 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-xl shadow-purple-500/30 group-hover:scale-110 transition-transform duration-300 relative z-10">
                    <span class="text-3xl font-black text-white">2</span>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Получайте отклики</h3>
                <p class="text-slate-500 text-sm leading-relaxed max-w-xs mx-auto">Ваше объявление увидят тысячи потенциальных покупателей и инвесторов</p>
            </div>

            <div class="relative text-center group">
                <div class="w-20 h-20 bg-gradient-to-br from-accent-500 to-accent-600 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-xl shadow-accent-500/30 group-hover:scale-110 transition-transform duration-300 relative z-10">
                    <span class="text-3xl font-black text-white">3</span>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Заключите сделку</h3>
                <p class="text-slate-500 text-sm leading-relaxed max-w-xs mx-auto">Общайтесь напрямую, договаривайтесь о цене и условиях, совершайте безопасную сделку</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-24 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-primary-900 to-slate-900"></div>
    <div class="glow-orb w-96 h-96 bg-primary-600 top-0 right-0 opacity-30"></div>
    <div class="glow-orb w-80 h-80 bg-accent-600 bottom-0 left-0 opacity-20"></div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center z-10">
        <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-6 tracking-tight">Готовы продать бизнес?</h2>
        <p class="text-primary-200 text-lg md:text-xl mb-10 max-w-2xl mx-auto font-light leading-relaxed">Разместите объявление бесплатно и найдите покупателя среди тысяч заинтересованных пользователей. Среднее время продажи — 3 недели.</p>

        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('my-listings.create') }}"
               class="bg-white text-primary-700 font-bold px-10 py-4 rounded-2xl hover:bg-primary-50 transition-all shadow-2xl shadow-white/10 hover:shadow-white/20 hover:-translate-y-1 text-base btn-shine">
                Подать объявление
            </a>
            <a href="{{ route('article.show', 'about') }}"
               class="border-2 border-white/30 text-white font-semibold px-10 py-4 rounded-2xl hover:bg-white/10 transition-all hover:border-white/50 text-base">
                Узнать подробнее
            </a>
        </div>

        <div class="mt-12 flex items-center justify-center gap-8 text-white/40 text-sm">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                Безопасные сделки
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Экономия времени
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Бесплатно
            </div>
        </div>
    </div>
</section>

@endsection
