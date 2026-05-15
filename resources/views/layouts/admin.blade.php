<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AnoPos Admin')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .theme-transition { transition: background-color 0.5s ease, border-color 0.5s ease, color 0.5s ease; }
    </style>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                isDark: localStorage.getItem('theme') === 'dark',
                init() {
                    document.documentElement.setAttribute('data-theme', this.isDark ? 'dark' : 'light');
                },
                toggle() {
                    this.isDark = !this.isDark;
                    localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
                    document.documentElement.setAttribute('data-theme', this.isDark ? 'dark' : 'light');
                }
            });
        });
    </script>
</head>

<body x-data x-init="$store.theme.init()"
    :class="$store.theme.isDark ? 'bg-slate-950 text-gray-100' : 'bg-gray-50 text-gray-900'"
    class="antialiased theme-transition">

    <div class="relative h-screen flex flex-col overflow-hidden">

        {{-- Glow Effect --}}
        <template x-if="$store.theme.isDark">
            <div class="fixed inset-0 pointer-events-none opacity-10">
                <div class="absolute top-0 left-0 w-96 h-96 bg-indigo-600 rounded-full blur-[100px]"></div>
            </div>
        </template>

        {{-- HEADER --}}
        <header :class="$store.theme.isDark ? 'bg-slate-900/70 border-white/5' : 'bg-white/70 border-gray-200/50'"
            class="h-16 flex items-center justify-between px-6 backdrop-blur-xl border-b z-50 theme-transition">

            <div class="flex items-center gap-4">
                <div class="flex flex-col">
                    <span class="font-black text-xl tracking-tighter uppercase leading-none">
                        Ano<span :class="$store.theme.isDark ? 'text-amber-500' : 'text-orange-600'">Pos</span>
                    </span>
                    <span class="text-[9px] font-black uppercase tracking-[0.2em] opacity-50 mt-1">
                        @yield('page_subtitle', 'Management System')
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <a href="/kitchen/kot"
                        :class="$store.theme.isDark ? 'bg-slate-800 text-amber-500 border-white/5 hover:bg-slate-700' : 'bg-white text-orange-600 border-gray-200 hover:bg-orange-50'"
                        class="flex items-center gap-2 px-4 py-2 rounded-full border duration-300 font-black text-[10px] uppercase tracking-widest shadow-sm">
                        <i data-lucide="utensils-crossed" class="w-4 h-4"></i>
                        <span class="hidden md:block">Kitchen</span>
                    </a>

                    <a href="/cashier"
                        :class="$store.theme.isDark ? 'bg-slate-800 text-amber-500 border-white/5 hover:bg-slate-700' : 'bg-white text-orange-600 border-gray-200 hover:bg-orange-50'"
                        class="flex items-center gap-2 px-4 py-2 rounded-full border duration-300 font-black text-[10px] uppercase tracking-widest shadow-sm">
                        <i data-lucide="calculator" class="w-4 h-4"></i>
                        <span class="hidden md:block">Cashier</span>
                    </a>

                    <button @click="document.documentElement.requestFullscreen()"
                        :class="$store.theme.isDark ? 'bg-slate-800 text-amber-500 border-white/5 hover:bg-slate-700' : 'bg-white text-orange-600 border-gray-200 hover:bg-orange-50'"
                        class="flex items-center gap-2 px-4 py-2 rounded-full border duration-300 font-black text-[10px] uppercase tracking-widest shadow-sm">
                        <i data-lucide="maximize" class="w-4 h-4"></i>
                        <span class="hidden md:block">FullScreen</span>
                    </button>
                </div>
            </div>
        </header>

        <div class="flex flex-1 overflow-hidden">
            {{-- SIDEBAR --}}
            <aside :class="$store.theme.isDark ? 'bg-slate-950 border-white/5' : 'bg-white border-gray-200'"
                class="w-20 lg:w-64 border-r transition-all duration-500 flex flex-col theme-transition shrink-0">
                
                {{-- Label Navigation sesuai Gambar --}}
                <div class="h-16 flex items-center justify-between px-6 border-b dark:border-white/5 shrink-0">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] opacity-30 dark:text-white">Navigation</span>
                    <i data-lucide="chevron-left" class="w-4 h-4 opacity-30"></i>
                </div>

                <nav class="flex-1 p-4 space-y-2 overflow-y-auto scrollbar-hide">
                    @php
                        $navItems = [
                            ['name' => 'Dashboard', 'icon' => 'layout-grid', 'url' => '/dashboard'],
                            ['name' => 'Users',     'icon' => 'users',       'url' => '/users'],
                            ['name' => 'Table',     'icon' => 'map',         'url' => '/tables'],
                            ['name' => 'Menu',      'icon' => 'utensils',    'url' => '/menu'],
                            ['name' => 'Shift',     'icon' => 'history',     'url' => '/shift'],
                        ];
                    @endphp

                    @foreach($navItems as $item)
                        @php $isActive = request()->is(trim($item['url'], '/') . '*'); @endphp
                        <a href="{{ $item['url'] }}"
                            :class="$store.theme.isDark 
                                ? ('{{ $isActive }}' ? 'bg-amber-500 text-slate-950 shadow-[0_0_20px_rgba(245,158,11,0.3)]' : 'text-gray-400 hover:bg-white/5 hover:text-white')
                                : ('{{ $isActive }}' ? 'bg-amber-500 text-white shadow-lg' : 'text-gray-500 hover:bg-gray-100')"
                            class="flex items-center p-3 rounded-full transition-all duration-300 group">
                            <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5 lg:w-4 lg:h-4 transition-transform group-hover:scale-110"></i>
                            <span class="ml-4 hidden lg:block text-[11px] font-black uppercase tracking-widest">{{ $item['name'] }}</span>
                        </a>
                    @endforeach
                </nav>

                {{-- Bagian Bawah: Settings sesuai Gambar --}}
                <div class="p-4 border-t dark:border-white/5 space-y-2 mt-auto shrink-0">
                    <a href="/me"
                        :class="$store.theme.isDark ? 'text-gray-400 hover:bg-white/5 hover:text-white' : 'text-gray-500 hover:bg-gray-100'"
                        class="flex items-center p-3 rounded-full transition-all duration-300">
                        <i data-lucide="user" class="w-5 h-5 lg:w-4 lg:h-4"></i>
                        <span class="ml-4 hidden lg:block text-[11px] font-black uppercase tracking-widest">Settings</span>
                    </a>

                    <a href="/settings"
                        :class="$store.theme.isDark ? 'text-gray-400 hover:bg-white/5 hover:text-white' : 'text-gray-500 hover:bg-gray-100'"
                        class="flex items-center p-3 rounded-full transition-all duration-300">
                        <i data-lucide="settings" class="w-5 h-5 lg:w-4 lg:h-4"></i>
                        <span class="ml-4 hidden lg:block text-[11px] font-black uppercase tracking-widest">Settings</span>
                    </a>
                </div>
            </aside>

            {{-- MAIN CONTENT --}}
            <main class="flex-1 overflow-y-auto relative scrollbar-hide">
                <template x-if="!$store.theme.isDark">
                    <div class="absolute inset-0 opacity-40 pointer-events-none"
                        style="background-image: radial-gradient(#e5e7eb 1px, transparent 1px); background-size: 20px 20px;">
                    </div>
                </template>

                <div class="relative z-10">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
</body>
</html>