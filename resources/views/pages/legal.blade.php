@extends('layouts.app')

@section('title', 'Реквизиты и юридическая поддержка')
@section('meta_description', 'Юридические реквизиты BizHub.by и условия сопровождения сделок.')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold text-slate-900 mb-6">Реквизиты и юридическая поддержка</h1>

    <div class="bg-white rounded-xl border border-slate-100 p-6 space-y-4">
        <div>
            <h2 class="font-semibold text-slate-900">Юридическое название</h2>
            <p class="text-slate-600">{{ config('bizhub.platform_contacts.legal_name') }}</p>
        </div>
        <div>
            <h2 class="font-semibold text-slate-900">УНП</h2>
            <p class="text-slate-600">{{ config('bizhub.platform_contacts.unp') }}</p>
        </div>
        <div>
            <h2 class="font-semibold text-slate-900">Адрес</h2>
            <p class="text-slate-600">{{ config('bizhub.platform_contacts.address') }}</p>
        </div>
        <div>
            <h2 class="font-semibold text-slate-900">Email</h2>
            <p class="text-slate-600">{{ config('bizhub.platform_contacts.email') }}</p>
        </div>
        @if(config('bizhub.platform_contacts.phone'))
        <div>
            <h2 class="font-semibold text-slate-900">Телефон</h2>
            <p class="text-slate-600">{{ config('bizhub.platform_contacts.phone') }}</p>
        </div>
        @endif
        <div>
            <h2 class="font-semibold text-slate-900">Условия юридической поддержки</h2>
            <p class="text-slate-600">{{ config('bizhub.platform_contacts.legal_support_terms') }}</p>
        </div>
    </div>
</div>
@endsection
