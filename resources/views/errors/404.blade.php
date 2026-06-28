@extends('layouts.app')

@section('title', 'Страница не найдена')
@section('meta_description', 'Запрашиваемая страница не найдена. Вернитесь на главную или воспользуйтесь поиском по объявлениям.')
@section('meta_robots', 'noindex, follow')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
    <div class="max-w-xl mx-auto">
        <h1 class="text-8xl font-black text-slate-200 mb-4">404</h1>
        <h2 class="text-2xl font-bold text-slate-900 mb-4">Страница не найдена</h2>
        <p class="text-slate-500 mb-8">
            Возможно, страница была удалена или вы перешли по неверной ссылке.
            Проверьте адрес или воспользуйтесь поиском.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('home') }}"
               class="bg-primary-600 hover:bg-primary-700 text-white font-semibold px-8 py-3 rounded-xl transition-colors">
                На главную
            </a>
            <a href="{{ route('listings.index') }}"
               class="border border-slate-200 hover:bg-slate-50 text-slate-700 font-medium px-8 py-3 rounded-xl transition-colors">
                Каталог объявлений
            </a>
        </div>
    </div>
</div>
@endsection
