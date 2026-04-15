@extends('layouts.dashboard')

@section('title', 'Переписка с ' . $otherParticipant->name)

@section('content')

<!-- Page Header / Breadcrumb -->
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-3">
        <a href="{{ route('messages.index') }}" class="hover:text-blue-600 transition-colors">Сообщения</a>
        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-700 truncate">{{ $otherParticipant->name }}</span>
    </div>

    <!-- Conversation Header -->
    <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
            @if($otherParticipant->avatar)
                <img src="{{ asset('storage/' . $otherParticipant->avatar) }}"
                     alt="{{ $otherParticipant->name }}"
                     class="w-full h-full rounded-full object-cover">
            @else
                <span class="text-blue-700 font-bold text-lg">
                    {{ substr($otherParticipant->name, 0, 1) }}
                </span>
            @endif
        </div>
        <div>
            <h1 class="text-xl font-bold text-gray-900">{{ $otherParticipant->name }}</h1>
            @if($conversation->listing)
                <a href="{{ route('listings.show', $conversation->listing->slug) }}"
                   class="text-sm text-blue-600 hover:text-blue-700 transition-colors">
                    {{ $conversation->listing->title }}
                </a>
            @endif
        </div>
    </div>
</div>

<!-- Messages Thread -->
<div class="bg-white rounded-xl border border-gray-100 overflow-hidden flex flex-col"
     style="min-height: 500px;">

    <!-- Messages Container -->
    <div class="flex-1 p-6 space-y-4 overflow-y-auto" id="messages-container">

        @if($messages->count())

            <!-- Older messages pagination -->
            @if($messages->hasMorePages())
                <div class="text-center">
                    <a href="{{ route('messages.show', $conversation) }}?page={{ $messages->currentPage() + 1 }}"
                       class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                        Загрузить более ранние сообщения
                    </a>
                </div>
            @endif

            @foreach($messages->reverse() as $message)
                @php
                    $isOwn = $message->sender_id === auth()->id();
                @endphp

                <div class="flex {{ $isOwn ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[75%] {{ $isOwn ? 'order-2' : 'order-1' }}">

                        @if(!$isOwn)
                            <div class="flex items-end gap-2 mb-1">
                                <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                    <span class="text-blue-700 font-semibold" style="font-size: 10px;">
                                        {{ substr($otherParticipant->name, 0, 1) }}
                                    </span>
                                </div>
                                <span class="text-xs text-gray-400">{{ $otherParticipant->name }}</span>
                            </div>
                        @endif

                        <div class="{{ $isOwn
                            ? 'bg-blue-600 text-white rounded-2xl rounded-br-md'
                            : 'bg-gray-100 text-gray-900 rounded-2xl rounded-bl-md'
                        }} px-4 py-3">
                            <p class="text-sm leading-relaxed whitespace-pre-wrap">{{ $message->body }}</p>
                        </div>

                        <p class="text-xs text-gray-400 mt-1 {{ $isOwn ? 'text-right' : 'text-left' }}">
                            {{ $message->created_at->format('d.m.Y H:i') }}
                        </p>
                    </div>
                </div>
            @endforeach

        @else

            <div class="flex items-center justify-center h-48">
                <div class="text-center">
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-3 3v-3z"/>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-500">Начните переписку</p>
                </div>
            </div>

        @endif
    </div>

    <!-- Reply Form -->
    <div class="border-t border-gray-100 p-4 bg-gray-50/50">
        <form action="{{ route('messages.reply', $conversation) }}"
              method="POST"
              class="flex items-end gap-3">
            @csrf

            <div class="flex-1">
                <textarea name="body"
                          rows="3"
                          required
                          placeholder="Напишите сообщение..."
                          class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 resize-none bg-white @error('body') border-red-400 @enderror">{{ old('body') }}</textarea>
                @error('body')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="flex-shrink-0 inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-3 rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                Отправить
            </button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Scroll to bottom of messages on load
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('messages-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    });
</script>
@endpush
