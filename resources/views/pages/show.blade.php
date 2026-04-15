@extends('layouts.app')

@section('title', $page->meta_title ?? $page->title)
@section('meta_description', $page->meta_description ?? Str::limit(strip_tags($page->body ?? ''), 160))

@section('content')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <article class="bg-white rounded-xl border border-gray-100 overflow-hidden">
        <!-- Page Header -->
        <div class="px-6 sm:px-10 pt-10 pb-6 border-b border-gray-100">
            <h1 class="text-3xl font-bold text-gray-900">{{ $page->title }}</h1>
        </div>

        <!-- Page Body -->
        <div class="px-6 sm:px-10 py-8">
            <div class="prose max-w-none text-gray-700 leading-relaxed
                        [&>h2]:text-xl [&>h2]:font-bold [&>h2]:text-gray-900 [&>h2]:mt-8 [&>h2]:mb-4
                        [&>h3]:text-lg [&>h3]:font-semibold [&>h3]:text-gray-900 [&>h3]:mt-6 [&>h3]:mb-3
                        [&>p]:mb-4 [&>p]:text-gray-700 [&>p]:leading-relaxed
                        [&>ul]:mb-4 [&>ul]:list-disc [&>ul]:pl-5 [&>ul>li]:mb-1.5
                        [&>ol]:mb-4 [&>ol]:list-decimal [&>ol]:pl-5 [&>ol>li]:mb-1.5
                        [&>blockquote]:border-l-4 [&>blockquote]:border-blue-300 [&>blockquote]:pl-4 [&>blockquote]:italic [&>blockquote]:text-gray-600 [&>blockquote]:my-6
                        [&>a]:text-blue-600 [&>a]:underline [&>a]:hover:text-blue-700
                        [&>hr]:border-gray-200 [&>hr]:my-6
                        [&>table]:w-full [&>table]:border-collapse [&>table>thead>tr>th]:text-left [&>table>thead>tr>th]:font-semibold [&>table>thead>tr>th]:text-gray-900 [&>table>thead>tr>th]:pb-2 [&>table>thead>tr>th]:border-b [&>table>thead>tr>th]:border-gray-200
                        [&>table>tbody>tr>td]:py-2 [&>table>tbody>tr>td]:border-b [&>table>tbody>tr>td]:border-gray-100">
                {!! $page->body !!}
            </div>
        </div>
    </article>

</div>

@endsection
