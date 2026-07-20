@extends('layouts.app')

@section('title', $listing->title)
@section('meta_description', Str::limit(strip_tags($listing->description), 160))
@section('canonical', route('listings.show', $listing->slug))
@section('og_type', 'product')
@section('og_url', route('listings.show', $listing->slug))
@section('og_title', $listing->title)
@section('og_description', Str::limit(strip_tags($listing->description), 160))
@section('og_image', $listing->main_image ? asset('storage/' . $listing->main_image) : asset('favicon.svg'))

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
    'trust_management' => 'Доверительное управление',
];

$typeBadgeColors = [
    'sell_business'    => 'bg-primary-100 text-primary-700',
    'buy_business'     => 'bg-purple-100 text-purple-700',
    'seek_investment'  => 'bg-green-100 text-green-700',
    'offer_investment' => 'bg-emerald-100 text-emerald-700',
    'franchise'        => 'bg-orange-100 text-orange-700',
    'partnership'      => 'bg-yellow-100 text-yellow-700',
    'real_estate'      => 'bg-indigo-100 text-indigo-700',
    'equipment'        => 'bg-slate-100 text-slate-700',
    'trust_management' => 'bg-cyan-100 text-cyan-700',
];

$statusLabels = [
    'active'    => ['label' => 'Активно',       'class' => 'bg-green-100 text-green-700'],
    'pending'   => ['label' => 'На проверке',    'class' => 'bg-yellow-100 text-yellow-700'],
    'sold'      => ['label' => 'Продано',        'class' => 'bg-slate-100 text-slate-600'],
    'archived'  => ['label' => 'В архиве',       'class' => 'bg-slate-100 text-slate-500'],
];

$statusInfo  = $statusLabels[$listing->status->value] ?? ['label' => $listing->status->label(), 'class' => 'bg-slate-100 text-slate-600'];
$badgeClass  = $typeBadgeColors[$listing->type->value] ?? 'bg-slate-100 text-slate-700';
$typeLabel   = $listing->type->label();

