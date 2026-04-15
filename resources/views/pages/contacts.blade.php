@extends('layouts.app')

@section('title', 'Контакты')
@section('meta_description', 'Свяжитесь с командой BizHub.by. Адрес, email, телефон и форма обратной связи.')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <!-- Page Header -->
    <div class="text-center mb-12">
        <h1 class="text-3xl font-bold text-gray-900 mb-3">Контакты</h1>
        <p class="text-gray-500 text-lg max-w-xl mx-auto">
            Есть вопросы или предложения? Мы всегда готовы помочь
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Contact Info -->
        <div class="lg:col-span-1 space-y-5">

            <!-- Address -->
            <div class="bg-white rounded-xl border border-gray-100 p-6">
                <div class="flex items-start gap-4">
                    <div class="w-11 h-11 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Адрес</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            220000, Беларусь<br>
                            г. Минск, пр-т Независимости
                        </p>
                    </div>
                </div>
            </div>

            <!-- Email -->
            <div class="bg-white rounded-xl border border-gray-100 p-6">
                <div class="flex items-start gap-4">
                    <div class="w-11 h-11 bg-green-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Email</h3>
                        <a href="mailto:info@bizhub.by"
                           class="text-sm text-blue-600 hover:text-blue-700 transition-colors font-medium">
                            info@bizhub.by
                        </a>
                        <p class="text-xs text-gray-400 mt-1">Ответим в течение 24 часов</p>
                    </div>
                </div>
            </div>

            <!-- Phone -->
            <div class="bg-white rounded-xl border border-gray-100 p-6">
                <div class="flex items-start gap-4">
                    <div class="w-11 h-11 bg-purple-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Телефон</h3>
                        <a href="tel:+375291234567"
                           class="text-sm text-blue-600 hover:text-blue-700 transition-colors font-medium">
                            +375 (29) 123-45-67
                        </a>
                        <p class="text-xs text-gray-400 mt-1">Пн–Пт, 9:00 – 18:00</p>
                    </div>
                </div>
            </div>

            <!-- Telegram -->
            <div class="bg-white rounded-xl border border-gray-100 p-6">
                <div class="flex items-start gap-4">
                    <div class="w-11 h-11 bg-sky-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-sky-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.248l-2.04 9.61c-.148.67-.546.834-1.107.518l-3.063-2.257-1.478 1.422c-.164.163-.3.3-.616.3l.22-3.11 5.66-5.11c.245-.22-.054-.34-.382-.12L7.17 14.05l-3.03-.947c-.66-.206-.673-.66.138-.977l11.843-4.567c.55-.2 1.031.13.84.69z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Telegram</h3>
                        <a href="https://t.me/bizhubby"
                           target="_blank" rel="noopener noreferrer"
                           class="text-sm text-blue-600 hover:text-blue-700 transition-colors font-medium">
                            @bizhubby
                        </a>
                        <p class="text-xs text-gray-400 mt-1">Быстрые ответы на вопросы</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Contact Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-100 p-6 sm:p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-2">Написать нам</h2>
                <p class="text-gray-500 text-sm mb-6">Заполните форму, и мы свяжемся с вами в ближайшее время</p>

                <form action="#" method="POST" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- Name -->
                        <div>
                            <label for="contact_name" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Ваше имя <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="contact_name"
                                   name="name"
                                   placeholder="Иван Иванов"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 transition-shadow">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email"
                                   id="contact_email"
                                   name="email"
                                   placeholder="ivan@example.com"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 transition-shadow">
                        </div>
                    </div>

                    <!-- Subject -->
                    <div>
                        <label for="contact_subject" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Тема
                        </label>
                        <input type="text"
                               id="contact_subject"
                               name="subject"
                               placeholder="Кратко опишите вопрос"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 transition-shadow">
                    </div>

                    <!-- Message -->
                    <div>
                        <label for="contact_message" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Сообщение <span class="text-red-500">*</span>
                        </label>
                        <textarea id="contact_message"
                                  name="message"
                                  rows="6"
                                  placeholder="Подробно опишите ваш вопрос или предложение..."
                                  class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 resize-y transition-shadow"></textarea>
                    </div>

                    <!-- Privacy Notice -->
                    <p class="text-xs text-gray-400">
                        Нажимая «Отправить», вы соглашаетесь с
                        <a href="{{ route('page.show', 'privacy') }}" class="text-blue-600 hover:underline">
                            политикой конфиденциальности
                        </a>.
                    </p>

                    <div class="flex items-center justify-end">
                        <button type="submit"
                                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-7 py-3 rounded-xl transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Отправить
                        </button>
                    </div>
                </form>
            </div>

            <!-- Map placeholder -->
            <div class="mt-5 bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="h-56 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                    <div class="text-center">
                        <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <p class="text-sm text-gray-400">Минск, Беларусь</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
