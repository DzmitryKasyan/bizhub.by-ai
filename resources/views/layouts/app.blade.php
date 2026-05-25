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
                            50:  '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        },
                        accent: {
                            400: '#f472b6',
                            500: '#ec4899',
                            600: '#db2777',
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'float-delayed': 'float 6s ease-in-out 3s infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'shimmer': 'shimmer 2s linear infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                        shimmer: {
                            '0%': { backgroundPosition: '-1000px 0' },
                            '100%': { backgroundPosition: '1000px 0' },
                        },
                    },
                },
            },
        }
    </script>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Inter font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    @livewireStyles

    @stack('styles')

    <style>
        [wire\:loading][wire\:loading], [wire\:loading\.delay][wire\:loading\.delay], [wire\:loading\.inline-block][wire\:loading\.inline-block], [wire\:loading\.inline][wire\:loading\.inline], [wire\:loading\.block][wire\:loading\.block], [wire\:loading\.flex][wire\:loading\.flex], [wire\:loading\.table][wire\:loading\.table], [wire\:loading\.grid][wire\:loading\.grid], [wire\:loading\.inline-flex][wire\:loading\.inline-flex] {display: none;}[wire\:loading\.delay\.none][wire\:loading\.delay\.none], [wire\:loading\.delay\.shortest][wire\:loading\.delay\.shortest], [wire\:loading\.delay\.shorter][wire\:loading\.delay\.shorter], [wire\:loading\.delay\.short][wire\:loading\.delay\.short], [wire\:loading\.delay\.default][wire\:loading\.delay\.default], [wire\:loading\.delay\.long][wire\:loading\.delay\.long], [wire\:loading\.delay\.longer][wire\:loading\.delay\.longer], [wire\:loading\.delay\.longest][wire\:loading\.delay\.longest] {display: none;}[wire\:offline][wire\:offline] {display: none;}[wire\:dirty]:not(textarea):not(input):not(select) {display: none;}:root {--livewire-progress-bar-color: #6366f1;}[x-cloak] {display: none !important;}[wire\:cloak] {display: none !important;}dialog#livewire-error::backdrop {background-color: rgba(0, 0, 0, .6);}
    </style>

    <style>
        .glass{background:rgba(255,255,255,.7);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.3)}
        .glass-dark{background:rgba(15,23,42,.6);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.1)}
        .hero-grid{background-image:linear-gradient(rgba(99,102,241,.03) 1px,transparent 1px),linear-gradient(90deg,rgba(99,102,241,.03) 1px,transparent 1px);background-size:60px 60px}
        .glow-orb{position:absolute;border-radius:50%;filter:blur(80px);opacity:.4;pointer-events:none}
        .card-3d{transition:transform .3s ease,box-shadow .3s ease}
        .card-3d:hover{transform:translateY(-8px) rotateX(2deg);box-shadow:0 25px 50px -12px rgba(99,102,241,.25)}
        .btn-shine{position:relative;overflow:hidden}
        .btn-shine::after{content:'';position:absolute;top:0;left:-100%;width:100%;height:100%;background:linear-gradient(90deg,transparent,rgba(255,255,255,.3),transparent);transition:left .5s}
        .btn-shine:hover::after{left:100%}
    </style>

    @if(app()->environment('production'))
        @include('partials.google-analytics')
    @endif
