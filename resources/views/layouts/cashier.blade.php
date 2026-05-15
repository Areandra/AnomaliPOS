<!DOCTYPE html>
<html lang="id" x-data :class="{ 'dark': $store.theme.isDark }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AnomaliPOS') | AnoPos</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                isDark: localStorage.getItem('theme') === 'dark',
                toggle() {
                    this.isDark = !this.isDark;
                    localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
                }
            });
        });
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>tailwind.config = { darkMode: 'class' }</script>
</head>
<body class="h-dvh flex overflow-hidden flex-col transition-colors duration-500 bg-[#FDFBF7] dark:bg-slate-950 text-gray-900 dark:text-gray-100">
    
    <header :class="$store.theme.isDark ? 'bg-slate-900/70 border-white/5' : 'bg-white/70 border-gray-200/50'"
            class="fixed top-0 left-0 w-full h-16 z-50 flex items-center justify-between px-6 backdrop-blur-xl border-b duration-300">
        
        <div class="flex items-center gap-2">
            <div class="-ml-5 flex">
                <button onclick="window.history.back()" class="p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-white/5"><i data-lucide="chevron-left" class="w-5 h-5"></i></button>
                <button onclick="window.history.forward()" class="p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-white/5"><i data-lucide="chevron-right" class="w-5 h-5"></i></button>
            </div>
            <div class="flex flex-col">
                <span class="font-black text-xl tracking-tighter uppercase dark:text-white">Ano<span class="text-orange-600 dark:text-amber-500">Pos</span></span>
                <span class="text-[9px] font-black uppercase tracking-[0.2em] opacity-50">@yield('cashier_title')</span>
            </div>
        </div>

        <div class="flex items-center gap-3">
            @yield('header_links')
            
            {{-- <button @click="$store.theme.toggle()" class="p-2.5 rounded-xl bg-gray-100 dark:bg-slate-800 text-orange-600 dark:text-amber-500">
                <i data-lucide="sun" class="w-4 h-4" x-show="$store.theme.isDark"></i>
                <i data-lucide="moon" class="w-4 h-4" x-show="!$store.theme.isDark"></i>
            </button> --}}
        </div>
    </header>

    {{-- GANTI $slot MENJADI yield --}}
    <main class="flex-1 h-full relative pt-16">
        @yield('content')
    </main>

    <script>window.addEventListener('DOMContentLoaded', () => lucide.createIcons());</script>
</body>
</html>