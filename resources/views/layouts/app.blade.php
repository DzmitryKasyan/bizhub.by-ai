<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'BizHub.by — платформа для покупки и продажи бизнеса в Беларуси. Актуальные объявления, инвестиции, франшизы.')">
    <meta name="keywords" content="@yield('meta_keywords', 'купить бизнес, продать бизнес, инвестиции, франшиза, Беларусь')">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'BizHub.by') — Платформа для покупки и продажи бизнеса в Беларуси</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50:  '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Inter font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @livewireStyles

    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-900 antialiased font-sans" x-data="{ mobileMenuOpen: false }">

    <!-- Navigation -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-sm">B</span>
                        </div>
                        <span class="text-xl font-bold text-blue-600">BizHub<span class="text-gray-900">.by</span></span>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('listings.index') }}"
                       class="text-gray-600 hover:text-blue-600 font-medium transition-colors text-sm {{ request()->routeIs('listings.index') ? 'text-blue-600' : '' }}">
                        Каталог
                    </a>
                    <a href="{{ route('sell-business') }}"
                       class="text-gray-600 hover:text-blue-600 font-medium transition-colors text-sm {{ request()->routeIs('sell-business') ? 'text-blue-600' : '' }}">
                        Продать бизнес
                    </a>
                    <a href="{{ route('investments') }}"
                       class="text-gray-600 hover:text-blue-600 font-medium transition-colors text-sm {{ request()->routeIs('investments') ? 'text-blue-600' : '' }}">
                        Инвестиции
                    </a>
                    <a href="{{ route('franchises') }}"
                       class="text-gray-600 hover:text-blue-600 font-medium transition-colors text-sm {{ request()->routeIs('franchises') ? 'text-blue-600' : '' }}">
                        Франшизы
                    </a>
                </div>

                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center gap-3">
                    @guest
                        <a href="{{ route('login') }}"
                           class="text-gray-600 hover:text-blue-600 font-medium text-sm transition-colors">
                            Войти
                        </a>
                        <a href="{{ route('register') }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                            Регистрация
                        </a>
                    @else
                        <div class="relative" id="user-menu-wrap">
                            <button onclick="toggleUserMenu()"
                                    id="user-menu-btn"
                                    class="flex items-center gap-2 text-gray-700 hover:text-blue-600 font-medium text-sm transition-colors focus:outline-none">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-blue-700 font-semibold text-sm">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                                <span>{{ auth()->user()->name }}</span>
                                <svg id="user-menu-arrow" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div id="user-menu-dropdown"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50"
                                 style="display:none;">
                                <a href="{{ route('dashboard') }}"
                                   class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h7v7H3zM14 3h7v5h-7zM14 12h7v9h-7zM3 18h7v3H3z"/>
                                    </svg>
                                    Личный кабинет
                                </a>
                                <a href="{{ route('my-listings.index') }}"
                                   class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    Мои объявления
                                </a>
                                <a href="{{ route('profile.edit') }}"
                                   class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Профиль
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 text-left">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Выйти
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>

                <!-- Mobile menu button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="md:hidden p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" style="display:none;"/>
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenuOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="md:hidden border-t border-gray-100 py-3 space-y-1"
                 style="display: none;">
                <a href="{{ route('listings.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-blue-600 rounded-lg font-medium text-sm">Каталог</a>
                <a href="{{ route('sell-business') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-blue-600 rounded-lg font-medium text-sm">Продать бизнес</a>
                <a href="{{ route('investments') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-blue-600 rounded-lg font-medium text-sm">Инвестиции</a>
                <a href="{{ route('franchises') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-blue-600 rounded-lg font-medium text-sm">Франшизы</a>
                <div class="border-t border-gray-100 pt-3 mt-3 flex flex-col gap-2 px-4">
                    @guest
                        <a href="{{ route('login') }}" class="block text-center py-2 text-gray-700 border border-gray-300 rounded-lg font-medium text-sm hover:bg-gray-50">Войти</a>
                        <a href="{{ route('register') }}" class="block text-center py-2 bg-blue-600 text-white rounded-lg font-medium text-sm hover:bg-blue-700">Регистрация</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="block py-2 text-gray-700 hover:text-blue-600 font-medium text-sm">Личный кабинет</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left py-2 text-red-600 font-medium text-sm">Выйти</button>
                        </form>
                    @endguest
                </div>
            </div>
        </nav>
    </header>

    <!-- Flash Messages -->
    @if(session('success'))
        <div x-data="{ show: true }"
             x-show="show"
             x-transition
             x-init="setTimeout(() => show = false, 5000)"
             class="fixed top-20 right-4 z-50 max-w-sm bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 shadow-lg flex items-start gap-3">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm font-medium">{{ session('success') }}</p>
            <button @click="show = false" class="ml-auto text-green-600 hover:text-green-800">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }"
             x-show="show"
             x-transition
             x-init="setTimeout(() => show = false, 7000)"
             class="fixed top-20 right-4 z-50 max-w-sm bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 shadow-lg flex items-start gap-3">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm font-medium">{{ session('error') }}</p>
            <button @click="show = false" class="ml-auto text-red-600 hover:text-red-800">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

                <!-- Brand -->
                <div class="md:col-span-1">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-sm">B</span>
                        </div>
                        <span class="text-xl font-bold text-white">BizHub<span class="text-blue-400">.by</span></span>
                    </div>
                    <p class="text-sm text-gray-400 leading-relaxed">
                        Платформа для покупки и продажи бизнеса в Беларуси. Тысячи актуальных предложений.
                    </p>
                </div>

                <!-- Catalog -->
                <div>
                    <h3 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Каталог</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('listings.index') }}" class="hover:text-white transition-colors">Все объявления</a></li>
                        <li><a href="{{ route('sell-business') }}" class="hover:text-white transition-colors">Продажа бизнеса</a></li>
                        <li><a href="{{ route('investments') }}" class="hover:text-white transition-colors">Инвестиции</a></li>
                        <li><a href="{{ route('franchises') }}" class="hover:text-white transition-colors">Франшизы</a></li>
                    </ul>
                </div>

                <!-- For Users -->
                <div>
                    <h3 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Пользователям</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Регистрация</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Войти</a></li>
                        @auth
                            <li><a href="{{ route('dashboard') }}" class="hover:text-white transition-colors">Личный кабинет</a></li>
                            <li><a href="{{ route('my-listings.create') }}" class="hover:text-white transition-colors">Подать объявление</a></li>
                        @endauth
                    </ul>
                </div>

                <!-- Info -->
                <div>
                    <h3 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Информация</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">О сервисе</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Правила пользования</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Политика конфиденциальности</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Контакты</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-sm text-gray-500">© {{ date('Y') }} BizHub.by. Все права защищены.</p>
                <p class="text-sm text-gray-500">Беларусь, г. Минск</p>
            </div>
        </div>
    </footer>

    @livewireScripts

    @stack('scripts')

    <script>
    function toggleUserMenu() {
        var menu = document.getElementById('user-menu-dropdown');
        var arrow = document.getElementById('user-menu-arrow');
        var isOpen = menu.style.display !== 'none';
        if (isOpen) {
            menu.style.display = 'none';
            arrow.style.transform = '';
        } else {
            menu.style.display = 'block';
            arrow.style.transform = 'rotate(180deg)';
        }
    }
    document.addEventListener('click', function(e) {
        var wrap = document.getElementById('user-menu-wrap');
        var menu = document.getElementById('user-menu-dropdown');
        if (wrap && menu && !wrap.contains(e.target)) {
            menu.style.display = 'none';
            var arrow = document.getElementById('user-menu-arrow');
            if (arrow) arrow.style.transform = '';
        }
    });
    </script>
</body>
</html>
