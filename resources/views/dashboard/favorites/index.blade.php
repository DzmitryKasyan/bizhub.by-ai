@extends('layouts.dashboard')

@section('title', 'Избранное')

@section('content')

<!-- Page Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Избранное</h1>
        <p class="text-gray-500 text-sm mt-1">Объявления, которые вы сохранили</p>
    </div>
    @if($listings->total() > 0)
        <span class="text-sm text-gray-400 font-medium">
            {{ $listings->total() }} {{ trans_choice('объявление|объявления|объявлений', $listings->total()) }}
        </span>
    @endif
</div>

@if($listings->count())

    <!-- Listings Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-6">
        @foreach($listings as $listing)
            <div class="relative">
                @include('partials.listing-card', ['listing' => $listing])

                <!-- Remove from favorites button -->
                <form action="{{ route('favorites.toggle', $listing) }}"
                      method="POST"
                      class="absolute top-3 right-3 z-10">
                    @csrf
                    <button type="submit"
                            title="Удалить из избранного"
                            class="w-8 h-8 bg-white/90 backdrop-blur-sm hover:bg-white rounded-full flex items-center justify-center shadow-sm transition-colors group">
                        <svg class="w-4 h-4 text-red-500 group-hover:text-red-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </button>
                </form>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($listings->hasPages())
        <div class="mt-6">
            {{ $listings->links() }}
        </div>
    @endif

@else

    <!-- Empty State -->
    <div class="bg-white rounded-xl border border-gray-100 py-20 text-center">
        <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Пока пусто</h3>
        <p class="text-gray-500 text-sm mb-6 max-w-sm mx-auto">
            Добавляйте понравившиеся объявления в избранное, нажимая на значок сердечка
        </p>
        <a href="{{ route('listings.index') }}"
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            Смотреть каталог
        </a>
    </div>

@endif

@endsection