</head>
<body class="bg-slate-50 text-slate-900 antialiased font-sans overflow-x-hidden" x-data="{ mobileMenuOpen: false }">

    @if(app()->environment('production'))
        @include('partials.yandex-metrika')
    @endif

    <!-- Navigation — Glass morphism -->
    <header class="fixed w-full top-0 z-50 transition-all duration-300" id="navbar">
        <nav class="glass max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 rounded-2xl shadow-lg shadow-primary-500/5">
            <div class="flex items-center justify-between h-16">

                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                        <div class="w-9 h-9 bg-gradient-to-br from-primary-500 to-accent-500 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/30 group-hover:shadow-primary-500/50 transition-shadow">
                            <span class="text-white font-bold text-sm">B</span>
                        </div>
                        <span class="text-xl font-bold bg-gradient-to-r from-primary-700 to-accent-600 bg-clip-text text-transparent">BizHub<span class="text-slate-700">.by</span></span>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('listings.index') }}"
                       class="text-slate-600 hover:text-primary-600 font-medium transition-colors text-sm px-3 py-2 rounded-lg hover:bg-primary-50/50 {{ request()->routeIs('listings.index') ? 'text-primary-600 bg-primary-50/50' : '' }}">
                        Каталог
                    </a>
                    <a href="{{ route('sell-business') }}"
                       class="text-slate-600 hover:text-primary-600 font-medium transition-colors text-sm px-3 py-2 rounded-lg hover:bg-primary-50/50 {{ request()->routeIs('sell-business') ? 'text-primary-600 bg-primary-50/50' : '' }}">
                        Продать бизнес
                    </a>
                    <a href="{{ route('investments') }}"
                       class="text-slate-600 hover:text-primary-600 font-medium transition-colors text-sm px-3 py-2 rounded-lg hover:bg-primary-50/50 {{ request()->routeIs('investments') ? 'text-primary-600 bg-primary-50/50' : '' }}">
                        Инвестиции
                    </a>
                    <a href="{{ route('franchises') }}"
                       class="text-slate-600 hover:text-primary-600 font-medium transition-colors text-sm px-3 py-2 rounded-lg hover:bg-primary-50/50 {{ request()->routeIs('franchises') ? 'text-primary-600 bg-primary-50/50' : '' }}">
                        Франшизы
                    </a>
                </div>

                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center gap-3">
                    @guest
                        <a href="{{ route('login') }}"
                           class="text-slate-600 hover:text-primary-600 font-medium text-sm transition-colors px-4 py-2">
                            Войти
                        </a>
                        <a href="{{ route('register') }}"
                           class="bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all shadow-lg shadow-primary-500/25 hover:shadow-primary-500/40 btn-shine">
                            Регистрация
                        </a>
                    @else
                        <div class="relative" id="user-menu-wrap">
                            <button onclick="toggleUserMenu()"
                                    class="flex items-center gap-2 text-slate-700 hover:text-primary-600 font-medium text-sm transition-colors px-3 py-2 rounded-lg hover:bg-primary-50/50">
                                <span class="truncate max-w-[120px]">{{ Auth::user()->name }}</span>
                                <svg id="user-menu-arrow" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div id="user-menu-dropdown"
                                 class="absolute right-0 top-full mt-2 w-56 bg-white rounded-xl shadow-xl border border-slate-100 py-1.5 z-50"
                                 style="display: none;">
                                <a href="{{ route('dashboard') }}"
                                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 font-medium transition-colors">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Личный кабинет
                                </a>
                                <a href="{{ route('my-listings.index') }}"
                                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 font-medium transition-colors">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    Мои объявления
                                </a>
                                <a href="{{ route('my-listings.create') }}"
                                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 font-medium transition-colors">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Подать объявление
                                </a>
                                <div class="border-t border-slate-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 font-medium transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Выйти
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>

                <!-- Mobile menu button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="md:hidden p-2 rounded-xl text-slate-500 hover:text-slate-700 hover:bg-slate-100 focus:outline-none transition-colors">
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
                 class="md:hidden border-t border-slate-200/50 py-3 space-y-1"
                 style="display: none;">
                <a href="{{ route('listings.index') }}" class="block px-4 py-2 text-slate-700 hover:bg-slate-50 hover:text-primary-600 rounded-lg font-medium text-sm">Каталог</a>
                <a href="{{ route('sell-business') }}" class="block px-4 py-2 text-slate-700 hover:bg-slate-50 hover:text-primary-600 rounded-lg font-medium text-sm">Продать бизнес</a>
                <a href="{{ route('investments') }}" class="block px-4 py-2 text-slate-700 hover:bg-slate-50 hover:text-primary-600 rounded-lg font-medium text-sm">Инвестиции</a>
                <a href="{{ route('franchises') }}" class="block px-4 py-2 text-slate-700 hover:bg-slate-50 hover:text-primary-600 rounded-lg font-medium text-sm">Франшизы</a>
                <div class="border-t border-slate-200/50 pt-3 mt-3 flex flex-col gap-2 px-4">
                    @guest
                        <a href="{{ route('login') }}" class="block text-center py-2 text-slate-700 border border-slate-300 rounded-lg font-medium text-sm hover:bg-slate-50">Войти</a>
                        <a href="{{ route('register') }}" class="block text-center py-2 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-lg font-medium text-sm hover:from-primary-700 hover:to-primary-800">Регистрация</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="block py-2 text-slate-700 hover:text-primary-600 font-medium text-sm">Личный кабинет</a>
                        <a href="{{ route('my-listings.index') }}" class="block py-2 text-slate-700 hover:text-primary-600 font-medium text-sm">Мои объявления</a>
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
             class="fixed top-24 right-4 z-50 max-w-sm bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-4 py-3 shadow-lg flex items-start gap-3">
            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm font-medium">{{ session('success') }}</p>
            <button @click="show = false" class="ml-auto text-emerald-600 hover:text-emerald-800">
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
             class="fixed top-24 right-4 z-50 max-w-sm bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 shadow-lg flex items-start gap-3">
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
    <main class="pt-24">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-t from-primary-900/20 to-transparent pointer-events-none"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

                <!-- Brand -->
                <div class="md:col-span-1">
                    <div class="flex items-center gap-2.5 mb-4">
                        <div class="w-9 h-9 bg-gradient-to-br from-primary-500 to-accent-500 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/20">
                            <span class="text-white font-bold text-sm">B</span>
                        </div>
                        <span class="text-xl font-bold text-white">BizHub<span class="text-primary-400">.by</span></span>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        Платформа для покупки и продажи бизнеса в Беларуси. Тысячи актуальных предложений.
                    </p>
                </div>

                <!-- Catalog -->
                <div>
                    <h3 class="text-white font-bold mb-5 text-sm uppercase tracking-wider">Каталог</h3>
                    <ul class="space-y-3 text-sm">
                        <li><a href="{{ route('listings.index') }}" class="hover:text-primary-400 transition-colors">Все объявления</a></li>
                        <li><a href="{{ route('sell-business') }}" class="hover:text-primary-400 transition-colors">Продажа бизнеса</a></li>
                        <li><a href="{{ route('investments') }}" class="hover:text-primary-400 transition-colors">Инвестиции</a></li>
                        <li><a href="{{ route('franchises') }}" class="hover:text-primary-400 transition-colors">Франшизы</a></li>
                    </ul>
                </div>

                <!-- For Users -->
                <div>
                    <h3 class="text-white font-bold mb-5 text-sm uppercase tracking-wider">Пользователям</h3>
                    <ul class="space-y-3 text-sm">
                        <li><a href="{{ route('register') }}" class="hover:text-primary-400 transition-colors">Регистрация</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-primary-400 transition-colors">Войти</a></li>
                        @auth
                            <li><a href="{{ route('dashboard') }}" class="hover:text-primary-400 transition-colors">Личный кабинет</a></li>
                            <li><a href="{{ route('my-listings.create') }}" class="hover:text-primary-400 transition-colors">Подать объявление</a></li>
                        @endauth
                    </ul>
                </div>

                <!-- Info -->
                <div>
                    <h3 class="text-white font-bold mb-5 text-sm uppercase tracking-wider">Информация</h3>
                    <ul class="space-y-3 text-sm">
                        <li><a href="{{ route('article.show', 'about') }}" class="hover:text-primary-400 transition-colors">О сервисе</a></li>
                        <li><a href="{{ route('article.show', 'terms') }}" class="hover:text-primary-400 transition-colors">Правила пользования</a></li>
                        <li><a href="{{ route('article.show', 'privacy') }}" class="hover:text-primary-400 transition-colors">Политика конфиденциальности</a></li>
                        <li><a href="{{ route('contacts') }}" class="hover:text-primary-400 transition-colors">Контакты</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-800 mt-12 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-sm text-slate-600">© {{ date('Y') }} BizHub.by. Все права защищены.</p>
                <p class="text-sm text-slate-600 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Беларусь, г. Минск
                </p>
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

    // Navbar scroll effect
    window.addEventListener('scroll', function() {
        const navbar = document.getElementById('navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('py-0');
            navbar.classList.remove('py-2');
        } else {
            navbar.classList.add('py-2');
            navbar.classList.remove('py-0');
        }
    });
    </script>
</body>
</html>
