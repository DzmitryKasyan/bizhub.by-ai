@extends('layouts.app')

@section('title', $listing->title)
@section('meta_description', Str::limit(strip_tags($listing->description), 160))

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

$statusLabels = [
    'active'    => ['label' => 'Активно',       'class' => 'bg-green-100 text-green-700'],
    'pending'   => ['label' => 'На проверке',    'class' => 'bg-yellow-100 text-yellow-700'],
    'sold'      => ['label' => 'Продано',        'class' => 'bg-gray-100 text-gray-600'],
    'archived'  => ['label' => 'В архиве',       'class' => 'bg-gray-100 text-gray-500'],
];

$statusInfo  = $statusLabels[$listing->status->value] ?? ['label' => $listing->status->label(), 'class' => 'bg-gray-100 text-gray-600'];
$badgeClass  = $typeBadgeColors[$listing->type->value] ?? 'bg-gray-100 text-gray-700';
$typeLabel   = $listing->type->label();

$images = array_unique(array_filter($listing->images_array));
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-blue-600 transition-colors">Главная</a>
        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="{{ route('listings.index') }}" class="hover:text-blue-600 transition-colors">Каталог</a>
        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-700 truncate max-w-xs">{{ $listing->title }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Left Column: Image Gallery + Details -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Image Gallery -->
            @if(count($images))
            @php $galleryImages = array_values($images); @endphp
            <div class="bg-white rounded-xl overflow-hidden border border-gray-100" id="gallery">
                <!-- Main Image -->
                <div class="aspect-video bg-gray-100 relative overflow-hidden cursor-zoom-in"
                     onclick="openLightbox(this.querySelector('img').src)">
                    <img id="gallery-main"
                         src="{{ asset('storage/' . $galleryImages[0]) }}"
                         alt="{{ $listing->title }}"
                         class="w-full h-full object-cover transition-opacity duration-200">
                </div>

                <!-- Thumbnails -->
                @if(count($galleryImages) > 1)
                    <div class="flex gap-2 p-3 overflow-x-auto">
                        @foreach($galleryImages as $i => $image)
                            <button onclick="setGalleryImage('{{ asset('storage/' . $image) }}', this)"
                                    data-active="{{ $i === 0 ? 'true' : 'false' }}"
                                    class="flex-shrink-0 w-20 h-16 rounded-lg overflow-hidden border-2 transition-colors hover:border-blue-400 {{ $i === 0 ? 'border-blue-500' : 'border-transparent' }}">
                                <img src="{{ asset('storage/' . $image) }}"
                                     alt=""
                                     class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Lightbox -->
            <div id="lightbox"
                 onclick="closeLightbox()"
                 style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.92); z-index:9999; cursor:zoom-out; align-items:center; justify-content:center;">
                <img id="lightbox-img" src="" alt=""
                     style="max-width:92vw; max-height:92vh; object-fit:contain; border-radius:8px; box-shadow:0 0 60px rgba(0,0,0,0.8);"
                     onclick="event.stopPropagation()">
                <button onclick="closeLightbox()"
                        style="position:absolute; top:20px; right:24px; color:#fff; font-size:32px; background:none; border:none; cursor:pointer; line-height:1;">&times;</button>
            </div>

            <script>
                function setGalleryImage(src, btn) {
                    document.getElementById('gallery-main').src = src;
                    document.querySelectorAll('#gallery button').forEach(function(b) {
                        b.classList.remove('border-blue-500');
                        b.classList.add('border-transparent');
                    });
                    btn.classList.remove('border-transparent');
                    btn.classList.add('border-blue-500');
                }
                function openLightbox(src) {
                    var lb = document.getElementById('lightbox');
                    document.getElementById('lightbox-img').src = src;
                    lb.style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                }
                function closeLightbox() {
                    document.getElementById('lightbox').style.display = 'none';
                    document.body.style.overflow = '';
                }
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') closeLightbox();
                });
            </script>
            @else
            <div class="bg-white rounded-xl overflow-hidden border border-gray-100">
                <div class="aspect-video bg-gray-100 flex items-center justify-center">
                    <div class="text-center text-gray-400">
                        <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm">Нет фото</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Title & Badges -->
            <div class="bg-white rounded-xl border border-gray-100 p-6">
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium {{ $badgeClass }}">
                        {{ $typeLabel }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium {{ $statusInfo['class'] }}">
                        {{ $statusInfo['label'] }}
                    </span>
                    @if($listing->is_featured ?? false)
                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium bg-amber-100 text-amber-700">
                            ТОП объявление
                        </span>
                    @endif
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $listing->title }}</h1>

                <!-- Key Metrics -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 py-4 border-t border-b border-gray-100 mb-4">
                    @if($listing->monthly_revenue)
                        <div class="text-center">
                            <p class="text-xs text-gray-500 mb-1">Выручка/мес</p>
                            <p class="text-base font-bold text-gray-900">
                                {{ number_format($listing->monthly_revenue, 0, '.', ' ') }}
                                <span class="text-xs font-normal text-gray-400">{{ $listing->currency ?? 'BYN' }}</span>
                            </p>
                        </div>
                    @endif
                    @if($listing->monthly_profit)
                        <div class="text-center">
                            <p class="text-xs text-gray-500 mb-1">Прибыль/мес</p>
                            <p class="text-base font-bold text-gray-900">
                                {{ number_format($listing->monthly_profit, 0, '.', ' ') }}
                                <span class="text-xs font-normal text-gray-400">{{ $listing->currency ?? 'BYN' }}</span>
                            </p>
                        </div>
                    @endif
                    @if($listing->employees_count)
                        <div class="text-center">
                            <p class="text-xs text-gray-500 mb-1">Сотрудников</p>
                            <p class="text-base font-bold text-gray-900">{{ $listing->employees_count }}</p>
                        </div>
                    @endif
                    @if($listing->year_founded)
                        <div class="text-center">
                            <p class="text-xs text-gray-500 mb-1">Год основания</p>
                            <p class="text-base font-bold text-gray-900">{{ $listing->year_founded }}</p>
                        </div>
                    @endif
                </div>

                <!-- Location & Category -->
                <div class="flex flex-wrap gap-4 text-sm text-gray-500">
                    @if($listing->location)
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $listing->location->name }}
                        </div>
                    @endif
                    @if($listing->category)
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            {{ $listing->category->name ?? '' }}
                        </div>
                    @endif
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        {{ number_format($listing->views_count ?? 0, 0, '.', ' ') }} просмотров
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $listing->created_at->format('d.m.Y') }}
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="bg-white rounded-xl border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Описание</h2>
                <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed whitespace-pre-line">
                    {{ $listing->description }}
                </div>
            </div>

            <!-- Business Details -->
            @if($listing->payback_months || $listing->investment_amount || $listing->ownership_type || $listing->sale_reason)
            <div class="bg-white rounded-xl border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Подробности</h2>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @if($listing->payback_months)
                        <div class="flex flex-col gap-1">
                            <dt class="text-xs text-gray-500 uppercase tracking-wide">Срок окупаемости</dt>
                            <dd class="font-medium text-gray-900">{{ $listing->payback_months }} мес.</dd>
                        </div>
                    @endif
                    @if($listing->investment_amount)
                        <div class="flex flex-col gap-1">
                            <dt class="text-xs text-gray-500 uppercase tracking-wide">Сумма инвестиций</dt>
                            <dd class="font-medium text-gray-900">
                                {{ number_format($listing->investment_amount, 0, '.', ' ') }} {{ $listing->currency ?? 'BYN' }}
                            </dd>
                        </div>
                    @endif
                    @if($listing->ownership_type)
                        <div class="flex flex-col gap-1">
                            <dt class="text-xs text-gray-500 uppercase tracking-wide">Форма собственности</dt>
                            <dd class="font-medium text-gray-900">{{ $listing->ownership_type }}</dd>
                        </div>
                    @endif
                    @if($listing->sale_reason)
                        <div class="flex flex-col gap-1 sm:col-span-2">
                            <dt class="text-xs text-gray-500 uppercase tracking-wide">Причина продажи</dt>
                            <dd class="font-medium text-gray-900">{{ $listing->sale_reason }}</dd>
                        </div>
                    @endif
                </dl>
            </div>
            @endif

            <!-- Documents -->
            @if(isset($listing->documents) && count($listing->documents))
            <div class="bg-white rounded-xl border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Документы</h2>
                <ul class="space-y-2">
                    @foreach($listing->documents as $document)
                        <li>
                            <a href="{{ asset('storage/' . $document->file_path) }}"
                               target="_blank"
                               download="{{ $document->name }}"
                               class="flex items-center gap-3 p-3 rounded-lg border border-gray-100 hover:border-blue-200 hover:bg-blue-50 transition-colors group">
                                <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 group-hover:text-blue-700 truncate">{{ $document->name }}</p>
                                    @if($document->size)
                                        <p class="text-xs text-gray-400">{{ number_format($document->size / 1024, 0, '.', ' ') }} КБ</p>
                                    @endif
                                </div>
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        <!-- Right Column: Price + Contact -->
        <div class="space-y-5">

            <!-- Price Card -->
            <div class="bg-white rounded-xl border border-gray-100 p-6 sticky top-20">
                <!-- Price -->
                <div class="mb-5 pb-5 border-b border-gray-100">
                    @if($listing->price)
                        <p class="text-sm text-gray-500 mb-1">Стоимость</p>
                        <p class="text-3xl font-bold text-gray-900">
                            {{ number_format($listing->price, 0, '.', ' ') }}
                            <span class="text-xl font-semibold text-gray-500">{{ $listing->currency ?? 'BYN' }}</span>
                        </p>
                        @if($listing->price_max)
                            <p class="text-sm text-gray-500 mt-1">
                                до {{ number_format($listing->price_max, 0, '.', ' ') }} {{ $listing->currency ?? 'BYN' }}
                            </p>
                        @endif
                        @if($listing->price_negotiable)
                            <p class="text-sm text-green-600 font-medium mt-1">Цена договорная</p>
                        @endif
                    @else
                        <p class="text-xl font-semibold text-gray-700">По договорённости</p>
                    @endif
                </div>

                <!-- Contact Actions -->
                @if(auth()->check() && auth()->id() !== ($listing->user_id ?? null))
                    <a href="#"
                       class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-xl transition-colors mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-3 3v-3z"/>
                        </svg>
                        Написать сообщение
                    </a>
                    <button
                       class="w-full flex items-center justify-center gap-2 border border-gray-200 hover:border-gray-300 text-gray-700 font-medium px-6 py-3 rounded-xl transition-colors">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        В избранное
                    </button>
                @elseif(!auth()->check())
                    <a href="{{ route('login') }}"
                       class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-xl transition-colors">
                        Войдите, чтобы написать
                    </a>
                @elseif(auth()->id() === ($listing->user_id ?? null))
                    <a href="{{ route('my-listings.edit', $listing->slug) }}"
                       class="w-full flex items-center justify-center gap-2 border border-blue-600 text-blue-600 hover:bg-blue-50 font-medium px-6 py-3 rounded-xl transition-colors">
                        Редактировать объявление
                    </a>
                @endif

                <!-- Seller Info -->
                @if($listing->user)
                    <div class="mt-5 pt-5 border-t border-gray-100">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-3">Продавец</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                @if($listing->user->avatar)
                                    <img src="{{ asset('storage/' . $listing->user->avatar) }}"
                                         alt="{{ $listing->user->name }}"
                                         class="w-full h-full object-cover rounded-full">
                                @else
                                    <span class="text-blue-700 font-semibold text-sm">
                                        {{ substr($listing->user->name, 0, 1) }}
                                    </span>
                                @endif
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 text-sm">{{ $listing->user->name }}</p>
                                <p class="text-xs text-gray-400">
                                    На сайте с {{ $listing->user->created_at->format('Y') }} г.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Share -->
            <div class="bg-white rounded-xl border border-gray-100 p-5">
                <p class="text-sm font-medium text-gray-700 mb-3">Поделиться</p>
                <div class="flex gap-2">
                    <a href="https://t.me/share/url?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($listing->title) }}"
                       target="_blank"
                       class="flex-1 flex items-center justify-center py-2 rounded-lg bg-sky-50 hover:bg-sky-100 text-sky-600 text-sm font-medium transition-colors">
                        Telegram
                    </a>
                    <button onclick="navigator.clipboard.writeText(window.location.href)"
                            class="flex-1 flex items-center justify-center py-2 rounded-lg bg-gray-50 hover:bg-gray-100 text-gray-600 text-sm font-medium transition-colors">
                        Копировать
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Similar Listings -->
    @if(isset($similar) && $similar->count())
    <section class="mt-12">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900">Похожие объявления</h2>
            <a href="{{ route('listings.index', ['type' => $listing->type->value]) }}"
               class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                Смотреть все →
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($similar as $item)
                @include('partials.listing-card', [
                    'listing' => $item,
                    'typeLabels' => $typeLabels,
                    'typeBadgeColors' => $typeBadgeColors,
                ])
            @endforeach
        </div>
    </section>
    @endif
</div>

@endsection
