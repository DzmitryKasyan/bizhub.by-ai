@extends('layouts.app')

@section('title', 'Курсы валют')
@section('meta_description', 'Актуальные курсы валют и криптовалют в белорусских рублях. USD, EUR, RUB, CNY, PLN, BTC, ETH.')

@section('content')

<!-- Page Header -->
<section class="bg-slate-900 text-white py-16 relative overflow-hidden">
    <div class="absolute inset-0 hero-grid opacity-30"></div>
    <div class="glow-orb w-64 h-64 bg-primary-500 top-0 right-1/4 opacity-20"></div>
    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4">Курсы валют</h1>
        <p class="text-slate-300 text-lg font-light max-w-xl mx-auto">
            Официальные курсы Национального банка Беларуси и криптовалют
        </p>
        @if($updatedAt)
            <p class="text-slate-400 text-sm mt-3">
                Обновлено: {{ \Carbon\Carbon::parse($updatedAt)->format('d.m.Y H:i') }}
            </p>
        @endif
    </div>
</section>

<!-- Rates Tables -->
<section class="py-16 bg-slate-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

        @php
            $fiat = array_filter($rates, fn($code) => ($labels[$code]['type'] ?? '') === 'fiat', ARRAY_FILTER_USE_KEY);
            $crypto = array_filter($rates, fn($code) => ($labels[$code]['type'] ?? '') === 'crypto', ARRAY_FILTER_USE_KEY);
        @endphp

        <!-- Fiat -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-50">
                <h2 class="text-lg font-bold text-slate-800">Фиатные валюты</h2>
                <p class="text-xs text-slate-400 mt-0.5">Источник: Национальный банк Республики Беларусь</p>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($fiat as $code => $rate)
                    <div class="flex items-center justify-between px-6 py-4 hover:bg-slate-50/50 transition-colors">
                        <div class="flex items-center gap-4">
                            <span class="text-2xl">{{ $labels[$code]['icon'] ?? '💱' }}</span>
                            <div>
                                <p class="font-semibold text-slate-800">{{ $labels[$code]['label'] ?? $code }}</p>
                                <p class="text-xs text-slate-400">{{ $code }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xl font-bold text-slate-900 tabular-nums">{{ number_format($rate, 4, '.', ' ') }}</p>
                            <p class="text-xs text-slate-400">Br</p>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-10 text-center text-slate-400 text-sm">Данные временно недоступны</div>
                @endforelse
            </div>
        </div>

        <!-- Crypto -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-50">
                <h2 class="text-lg font-bold text-slate-800">Криптовалюты</h2>
                <p class="text-xs text-slate-400 mt-0.5">Источник: CoinGecko (цены в Br рассчитаны по курсу USD/BYN)</p>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($crypto as $code => $rate)
                    <div class="flex items-center justify-between px-6 py-4 hover:bg-slate-50/50 transition-colors">
                        <div class="flex items-center gap-4">
                            <span class="text-2xl font-bold text-amber-500">{{ $labels[$code]['icon'] ?? '🪙' }}</span>
                            <div>
                                <p class="font-semibold text-slate-800">{{ $labels[$code]['label'] ?? $code }}</p>
                                <p class="text-xs text-slate-400">{{ $code }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xl font-bold text-slate-900 tabular-nums">{{ number_format($rate, 0, '.', ' ') }}</p>
                            <p class="text-xs text-slate-400">Br</p>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-10 text-center text-slate-400 text-sm">Данные временно недоступны</div>
                @endforelse
            </div>
        </div>

        <!-- Back link -->
        <div class="text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm text-primary-600 hover:text-primary-700 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                На главную
            </a>
        </div>

    </div>
</section>

@endsection
