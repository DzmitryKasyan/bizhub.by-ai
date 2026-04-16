@extends('layouts.dashboard')

@section('title', 'Профиль')

@section('content')

<!-- Page Header -->
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Профиль</h1>
    <p class="text-gray-500 text-sm mt-1">Управляйте личными данными и настройками аккаунта</p>
</div>

<!-- Validation Errors -->
@if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 mb-6">
        <p class="font-semibold text-sm mb-2">Пожалуйста, исправьте следующие ошибки:</p>
        <ul class="list-disc list-inside space-y-1 text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Left: Avatar -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl border border-gray-100 p-6 text-center">
            <div class="relative inline-block mb-4" x-data="{ preview: null }">
                <div class="w-24 h-24 rounded-full overflow-hidden bg-blue-100 mx-auto">
                    @if(auth()->user()->avatar)
                        <img id="avatarPreview"
                             src="{{ asset('storage/' . auth()->user()->avatar) }}"
                             alt="{{ auth()->user()->name }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center" id="avatarFallback">
                            <span class="text-blue-700 font-bold text-3xl">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Upload overlay -->
                <label class="absolute bottom-0 right-0 w-7 h-7 bg-blue-600 hover:bg-blue-700 text-white rounded-full flex items-center justify-center cursor-pointer transition-colors shadow-sm"
                       title="Изменить фото">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <input type="file"
                           id="avatarInput"
                           name="avatar"
                           accept="image/jpeg,image/png,image/webp"
                           form="profileForm"
                           class="sr-only"
                           onchange="previewAvatar(this)">
                </label>
            </div>

            <h2 class="font-semibold text-gray-900">{{ auth()->user()->name }}</h2>
            <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
            @if(auth()->user()->role)
                <p class="text-xs text-gray-400 mt-1">
                    @php
                    $roles = [
                        'user'         => 'Пользователь',
                        'entrepreneur' => 'Предприниматель',
                        'investor'     => 'Инвестор',
                        'broker'       => 'Брокер',
                        'admin'        => 'Администратор',
                    ];
                    @endphp
                    {{ auth()->user()->role->label() }}
                </p>
            @endif
            <p class="text-xs text-gray-400 mt-2">
                На сайте с {{ auth()->user()->created_at->format('d.m.Y') }}
            </p>
        </div>
    </div>

    <!-- Right: Forms -->
    <div class="lg:col-span-2 space-y-6">

        <!-- Personal Info Form -->
        <form id="profileForm"
              action="{{ route('profile.update') }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h2 class="font-semibold text-gray-900">Личные данные</h2>
                </div>
                <div class="p-6 space-y-5">

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Имя и фамилия <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name', auth()->user()->name) }}"
                               required
                               autocomplete="name"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('name') border-red-400 @enderror">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Email адрес <span class="text-red-500">*</span>
                        </label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email', auth()->user()->email) }}"
                               required
                               autocomplete="email"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('email') border-red-400 @enderror">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Телефон
                        </label>
                        <input type="tel"
                               id="phone"
                               name="phone"
                               value="{{ old('phone', auth()->user()->phone) }}"
                               autocomplete="tel"
                               placeholder="+375 (XX) XXX-XX-XX"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('phone') border-red-400 @enderror">
                        @error('phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Company Name -->
                    <div>
                        <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Название компании
                            <span class="text-gray-400 text-xs font-normal">(необязательно)</span>
                        </label>
                        <input type="text"
                               id="company_name"
                               name="company_name"
                               value="{{ old('company_name', auth()->user()->company_name) }}"
                               placeholder="ООО «Ваша Компания»"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                    </div>

                    <!-- Bio -->
                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-700 mb-1.5">
                            О себе
                            <span class="text-gray-400 text-xs font-normal">(необязательно)</span>
                        </label>
                        <textarea id="bio"
                                  name="bio"
                                  rows="3"
                                  maxlength="500"
                                  placeholder="Краткая информация о вас, опыт, специализация..."
                                  class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 resize-y">{{ old('bio', auth()->user()->bio) }}</textarea>
                        <p class="text-xs text-gray-400 mt-1">Максимум 500 символов</p>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors">
                            Сохранить изменения
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Change Password Form -->
        <form action="{{ route('profile.password') }}"
              method="POST">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h2 class="font-semibold text-gray-900">Изменить пароль</h2>
                </div>
                <div class="p-6 space-y-5">

                    <!-- Current Password -->
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Текущий пароль <span class="text-red-500">*</span>
                        </label>
                        <input type="password"
                               id="current_password"
                               name="current_password"
                               autocomplete="current-password"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('current_password') border-red-400 @enderror">
                        @error('current_password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Новый пароль <span class="text-red-500">*</span>
                        </label>
                        <input type="password"
                               id="new_password"
                               name="password"
                               autocomplete="new-password"
                               placeholder="Минимум 8 символов"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('password') border-red-400 @enderror">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm New Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Подтверждение нового пароля <span class="text-red-500">*</span>
                        </label>
                        <input type="password"
                               id="password_confirmation"
                               name="password_confirmation"
                               autocomplete="new-password"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="px-6 py-2.5 bg-gray-800 hover:bg-gray-900 text-white text-sm font-semibold rounded-xl transition-colors">
                            Изменить пароль
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Danger Zone -->
        <div class="bg-white rounded-xl border border-red-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-red-100 bg-red-50/50">
                <h2 class="font-semibold text-red-700">Опасная зона</h2>
            </div>
            <div class="p-6">
                <p class="text-sm text-gray-600 mb-4">
                    После удаления аккаунта все ваши данные и объявления будут безвозвратно удалены.
                </p>
                <button type="button"
                        onclick="if(confirm('Вы уверены? Это действие нельзя отменить.')) { document.getElementById('deleteAccountForm').submit(); }"
                        class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-xl transition-colors">
                    Удалить аккаунт
                </button>
                <form id="deleteAccountForm"
                      action="{{ route('profile.edit') }}"
                      method="POST"
                      class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const preview = document.getElementById('avatarPreview');
            const fallback = document.getElementById('avatarFallback');
            if (preview) {
                preview.src = e.target.result;
            } else if (fallback) {
                fallback.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover" id="avatarPreview">`;
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
