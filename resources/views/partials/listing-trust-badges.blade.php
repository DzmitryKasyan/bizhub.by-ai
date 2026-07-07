@php
$badgeColors = [
    'blue' => 'bg-blue-50 text-blue-700 border-blue-100',
    'indigo' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
    'emerald' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
    'amber' => 'bg-amber-50 text-amber-700 border-amber-100',
    'purple' => 'bg-purple-50 text-purple-700 border-purple-100',
];

$badges = $badges ?? [];
@endphp

@if(count($badges))
    <div class="flex flex-wrap gap-2 {{ $class ?? '' }}">
        @foreach($badges as $badge)
            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-medium border {{ $badgeColors[$badge['color']] ?? 'bg-slate-50 text-slate-600 border-slate-100' }}"
                  title="{{ $badge['label'] }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $badge['short_label'] }}
            </span>
        @endforeach
    </div>
@endif