$images = array_unique(array_filter($listing->images_array));
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-sm text-slate-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-primary-600 transition-colors">Главная</a>
        <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="{{ route('listings.index') }}" class="hover:text-primary-600 transition-colors">Каталог</a>
        <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-slate-700 truncate max-w-xs">{{ $listing->title }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Left Column: Image Gallery + Details -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Image Gallery -->
            @if(count($images))
            @php $galleryImages = array_values($images); @endphp
            <div class="bg-white rounded-xl overflow-hidden border border-slate-100" id="gallery">
                <!-- Main Image -->
                <div class="aspect-video bg-slate-100 relative overflow-hidden cursor-zoom-in"
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
                                    class="flex-shrink-0 w-20 h-16 rounded-lg overflow-hidden border-2 transition-colors hover:border-primary-400 {{ $i === 0 ? 'border-blue-500' : 'border-transparent' }}">
                                <img src="{{ asset('storage/' . $image) }}"
                                     alt=""
                                     loading="lazy"
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
            <div class="bg-white rounded-xl overflow-hidden border border-slate-100">
                <div class="aspect-video bg-slate-100 flex items-center justify-center">
                    <div class="text-center text-slate-400">
                        <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm">Нет фото</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Title & Badges -->
            <div class="bg-white rounded-xl border border-slate-100 p-6">
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
                    @if($listing->is_representative)
                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium bg-slate-100 text-slate-600 border border-slate-200">
                            Представитель собственника
                        </span>
                    @endif
                </div>

                @include('partials.listing-trust-badges', ['badges' => app(\App\Services\ListingTrustBadgeService::class)->forListing($listing), 'class' => 'mb-3'])

                <h1 class="text-2xl font-bold text-slate-900 mb-4">{{ $listing->title }}</h1>

                <!-- Key Metrics -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 py-4 border-t border-b border-slate-100 mb-4">
                    @if($listing->monthly_revenue)
                        <div class="text-center">
                            <p class="text-xs text-slate-500 mb-1">Выручка/мес</p>
                            <p class="text-base font-bold text-slate-900">
                                {{ number_format($listing->monthly_revenue, 0, '.', ' ') }}
                                <span class="text-xs font-normal text-slate-400">{{ $listing->currency ?? 'Br' }}</span>
                            </p>
                        </div>
                    @endif
                    @if($listing->monthly_profit)
                        <div class="text-center">
                            <p class="text-xs text-slate-500 mb-1">Прибыль/мес</p>
                            <p class="text-base font-bold text-slate-900">
                                {{ number_format($listing->monthly_profit, 0, '.', ' ') }}
                                <span class="text-xs font-normal text-slate-400">{{ $listing->currency ?? 'Br' }}</span>
                            </p>
                        </div>
                    @endif
                    @if($listing->employees_count)
                        <div class="text-center">
                            <p class="text-xs text-slate-500 mb-1">Сотрудников</p>
                            <p class="text-base font-bold text-slate-900">{{ $listing->employees_count }}</p>
                        </div>
                    @endif
                    @if($listing->year_founded)
                        <div class="text-center">
                            <p class="text-xs text-slate-500 mb-1">Год основания</p>
                            <p class="text-base font-bold text-slate-900">{{ $listing->year_founded }}</p>
                        </div>
                    @endif
                </div>

                <!-- Location & Category -->
                <div class="flex flex-wrap gap-4 text-sm text-slate-500">
                    @if($listing->location)
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $listing->location->name }}
                        </div>
                    @endif
                    @if($listing->category)
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            {{ $listing->category->name ?? '' }}
                        </div>
                    @endif
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        {{ number_format($listing->views_count ?? 0, 0, '.', ' ') }} просмотров
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $listing->created_at->format('d.m.Y') }}
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="bg-white rounded-xl border border-slate-100 p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Описание</h2>
                <div class="prose prose-sm max-w-none text-slate-700 leading-relaxed whitespace-pre-line">
                    {{ $listing->description }}
                </div>
            </div>

            <!-- Business Details -->
            @if($listing->payback_months || $listing->investment_amount || $listing->ownership_type || $listing->sale_reason)
            <div class="bg-white rounded-xl border border-slate-100 p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Подробности</h2>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @if($listing->payback_months)
                        <div class="flex flex-col gap-1">
                            <dt class="text-xs text-slate-500 uppercase tracking-wide">Срок окупаемости</dt>
                            <dd class="font-medium text-slate-900">{{ $listing->payback_months }} мес.</dd>
                        </div>
                    @endif
                    @if($listing->investment_amount)
                        <div class="flex flex-col gap-1">
                            <dt class="text-xs text-slate-500 uppercase tracking-wide">Сумма инвестиций</dt>
                            <dd class="font-medium text-slate-900">
                                {{ number_format($listing->investment_amount, 0, '.', ' ') }} {{ $listing->currency ?? 'Br' }}
                            </dd>
                        </div>
                    @endif
                    @if($listing->ownership_type)
                        <div class="flex flex-col gap-1">
                            <dt class="text-xs text-slate-500 uppercase tracking-wide">Форма собственности</dt>
                            <dd class="font-medium text-slate-900">{{ $listing->ownership_type }}</dd>
                        </div>
                    @endif
                    @if($listing->sale_reason)
                        <div class="flex flex-col gap-1 sm:col-span-2">
                            <dt class="text-xs text-slate-500 uppercase tracking-wide">Причина продажи</dt>
                            <dd class="font-medium text-slate-900">{{ $listing->sale_reason }}</dd>
                        </div>
                    @endif
                </dl>
            </div>
            @endif

            <!-- Documents / Data Room -->
            @php
                $publicDocuments = $listing->documents->filter(fn($d) => ! $d->is_confidential);
                $confidentialDocuments = $listing->documents->filter(fn($d) => $d->is_confidential);
            @endphp

            @if($publicDocuments->count())
            <div class="bg-white rounded-xl border border-slate-100 p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Документы</h2>
                <ul class="space-y-2">
                    @foreach($publicDocuments as $document)
                        @include('listings.partials.document-row', ['document' => $document, 'listing' => $listing])
                    @endforeach
                </ul>
            </div>
            @endif

            @if($confidentialDocuments->count())
            <div class="bg-white rounded-xl border border-slate-100 p-6">
                @if($hasSignedNda || (auth()->check() && ($listing->isOwnedBy(auth()->user()) || auth()->user()->isModerator())))
                    <div class="flex items-center gap-2 mb-4">
                        <h2 class="text-lg font-semibold text-slate-900">Data Room</h2>
                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-700">NDA подписано</span>
                    </div>
                    <ul class="space-y-2">
                        @foreach($confidentialDocuments as $document)
                            @include('listings.partials.document-row', ['document' => $document, 'listing' => $listing])
                        @endforeach
                    </ul>
                @else
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <h2 class="text-lg font-semibold text-slate-900">Data Room</h2>
                    </div>
                    <p class="text-sm text-slate-500 mb-4">
                        Конфиденциальные документы и финансы доступны после подписания NDA.
                    </p>
                    @if(auth()->check() && ! $listing->isOwnedBy(auth()->user()))
                        <form action="{{ route('listings.nda.sign', $listing) }}" method="POST" class="space-y-3 nda-form">
                            @csrf
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" name="agree" value="1" required class="mt-1 w-4 h-4 text-primary-600 border-slate-300 rounded focus:ring-primary-300 nda-agree">
                                <span class="text-sm text-slate-600">
                                    Я согласен с условиями NDA и обязуюсь не разглашать конфиденциальную информацию о бизнесе.
                                </span>
                            </label>
                            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold px-6 py-3 rounded-xl transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                                Подписать NDA
                            </button>
                        </form>
                        <script>
                            (function() {
                                const form = document.currentScript.previousElementSibling;
                                const checkbox = form.querySelector('.nda-agree');
                                form.addEventListener('submit', function(e) {
                                    if (!checkbox.checked) {
                                        e.preventDefault();
                                        checkbox.focus();
                                        return false;
                                    }
                                });
                            })();
                        </script>
                    @elseif(! auth()->check())
                        <a href="{{ route('login') }}" class="w-full flex items-center justify-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold px-6 py-3 rounded-xl transition-colors">
                            Войдите, чтобы подписать NDA
                        </a>
                    @endif
                @endif
            </div>
            @endif

            @if(count($dealProgress))
            @php
                $stageStatusStyles = [
                    'pending' => [
                        'badge' => 'bg-slate-100 text-slate-500',
                        'dot' => 'border-2 border-slate-300 bg-white text-slate-400',
                        'line' => 'bg-slate-200',
                        'label' => 'text-slate-900',
                    ],
                    'in_progress' => [
                        'badge' => 'bg-amber-100 text-amber-700',
                        'dot' => 'border-2 border-amber-400 bg-amber-50 text-amber-500',
                        'line' => 'bg-slate-200',
                        'label' => 'text-slate-900',
                    ],
                    'done' => [
                        'badge' => 'bg-emerald-100 text-emerald-700',
                        'dot' => 'bg-emerald-500 text-white',
                        'line' => 'bg-emerald-500',
                        'label' => 'text-slate-900',
                    ],
                    'skipped' => [
                        'badge' => 'bg-slate-100 text-slate-400',
                        'dot' => 'bg-slate-200 text-slate-400',
                        'line' => 'bg-slate-200',
                        'label' => 'text-slate-400 line-through',
                    ],
                ];
                $doneStagesCount = collect($dealProgress)->where('status', 'done')->count();
                $dealProgressPercent = (int) round($doneStagesCount / count($dealProgress) * 100);
                $canEditDealStages = $listing->isOwnedBy(auth()->user()) || auth()->user()->isModerator() || $hasSignedNda;
            @endphp
            <div class="bg-white rounded-xl border border-slate-100 p-6">
                <div class="flex items-center justify-between gap-3 mb-2">
                    <h2 class="text-lg font-semibold text-slate-900">Ход сделки</h2>
                    @if(count($participatingBuyers) > 1)
                        <form method="GET" class="flex items-center gap-2">
                            <label class="text-xs text-slate-500">Покупатель:</label>
                            <select name="buyer_id" onchange="this.form.submit()" class="text-sm border-slate-200 rounded-lg py-1.5">
                                @foreach($participatingBuyers as $buyerOption)
                                    <option value="{{ $buyerOption['id'] }}" {{ $selectedBuyer->id == $buyerOption['id'] ? 'selected' : '' }}>
                                        {{ $buyerOption['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    @endif
                </div>

                <div class="flex items-center gap-3 mb-5">
                    <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500 rounded-full transition-all" style="width: {{ $dealProgressPercent }}%"></div>
                    </div>
                    <span class="text-xs text-slate-500 whitespace-nowrap">{{ $doneStagesCount }} из {{ count($dealProgress) }} этапов</span>
                </div>

                @if(count($participatingBuyers) > 0 && ($listing->isOwnedBy(auth()->user()) || auth()->user()->isModerator()))
                    <div class="flex items-start gap-2 rounded-lg bg-primary-50 border border-primary-100 px-3 py-2 mb-4">
                        <svg class="w-4 h-4 text-primary-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-xs text-primary-700">
                            Вы редактируете статусы для покупателя <strong>{{ $selectedBuyer->name }}</strong>.
                        </p>
                    </div>
                @endif

                <ol>
                    @foreach($dealProgress as $stage)
                        @php $stageStyle = $stageStatusStyles[$stage['status']] ?? $stageStatusStyles['pending']; @endphp
                        <li class="relative flex gap-4 {{ $loop->last ? '' : 'pb-6' }}">
                            @if(!$loop->last)
                                <span class="absolute left-[15px] top-8 bottom-0 w-0.5 {{ $stageStyle['line'] }}"></span>
                            @endif
                            <div class="relative z-10 flex h-8 w-8 shrink-0 items-center justify-center rounded-full {{ $stageStyle['dot'] }}">
                                @if($stage['status'] === 'done')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @elseif($stage['status'] === 'skipped')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                @else
                                    <span class="text-xs font-semibold">{{ $loop->iteration }}</span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0 pt-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="text-sm font-medium {{ $stageStyle['label'] }}">{{ $stage['label'] }}</p>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $stageStyle['badge'] }}">
                                        {{ $stage['status_label'] }}
                                    </span>
                                </div>
                                @if($stage['notes'])
                                    <p class="text-xs text-slate-500 mt-1">{{ $stage['notes'] }}</p>
                                @endif

                                @if($canEditDealStages)
                                    <form action="{{ route('listings.deal-stage.update', $listing) }}" method="POST" class="mt-2 flex flex-wrap items-center gap-2 rounded-lg bg-slate-50 border border-slate-100 p-2">
                                        @csrf
                                        <input type="hidden" name="buyer_id" value="{{ $selectedBuyer->id }}">
                                        <input type="hidden" name="stage" value="{{ $stage['stage'] }}">
                                        <select name="status" class="text-xs border-slate-200 rounded-md py-1.5 pl-2 pr-7 bg-white focus:ring-primary-300 focus:border-primary-300">
                                            @foreach([\App\Enums\DealStageStatus::Pending, \App\Enums\DealStageStatus::InProgress, \App\Enums\DealStageStatus::Done, \App\Enums\DealStageStatus::Skipped] as $statusOption)
                                                <option value="{{ $statusOption->value }}" {{ $stage['status'] === $statusOption->value ? 'selected' : '' }}>{{ $statusOption->label() }}</option>
                                            @endforeach
                                        </select>
                                        <input type="text" name="notes" value="{{ $stage['notes'] ?? '' }}" placeholder="Комментарий" class="text-xs border-slate-200 rounded-md py-1.5 px-2 flex-1 min-w-[8rem] bg-white focus:ring-primary-300 focus:border-primary-300">
                                        <button type="submit" class="text-xs font-medium bg-primary-600 hover:bg-primary-700 text-white px-3 py-1.5 rounded-md transition-colors">Сохранить</button>
                                    </form>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>
            @endif
        </div>

        <!-- Right Column: Price + Contact -->
        <div class="space-y-5">

            <!-- Price Card -->
            <div class="bg-white rounded-xl border border-slate-100 p-6 sticky top-20">
                <!-- Price -->
                <div class="mb-5 pb-5 border-b border-slate-100">
                    @if($listing->price_on_request)
                        <p class="text-sm text-slate-500 mb-1">Стоимость</p>
                        <p class="text-2xl font-bold text-slate-900">Цена по запросу</p>
                    @elseif($listing->price)
                        <p class="text-sm text-slate-500 mb-1">Стоимость</p>
                        <p class="text-3xl font-bold text-slate-900">
                            {{ number_format($listing->price, 0, '.', ' ') }}
                            <span class="text-xl font-semibold text-slate-500">{{ $listing->currency ?? 'Br' }}</span>
                        </p>
                        @if($listing->price_max)
                            <p class="text-sm text-slate-500 mt-1">
                                до {{ number_format($listing->price_max, 0, '.', ' ') }} {{ $listing->currency ?? 'Br' }}
                            </p>
                        @endif
                        @if($listing->price_negotiable)
                            <p class="text-sm text-green-600 font-medium mt-1">Цена договорная</p>
                        @endif
                    @else
                        <p class="text-xl font-semibold text-slate-700">По договорённости</p>
                    @endif
                </div>

                <!-- Contact Actions -->
                @if(auth()->check() && auth()->id() !== ($listing->user_id ?? null))
                    <div x-data="{ open: false }" class="mb-3">
                        <button type="button"
                                @click="open = !open"
                                class="w-full flex items-center justify-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold px-6 py-3 rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-3 3v-3z"/>
                            </svg>
                            Написать сообщение
                        </button>

                        <form action="{{ route('messages.start', $listing) }}"
                              method="POST"
                              x-show="open"
                              x-cloak
                              @click.outside="open = false"
                              class="mt-3">
                            @csrf
                            <textarea name="body"
                                      rows="3"
                                      required
                                      placeholder="Напишите ваше сообщение..."
                                      class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-300 resize-none"
                            ></textarea>
                            <button type="submit"
                                    class="w-full mt-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-4 py-2.5 rounded-xl transition-colors">
                                Отправить
                            </button>
                        </form>
                    </div>

                    @php
                        $isFavorited = auth()->user()->favoritedListings()->where('listings.id', $listing->id)->exists();
                        $favoriteUrl = route('api.listings.favorite', $listing);
                    @endphp

                    <div x-data="{ favorited: {{ $isFavorited ? 'true' : 'false' }}, loading: false }">
                        <button type="button"
                                data-favorite-url="{{ $favoriteUrl }}"
                                :disabled="loading"
                                @click="loading = true; fetch('{{ $favoriteUrl }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest'
                                    }
                                })
                                .then(r => { if (!r.ok) throw new Error('Network error'); return r.json(); })
                                .then(data => { favorited = data.favorited; })
                                .catch(() => { alert('Не удалось обновить избранное. Попробуйте позже.'); })
                                .finally(() => { loading = false; })"
                                :class="favorited
                                    ? 'border-red-200 text-red-600 bg-red-50 hover:bg-red-100'
                                    : 'border-slate-200 text-slate-700 hover:border-slate-300 bg-white'"
                                class="w-full flex items-center justify-center gap-2 border font-medium px-6 py-3 rounded-xl transition-colors">
                            <svg class="w-5 h-5"
                                 :class="favorited ? 'text-red-500' : 'text-slate-400'"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <span x-text="favorited ? 'В избранном' : 'В избранное'"></span>
                        </button>
                    </div>

                    <!-- Report button -->
                    <div x-data="{ reportOpen: false }" class="mt-2">
                        <button type="button"
                                @click="reportOpen = !reportOpen"
                                class="w-full flex items-center justify-center gap-1.5 text-xs text-slate-400 hover:text-red-500 font-medium py-2 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                            </svg>
                            Пожаловаться
                        </button>
                        <form action="{{ route('listings.report', $listing) }}"
                              method="POST"
                              x-show="reportOpen"
                              x-transition
                              class="mt-2 p-4 bg-red-50 rounded-xl border border-red-100 space-y-3"
                              @click.outside="reportOpen = false">
                            @csrf
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Причина</label>
                                <select name="reason" required
                                        class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-300 bg-white">
                                    <option value="">Выберите причину</option>
                                    <option value="spam">Спам</option>
                                    <option value="fraud">Мошенничество</option>
                                    <option value="incorrect_info">Неверная информация</option>
                                    <option value="other">Другое</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Описание (необязательно)</label>
                                <textarea name="description" rows="2" maxlength="1000"
                                          placeholder="Опишите проблему..."
                                          class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-300 resize-none bg-white"></textarea>
                            </div>
                            <button type="submit"
                                    class="w-full bg-red-500 hover:bg-red-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                                Отправить жалобу
                            </button>
                        </form>
                    </div>
                @elseif(!auth()->check())
                    <a href="{{ route('login') }}"
                       class="w-full flex items-center justify-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold px-6 py-3 rounded-xl transition-colors">
                        Войдите, чтобы написать
                    </a>
                @elseif(auth()->id() === ($listing->user_id ?? null))
                    <a href="{{ route('my-listings.edit', $listing->slug) }}"
                       class="w-full flex items-center justify-center gap-2 border border-primary-600 text-primary-600 hover:bg-primary-50 font-medium px-6 py-3 rounded-xl transition-colors">
                        Редактировать объявление
                    </a>
                @endif

                <!-- Deal Support -->
                @if($listing->deal_support_requested)
                    <div class="mt-4 p-4 bg-emerald-50 border border-emerald-100 rounded-xl">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <p class="font-semibold text-emerald-800 text-sm">Сопровождение сделки</p>
                        </div>
                        <p class="text-xs text-emerald-700 mb-2">
                            Продавец готов к сейф-сделке с юридическим сопровождением BizHub и нотариальным оформлением.
                        </p>
                        <p class="text-xs text-emerald-600">
                            Уточняйте условия в переписке.
                        </p>
                    </div>
                @endif

                <!-- Seller Info -->
                @if($listing->user)
                    <div class="mt-5 pt-5 border-t border-slate-100">
                        <p class="text-xs text-slate-500 uppercase tracking-wide mb-3">Продавец</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center flex-shrink-0">
                                @if($listing->is_representative)
                                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                @elseif($listing->user->avatar)
                                    <img src="{{ asset('storage/' . $listing->user->avatar) }}"
                                         alt="{{ $listing->user->name }}"
                                         loading="lazy"
                                         class="w-full h-full object-cover rounded-full">
                                @else
                                    <span class="text-primary-700 font-semibold text-sm">
                                        {{ substr($listing->user->name, 0, 1) }}
                                    </span>
                                @endif
                            </div>
                            <div>
                                @if($listing->is_representative)
                                    <p class="font-medium text-slate-900 text-sm">Представитель собственника</p>
                                    @if($listing->representative_note)
                                        <p class="text-xs text-slate-400">{{ $listing->representative_note }}</p>
                                    @endif
                                @else
                                    <p class="font-medium text-slate-900 text-sm">{{ $listing->user->name }}</p>
                                    <p class="text-xs text-slate-400">
                                        На сайте с {{ $listing->user->created_at->format('Y') }} г.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Contacts -->
                @php
                    $contactPhone = $listing->contacts->firstWhere('type', 'phone');
                    $contactTelegram = $listing->contacts->firstWhere('type', 'telegram');
                @endphp
                @if($contactPhone || $contactTelegram)
                    <div class="mt-5 pt-5 border-t border-slate-100">
                        <p class="text-xs text-slate-500 uppercase tracking-wide mb-3">Контакты</p>
                        <div class="space-y-2">
                            @if($contactPhone)
                                <a href="tel:{{ $contactPhone->value }}"
                                   class="flex items-center gap-2 text-sm text-slate-700 hover:text-primary-600">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    {{ $contactPhone->value }}
                                </a>
                            @endif
                            @if($contactTelegram)
                                <a href="https://t.me/{{ ltrim($contactTelegram->value, '@') }}"
                                   target="_blank"
                                   class="flex items-center gap-2 text-sm text-slate-700 hover:text-sky-600">
                                    <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                                    </svg>
                                    {{ $contactTelegram->value }}
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Share -->
            <div class="bg-white rounded-xl border border-slate-100 p-5">
                <p class="text-sm font-medium text-slate-700 mb-3">Поделиться</p>
                <div class="flex gap-2">
                    <a href="https://t.me/share/url?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($listing->title) }}"
                       target="_blank"
                       class="flex-1 flex items-center justify-center py-2 rounded-lg bg-sky-50 hover:bg-sky-100 text-sky-600 text-sm font-medium transition-colors">
                        Telegram
                    </a>
                    <button onclick="navigator.clipboard.writeText(window.location.href)"
                            class="flex-1 flex items-center justify-center py-2 rounded-lg bg-slate-50 hover:bg-slate-100 text-slate-600 text-sm font-medium transition-colors">
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
            <h2 class="text-xl font-bold text-slate-900">Похожие объявления</h2>
            <a href="{{ route('listings.index', ['type' => $listing->type->value]) }}"
               class="text-primary-600 hover:text-primary-700 text-sm font-medium">
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