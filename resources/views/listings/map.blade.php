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
    'trust_management' => 'Доверительное управление',
];
@endphp

@extends('layouts.app')

@section('title', 'Объявления на карте')
@section('meta_description', 'Карта объявлений о продаже и покупке бизнеса в Беларуси. Ищите готовый бизнес, инвестиции и франшизы по географии.')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Page Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Объявления на карте</h1>
            <p class="text-slate-500 text-sm mt-1">
                Найдено {{ $mapPoints->count() }} {{ trans_choice('объявление|объявления|объявлений', $mapPoints->count()) }} с геолокацией
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('listings.index') }}" class="inline-flex items-center justify-center gap-2 border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                Список
            </a>
            <a href="{{ route('my-listings.create') }}"
               class="inline-flex items-center justify-center gap-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Добавить объявление
            </a>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-6" x-data="{ filtersOpen: false }">

        <!-- Sidebar Filters -->
        <aside class="lg:w-72 flex-shrink-0">
            <button @click="filtersOpen = !filtersOpen"
                    class="lg:hidden w-full flex items-center justify-between bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 mb-4">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Фильтры
                </span>
                <svg class="w-4 h-4 transition-transform" :class="filtersOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div class="hidden lg:block" :class="filtersOpen ? '!block' : ''" x-show="filtersOpen || window.innerWidth >= 1024" style="display: block;">
                <form action="{{ route('listings.map') }}" method="GET"
                      class="bg-white rounded-xl border border-slate-200 p-5 space-y-5">

                    <h2 class="font-semibold text-slate-900 text-sm uppercase tracking-wider">Фильтры</h2>

                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Тип объявления</label>
                        <select name="type"
                                class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white">
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
                        <label class="block text-sm font-medium text-slate-700 mb-2">Категория</label>
                        <select name="category"
                                class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white">
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
                        <label class="block text-sm font-medium text-slate-700 mb-2">Город / Регион</label>
                        <select name="location"
                                class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white">
                            <option value="">Вся Беларусь</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}" {{ request('location') == $loc->id ? 'selected' : '' }}>
                                    {{ $loc->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <!-- Price Range -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Цена, Br</label>
                        <div class="flex gap-2">
                            <input type="number"
                                   name="price_min"
                                   value="{{ request('price_min') }}"
                                   placeholder="от"
                                   min="0"
                                   class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-300">
                            <input type="number"
                                   name="price_max"
                                   value="{{ request('price_max') }}"
                                   placeholder="до"
                                   min="0"
                                   class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-300">
                        </div>
                    </div>

                    <!-- Currency -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Валюта</label>
                        <div class="flex gap-2 flex-wrap">
                            @foreach(['BYN', 'USD', 'EUR', 'RUB'] as $cur)
                                <label class="flex items-center gap-1.5 cursor-pointer">
                                    <input type="radio"
                                           name="currency"
                                           value="{{ $cur }}"
                                           {{ request('currency', 'BYN') === $cur ? 'checked' : '' }}
                                           class="text-primary-600 focus:ring-primary-300">
                                    <span class="text-sm text-slate-700">{{ $cur }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Search keyword -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Ключевое слово</label>
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Поиск..."
                               class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-300">
                    </div>

                    <div class="flex gap-2 pt-1">
                        <button type="submit"
                                class="flex-1 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium py-2.5 rounded-lg transition-colors">
                            Применить
                        </button>
                        <a href="{{ route('listings.map') }}"
                           class="px-4 py-2.5 border border-slate-200 text-slate-600 text-sm font-medium rounded-lg hover:bg-slate-50 transition-colors">
                            Сбросить
                        </a>
                    </div>
                </form>
            </div>
        </aside>

        <!-- Map -->
        <div class="flex-1 min-w-0">
            <div id="listings-map" class="w-full h-[600px] rounded-xl border border-slate-200 bg-slate-100"></div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&apikey={{ config('services.yandex.maps_api_key', '') }}" type="text/javascript"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const points = @json($mapPoints);

        ymaps.ready(function () {
            const map = new ymaps.Map('listings-map', {
                center: [53.9045, 27.5615],
                zoom: 7,
                controls: ['zoomControl', 'searchControl'],
            });

            if (points.length === 0) {
                map.setCenter([53.9045, 27.5615], 7);
                return;
            }

            const bounds = new ymaps.GeoObjectCollection();

            points.forEach(function (point) {
                const placemark = new ymaps.Placemark(
                    [point.latitude, point.longitude],
                    {
                        balloonContent: `
                            <div class="p-2 max-w-xs">
                                ${point.image ? `<img src="${point.image}" alt="" class="w-full h-32 object-cover rounded mb-2">` : ''}
                                <a href="${point.url}" class="font-semibold text-primary-600 hover:underline block mb-1">${point.title}</a>
                                <p class="text-sm text-slate-700">${point.price}</p>
                                ${point.address ? `<p class="text-xs text-slate-500 mt-1">${point.address}</p>` : ''}
                            </div>
                        `,
                        hintContent: point.title,
                    },
                    {
                        preset: 'islands#blueDotIcon',
                    }
                );

                map.geoObjects.add(placemark);
                bounds.add(placemark);
            });

            if (points.length > 0) {
                map.setBounds(bounds.getBounds(), {
                    checkZoomRange: true,
                    zoomMargin: 30,
                }).then(function () {
                    if (map.getZoom() > 16) {
                        map.setZoom(16);
                    }
                });
            }
        });
    });
</script>
@endpush

@endsection
