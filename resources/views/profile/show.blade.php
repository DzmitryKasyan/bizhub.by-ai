@extends('layouts.app')

@section('title', $user->name)
@section('meta_description', $user->bio ? Str::limit($user->bio, 160) : 'Профиль пользователя ' . $user->name . ' на BizHub.by')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Left Column: Profile Card -->
        <div class="lg:col-span-1 space-y-5">

            <!-- Profile Card -->
            <div class="bg-white rounded-xl border border-gray-100 p-6">
                <!-- Avatar -->
                <div class="flex flex-col items-center text-center mb-6">
                    <div class="w-24 h-24 rounded-full bg-blue-100 flex items-center justify-center mb-4 overflow-hidden">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}"
                                 alt="{{ $user->name }}"
                                 class="w-full h-full object-cover">
                        @else
                            <span class="text-blue-700 font-bold text-3xl">
                                {{ substr($user->name, 0, 1) }}
                            </span>
                        @endif
                    </div>

                    <h1 class="text-xl font-bold text-gray-900">{{ $user->name }}</h1>

                    @if($user->company_name)
                        <p class="text-sm text-gray-500 mt-1">{{ $user->company_name }}</p>
                    @endif

                    @if($user->role)
                        @php
                            $roleLabels = [
                                'seller'   => 'Продавец',
                                'buyer'    => 'Покупатель',
                                'investor' => 'Инвестор',
                                'admin'    => 'Администратор',
                            ];
                        @endphp
                        <span class="mt-2 inline-block text-xs font-medium px-2.5 py-1 bg-blue-100 text-blue-700 rounded-full">
                            {{ $roleLabels[$user->role] ?? $user->role }}
                        </span>
                    @endif
                </div>

                <!-- Bio -->
                @if($user->bio)
                    <div class="border-t border-gray-100 pt-5 mb-5">
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $user->bio }}</p>
                    </div>
                @endif

                <!-- Stats -->
                <div class="grid grid-cols-3 gap-3 border-t border-gray-100 pt-5 mb-5">
                    <div class="text-center">
                        <p class="text-xl font-bold text-gray-900">{{ $listings->total() }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Объявлений</p>
                    </div>
                    <div class="text-center border-x border-gray-100">
                        <p class="text-xl font-bold text-gray-900">
                            @if($user->rating)
                                {{ number_format($user->rating, 1) }}
                            @else
                                —
                            @endif
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">Рейтинг</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xl font-bold text-gray-900">{{ $user->reviews_count ?? $reviews->count() }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Отзывов</p>
                    </div>
                </div>

                <!-- Member Since -->
                <div class="flex items-center gap-2 text-xs text-gray-400 border-t border-gray-100 pt-4">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    На сайте с {{ $user->created_at->format('d.m.Y') }}
                </div>

                <!-- Contact Button -->
                @auth
                    @if(auth()->id() !== $user->id)
                        @if($listings->count())
                            <div class="mt-4">
                                <a href="{{ route('listings.show', $listings->first()->slug) }}"
                                   class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2.5 rounded-xl transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-3 3v-3z"/>
                                    </svg>
                                    Написать сообщение
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="mt-4">
                            <a href="{{ route('profile.edit') }}"
                               class="w-full flex items-center justify-center gap-2 border border-gray-200 hover:border-gray-300 text-gray-700 text-sm font-medium py-2.5 rounded-xl hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Редактировать профиль
                            </a>
                        </div>
                    @endif
                @endauth
            </div>

            <!-- Rating Stars Card -->
            @if($user->rating)
                <div class="bg-white rounded-xl border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-900 text-sm mb-3">Рейтинг</h3>
                    <div class="flex items-center gap-2">
                        <div class="flex items-center gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= round($user->rating))
                                    <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-200" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endif
                            @endfor
                        </div>
                        <span class="text-lg font-bold text-gray-900">{{ number_format($user->rating, 1) }}</span>
                        <span class="text-sm text-gray-400">из 5</span>
                    </div>
                </div>
            @endif

        </div>

        <!-- Right Column: Listings & Reviews -->
        <div class="lg:col-span-2" x-data="{ tab: 'listings' }">

            <!-- Tabs -->
            <div class="flex gap-1 mb-6 bg-gray-100 p-1 rounded-xl w-fit">
                <button @click="tab = 'listings'"
                        :class="tab === 'listings' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                        class="px-5 py-2 rounded-lg text-sm font-medium transition-all">
                    Объявления
                    @if($listings->total() > 0)
                        <span class="ml-1.5 text-xs bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded-full">
                            {{ $listings->total() }}
                        </span>
                    @endif
                </button>
                <button @click="tab = 'reviews'"
                        :class="tab === 'reviews' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                        class="px-5 py-2 rounded-lg text-sm font-medium transition-all">
                    Отзывы
                    @if($reviews->count() > 0)
                        <span class="ml-1.5 text-xs bg-gray-200 text-gray-600 px-1.5 py-0.5 rounded-full">
                            {{ $reviews->count() }}
                        </span>
                    @endif
                </button>
            </div>

            <!-- Listings Tab -->
            <div x-show="tab === 'listings'" x-transition>
                @if($listings->count())
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        @foreach($listings as $listing)
                            @include('partials.listing-card', ['listing' => $listing])
                        @endforeach
                    </div>

                    @if($listings->hasPages())
                        <div class="mt-6">
                            {{ $listings->links() }}
                        </div>
                    @endif
                @else
                    <div class="bg-white rounded-xl border border-gray-100 py-16 text-center">
                        <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <p class="text-gray-500 text-sm">Нет активных объявлений</p>
                    </div>
                @endif
            </div>

            <!-- Reviews Tab -->
            <div x-show="tab === 'reviews'" x-transition style="display: none;">
                @if($reviews->count())
                    <div class="space-y-4">
                        @foreach($reviews as $review)
                            <div class="bg-white rounded-xl border border-gray-100 p-6">
                                <div class="flex items-start justify-between gap-4 mb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">
                                            <span class="text-gray-600 font-semibold text-sm">
                                                {{ substr($review->reviewer->name ?? 'А', 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900 text-sm">
                                                {{ $review->reviewer->name ?? 'Аноним' }}
                                            </p>
                                            <p class="text-xs text-gray-400">
                                                {{ $review->created_at->format('d.m.Y') }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Stars -->
                                    @if(isset($review->rating))
                                        <div class="flex items-center gap-0.5 flex-shrink-0">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4 text-gray-200" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @endif
                                            @endfor
                                        </div>
                                    @endif
                                </div>

                                @if(isset($review->comment) && $review->comment)
                                    <p class="text-sm text-gray-700 leading-relaxed">{{ $review->comment }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-xl border border-gray-100 py-16 text-center">
                        <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                        </div>
                        <p class="text-gray-500 text-sm">Отзывов пока нет</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>

@endsection
