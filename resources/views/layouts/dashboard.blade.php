<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Личный кабинет') — BizHub.by</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Inter font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    @livewireStyles

    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-900 antialiased font-sans" x-data="{ sidebarOpen: false }">

    <!-- Top Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40">
        <div class="flex items-center justify-between h-16 px-4 sm:px-6">

            <div class="flex items-center gap-4">
                <!-- Mobile sidebar toggle -->
                <button @click="sidebarOpen = !sidebarOpen"
                        class="lg:hidden p-2 text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-accent-500 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-sm">B</span>
                    </div>
                    <span class="text-lg font-bold bg-gradient-to-r from-primary-700 to-accent-600 bg-clip-text text-transparent">BizHub<span class="text-slate-700">.by</span></span>
                </a>
            </div>

            <div class="flex items-center gap-4">
                <!-- Back to site -->
                <a href="{{ route('listings.index') }}"
                   class="hidden sm:flex items-center gap-1.5 text-sm text-slate-500 hover:text-primary-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    На сайт
                </a>

                <!-- User Dropdown -->
                <div class="relative" id="user-menu-wrap">
                    <button onclick="toggleUserMenu()"
                            class="flex items-center gap-2 text-slate-700 hover:text-primary-600 font-medium text-sm transition-colors px-3 py-2 rounded-lg hover:bg-primary-50/50">
                        <span class="truncate max-w-[140px]">{{ Auth::user()->name }}</span>
                        <svg id="user-menu-arrow" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="user-menu-dropdown"
                         class="absolute right-0 top-full mt-2 w-56 bg-white rounded-xl shadow-xl border border-slate-100 py-1.5 z-50"
                         style="display: none;">
                        <a href="{{ route('profile.edit') }}"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 font-medium transition-colors">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Профиль
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
            </div>
        </div>
    </header>

    <div class="flex">
        <!-- Sidebar -->
        <aside class="hidden lg:flex lg:flex-col lg:w-64 fixed top-16 bottom-0 bg-white border-r border-slate-200 overflow-y-auto"
               :class="sidebarOpen ? '!flex !fixed !inset-y-16 !left-0 !z-30 !w-64' : ''"
               x-show="sidebarOpen || window.innerWidth >= 1024" style="display: none;">

            <nav class="flex-1 py-6 px-4 space-y-1">

                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors
                          {{ request()->routeIs('dashboard') ? 'bg-primary-50 text-primary-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Дашборд
                </a>

                <div class="border-t border-slate-100 my-2 pt-2">
                    <p class="text-xs text-slate-400 uppercase tracking-wider px-3 mb-3 font-semibold">Объявления</p>
                </div>

                <!-- My Listings -->
                <a href="{{ route('my-listings.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors
                          {{ request()->routeIs('my-listings.*') ? 'bg-primary-50 text-primary-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Мои объявления
                </a>

                <!-- Add Listing -->
                <a href="{{ route('my-listings.create') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors
                          text-slate-600 hover:bg-slate-50 hover:text-slate-900 {{ request()->routeIs('my-listings.create') ? 'bg-primary-50 text-primary-700' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Подать объявление
                </a>

                <div class="border-t border-slate-100 my-2 pt-2">
                    <p class="text-xs text-slate-400 uppercase tracking-wider px-3 mb-3 font-semibold">Профиль</p>
                </div>

                <!-- Favorites -->
                <a href="{{ route('favorites.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors
                          {{ request()->routeIs('favorites.index') ? 'bg-primary-50 text-primary-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    Избранное
                </a>

                <!-- Messages -->
                <a href="{{ route('messages.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors
                          {{ request()->routeIs('messages.index') || request()->routeIs('messages.show') ? 'bg-primary-50 text-primary-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-3 3v-3z"/>
                    </svg>
                    Сообщения
                </a>

                <!-- Profile -->
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors
                          {{ request()->routeIs('profile.edit') ? 'bg-primary-50 text-primary-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Настройки
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 lg:ml-64 min-h-[calc(100vh-4rem)]">
            <div class="p-6 sm:p-8">
                @yield('content')
            </div>
        </main>
    </div>

    @livewireScripts

    @stack('scripts')

    <script>
    function toggleUserMenu() {
        var menu = document.getElementById('user-menu-dropdown');
        var arrow = document.getElementById('user-menu-arrow');
        var isOpen = menu.style.display !== 'none';
        if (isOpen) { menu.style.display = 'none'; arrow.style.transform = ''; }
        else { menu.style.display = 'block'; arrow.style.transform = 'rotate(180deg)'; }
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
