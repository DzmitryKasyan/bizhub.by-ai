@extends('layouts.dashboard')

@section('title', 'Мои объявления')

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

$statusConfig = [
    'draft'    => ['label' => 'Черновики',     'class' => 'bg-gray-100 text-gray-500'],
    'active'   => ['label' => 'Активно',       'class' => 'bg-green-100 text-green-700'],
    'pending'  => ['label' => 'На проверке',   'class' => 'bg-yellow-100 text-yellow-700'],
    'sold'     => ['label' => 'Продано',       'class' => 'bg-gray-100 text-gray-600'],
    'archived' => ['label' => 'Архив',         'class' => 'bg-gray-100 text-gray-400'],
    'rejected' => ['label' => 'Отклонено',     'class' => 'bg-red-100 text-red-600'],
];
@endphp

<!-- Page Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Мои объявления</h1>
        @if(isset($listings))
            <p class="text-gray-500 text-sm mt-1">Всего: {{ $listings->total() }}</p>
        @endif
    </div>
    <a href="{{ route('my-listings.create') }}"
       class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-xl transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Подать объявление
    </a>
</div>

<!-- Filters Tabs -->
<div class="flex gap-1 bg-gray-100 p-1 rounded-xl mb-5 w-fit">
    <a href="{{ route('my-listings.index') }}"
       class="px-4 py-2 text-sm font-medium rounded-lg transition-colors
              {{ !request('status') ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
        Все
    </a>
    @foreach($statusConfig as $statusKey => $cfg)
        <a href="{{ route('my-listings.index', ['status' => $statusKey]) }}"
           class="px-4 py-2 text-sm font-medium rounded-lg transition-colors
                  {{ request('status') === $statusKey ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
            {{ $cfg['label'] }}
        </a>
    @endforeach
</div>

<!-- Table -->
<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    @if(isset($listings) && $listings->count())
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-3.5">Объявление</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3.5">Тип</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3.5">Статус</th>
                        <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3.5">Просмотры</th>
                        <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3.5">Дата</th>
                        <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-3.5">Действия</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($listings as $listing)
                        @php
                            $status = $statusConfig[$listing->status->value] ?? ['label' => $listing->status->label(), 'class' => 'bg-gray-100 text-gray-500'];
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <!-- Title + Image -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
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
                                    <div class="min-w-0">
                                        <a href="{{ route('listings.show', $listing->slug) }}"
                                           target="_blank"
                                           class="font-medium text-gray-900 text-sm hover:text-blue-600 transition-colors line-clamp-1 block">
                                            {{ $listing->title }}
                                        </a>
                                        @if($listing->price)
                                            <p class="text-xs text-gray-400 mt-0.5">
                                                {{ number_format($listing->price, 0, '.', ' ') }} {{ $listing->currency ?? 'BYN' }}
                                            </p>
                                        @else
                                            <p class="text-xs text-gray-400 mt-0.5">По договорённости</p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Type -->
                            <td class="px-4 py-4">
                                <span class="text-xs text-gray-600 whitespace-nowrap">
                                    {{ $listing->type->label() }}
                                </span>
                            </td>

                            <!-- Status -->
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $status['class'] }}">
                                    {{ $status['label'] }}
                                </span>
                            </td>

                            <!-- Views -->
                            <td class="px-4 py-4 text-right">
                                <span class="text-sm text-gray-600">
                                    {{ number_format($listing->views_count ?? 0, 0, '.', ' ') }}
                                </span>
                            </td>

                            <!-- Date -->
                            <td class="px-4 py-4 text-right">
                                <span class="text-xs text-gray-400 whitespace-nowrap">
                                    {{ $listing->created_at->format('d.m.Y') }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2" x-data="{ menuOpen: false }">
                                    <!-- Edit -->
                                    <a href="{{ route('my-listings.edit', $listing->slug) }}"
                                       class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                       title="Редактировать">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>

                                    <!-- Toggle Archive/Publish -->
                                    @if($listing->status === 'active')
                                        <form method="POST" action="{{ route('my-listings.archive', $listing->slug) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="p-1.5 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors"
                                                    title="Архивировать">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @elseif($listing->status === 'archived')
                                        <form method="POST" action="{{ route('my-listings.publish', $listing->slug) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                                                    title="Опубликовать">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Delete -->
                                    <form method="POST"
                                          action="{{ route('my-listings.destroy', $listing->slug) }}"
                                          onsubmit="return confirm('Вы уверены, что хотите удалить это объявление?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Удалить">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($listings->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $listings->appends(request()->query())->links() }}
            </div>
        @endif

    @else
        <div class="py-16 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Нет объявлений</h3>
            <p class="text-gray-500 text-sm mb-5">Создайте своё первое объявление прямо сейчас</p>
            <a href="{{ route('my-listings.create') }}"
               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Подать объявление
            </a>
        </div>
    @endif
</div>

@endsection
