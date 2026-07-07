@php
$typeLabels = $typeLabels ?? [
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

$typeBadgeColors = $typeBadgeColors ?? [
    'sell_business'    => 'bg-primary-100 text-primary-700',
    'buy_business'     => 'bg-purple-100 text-purple-700',
    'seek_investment'  => 'bg-emerald-100 text-emerald-700',
    'offer_investment' => 'bg-teal-100 text-teal-700',
    'franchise'        => 'bg-orange-100 text-orange-700',
    'partnership'      => 'bg-amber-100 text-amber-700',
    'real_estate'      => 'bg-indigo-100 text-indigo-700',
    'equipment'        => 'bg-slate-100 text-slate-700',
    'trust_management' => 'bg-cyan-100 text-cyan-700',
];

$badgeClass = $typeBadgeColors[$listing->type->value] ?? 'bg-slate-100 text-slate-700';
$typeLabel  = $listing->type->label();
@endphp

<article class="bg-white rounded-2xl overflow-hidden border border-slate-100 hover:shadow-xl hover:shadow-primary-500/10 hover:border-primary-100 transition-all group card-3d">
    <!-- Image -->
    <a href="{{ route('listings.show', $listing->slug) }}" class="block relative overflow-hidden aspect-video bg-slate-100">
        @if($listing->main_image)
            <img src="{{ asset('storage/' . $listing->main_image) }}"
                 alt="{{ $listing->title }}"
                 loading="lazy"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        @else
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200">
                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
        @endif

        <!-- Type Badge on image -->
        <div class="absolute top-3 left-3">
            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold {{ $badgeClass }} backdrop-blur-sm shadow-sm">
                {{ $typeLabel }}
            </span>
        </div>

        <!-- Representative Badge -->
        @if($listing->is_representative)
            <div class="absolute top-3 right-3">
                <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200 shadow-sm">
                    Представитель собственника
                </span>
            </div>
        @elseif($listing->is_featured ?? false)
            <div class="absolute top-3 right-3">
                <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-semibold bg-gradient-to-r from-amber-400 to-amber-500 text-white shadow-sm">
                    ТОП
                </span>
            </div>
        @endif
    </a>

    <!-- Content -->
    <div class="p-5">
        <!-- Title -->
        <a href="{{ route('listings.show', $listing->slug) }}"
           class="block font-semibold text-slate-900 hover:text-primary-600 transition-colors leading-snug mb-2 line-clamp-2">
            {{ $listing->title }}
        </a>

        <!-- KPIs -->
        @php $kpis = $listing->kpis; @endphp
        @if(count($kpis))
            <div class="flex flex-wrap gap-2 mb-3">
                @foreach(array_slice($kpis, 0, 4) as $kpi)
                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-slate-50 text-slate-600 border border-slate-100">
                        {{ $kpi['label'] }}: {{ $kpi['value'] }}
                    </span>
                @endforeach
            </div>
        @endif

        @include('partials.listing-trust-badges', ['badges' => app(\App\Services\ListingTrustBadgeService::class)->forListing($listing), 'class' => 'mb-3'])

        <!-- Price -->
        <div class="mb-3">
            @if($listing->price_on_request)
                <span class="text-lg font-semibold text-slate-700">Цена по запросу</span>
            @elseif($listing->price)
                <span class="text-xl font-bold text-slate-900">
                    {{ number_format($listing->price, 0, '.', ' ') }}
                </span>
                <span class="text-slate-500 font-medium ml-1">{{ $listing->currency ?? 'Br' }}</span>
                @if($listing->price_negotiable)
                    <span class="text-slate-400 text-xs ml-1">· торг</span>
                @endif
            @else
                <span class="text-slate-500 text-sm font-medium">По договорённости</span>
            @endif
        </div>

        <!-- Meta -->
        <div class="flex items-center justify-between text-xs text-slate-400 pt-3 border-t border-slate-50">
            <div class="flex items-center gap-1">
                @if($listing->location)
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="truncate max-w-[120px]">{{ $listing->location?->name }}</span>
                @endif
            </div>
            <div class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <span>{{ number_format($listing->views_count ?? 0, 0, '.', ' ') }}</span>
            </div>
        </div>
    </div>
</article>
