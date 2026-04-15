@extends('layouts.dashboard')

@section('title', 'Сообщения')

@section('content')

<!-- Page Header -->
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Сообщения</h1>
    <p class="text-gray-500 text-sm mt-1">Ваши переписки с покупателями и продавцами</p>
</div>

<!-- Conversations List -->
<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">

    @if($conversations->count())

        <div class="divide-y divide-gray-50">
            @foreach($conversations as $conversation)
                @php
                    $otherUser = $conversation->participant_one_id === auth()->id()
                        ? $conversation->participantTwo
                        : $conversation->participantOne;

                    $latestMsg = $conversation->latestMessage;
                @endphp

                <a href="{{ route('messages.show', $conversation) }}"
                   class="flex items-start gap-4 px-6 py-5 hover:bg-gray-50 transition-colors group">

                    <!-- Avatar -->
                    <div class="w-11 h-11 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                        @if($otherUser && $otherUser->avatar)
                            <img src="{{ asset('storage/' . $otherUser->avatar) }}"
                                 alt="{{ $otherUser->name }}"
                                 class="w-full h-full rounded-full object-cover">
                        @else
                            <span class="text-blue-700 font-semibold text-sm">
                                {{ $otherUser ? substr($otherUser->name, 0, 1) : '?' }}
                            </span>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2 mb-1">
                            <span class="font-semibold text-gray-900 text-sm truncate group-hover:text-blue-600 transition-colors">
                                {{ $otherUser ? $otherUser->name : 'Удалённый пользователь' }}
                            </span>
                            @if($conversation->last_message_at)
                                <span class="text-xs text-gray-400 flex-shrink-0">
                                    {{ $conversation->last_message_at->diffForHumans() }}
                                </span>
                            @endif
                        </div>

                        @if($conversation->listing)
                            <p class="text-xs text-blue-600 mb-1 truncate">
                                <svg class="w-3 h-3 inline mr-0.5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                {{ $conversation->listing->title }}
                            </p>
                        @endif

                        <p class="text-sm text-gray-500 truncate">
                            @if($latestMsg)
                                @if($latestMsg->sender_id === auth()->id())
                                    <span class="text-gray-400">Вы: </span>
                                @endif
                                {{ $latestMsg->body }}
                            @else
                                <span class="text-gray-400 italic">Нет сообщений</span>
                            @endif
                        </p>
                    </div>

                    <!-- Arrow -->
                    <div class="flex-shrink-0 text-gray-300 group-hover:text-blue-400 transition-colors self-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($conversations->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $conversations->links() }}
            </div>
        @endif

    @else

        <!-- Empty State -->
        <div class="py-20 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-3 3v-3z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Нет сообщений</h3>
            <p class="text-gray-500 text-sm mb-6 max-w-sm mx-auto">
                Когда вы начнёте переписку с продавцами или покупателями, она появится здесь.
            </p>
            <a href="{{ route('listings.index') }}"
               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Смотреть объявления
            </a>
        </div>

    @endif
</div>

@endsection
