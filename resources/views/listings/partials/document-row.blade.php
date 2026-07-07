@php
/** @var \App\Models\ListingDocument $document */
@endphp

<li>
    <a href="{{ $document->url }}"
       target="_blank"
       download="{{ $document->original_name }}"
       class="flex items-center gap-3 p-3 rounded-lg border border-slate-100 hover:border-blue-200 hover:bg-primary-50 transition-colors group">
        <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-slate-900 group-hover:text-primary-700 truncate">{{ $document->original_name }}</p>
            <p class="text-xs text-slate-400">{{ $document->getTypeLabel() }}</p>
        </div>
        <svg class="w-4 h-4 text-slate-400 group-hover:text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
        </svg>
    </a>
</li>
