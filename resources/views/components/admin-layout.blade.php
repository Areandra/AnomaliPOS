@props([
    'title' => null,
    'theme' => 'light'
])

@php
    $plan       = session('restaurant_plan', 'starter');
    $atLeastPro = $plan !== 'starter';
    $currentUrl = request()->path();

    $mainMenuItems = [
        [
            'name'   => 'Dashboard',
            'icon'   => 'layout-dashboard',
            'href'   => '/dashboard',
            'active' => $currentUrl === '/' || str_contains($currentUrl, 'dashboard'),
        ],
    ];

    if ($atLeastPro) {
        $mainMenuItems[] = [
            'name'   => 'Users',
            'icon'   => 'users',
            'href'   => '/users',
            'active' => str_contains($currentUrl, 'users'),
        ];
        $mainMenuItems[] = [
            'name'   => 'Table',
            'icon'   => 'map',
            'href'   => '/table',
            'active' => str_contains($currentUrl, 'table'),
        ];
    }

    $mainMenuItems[] = [
        'name'   => 'Menu',
        'icon'   => 'utensils',
        'href'   => '/menu/items',
        'active' => str_contains($currentUrl, 'menu'),
    ];

    $mainMenuItems[] = [
        'name'   => 'Shift',
        'icon'   => 'history',
        'href'   => '/shifts',
        'active' => str_contains($currentUrl, 'shift') || str_contains($currentUrl, 'history'),
    ];

    // 2. Data Links Header
    $links = [];
    if ($atLeastPro) {
        $links[] = ['label' => 'Kitchen', 'href' => '/kitchen/kot', 'icon' => 'utensils'];
    }
    $links[] = ['label' => 'Cashier', 'href' => '/cashier', 'icon' => 'calculator'];

    // Cek status menu setting bawah
    $isMeActive = str_contains($currentUrl, '/me') && !str_contains($currentUrl, '/menu');
    $isRestoActive = str_contains($currentUrl, '/restaurant');
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'AnoPos' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{ $head ?? '' }}
</head>
<body class="antialiased">

    <div x-data="adminLayoutData({ theme: '{{ $theme }}' })"
         :class="{ 'dark': isDark }"
         class="relative h-dvh transition-colors duration-500 bg-gray-50 text-gray-900 dark:bg-slate-950 dark:text-gray-100 font-sans">

        <template x-if="isDark">
            <div class="fixed inset-0 pointer-events-none opacity-10">
                <div class="absolute top-0 left-0 w-96 h-96 bg-indigo-600 rounded-full blur-[100px]"></div>
            </div>
        </template>

        <header class="fixed top-0 left-0 w-full h-16 z-50 flex items-center justify-between px-4 backdrop-blur-xl border-b duration-300 bg-white/70 border-gray-200/50 dark:bg-slate-900/70 dark:border-white/5">
            <div class="flex items-center gap-2">
                <div class="-ml-3 flex">
                    <button onclick="window.history.back()" class="p-2 rounded-xl duration-200 group hover:bg-gray-100 text-gray-600 hover:text-gray-900 dark:hover:bg-white/5 dark:text-gray-400 dark:hover:text-white">
                        <x-lucide-chevron-left class="w-5 h-5 transition-transform group-hover:translate-x-1" />
                    </button>
                    <button onclick="window.history.forward()" class="p-2 rounded-xl duration-200 group hover:bg-gray-100 text-gray-600 hover:text-gray-900 dark:hover:bg-white/5 dark:text-gray-400 dark:hover:text-white">
                        <x-lucide-chevron-right class="w-5 h-5 transition-transform group-hover:-translate-x-1" />
                    </button>
                </div>

                <div class="flex flex-col">
                    <span class="font-black text-xl tracking-tighter uppercase text-slate-800 dark:text-white">
                        Ano<span class="text-orange-600 dark:text-amber-500">Pos</span>
                    </span>
                    @if($title)
                        <span class="text-[9px] font-black uppercase tracking-[0.2em] opacity-50">
                            {{ $title }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-3">
                @foreach($links as $link)
                    <a href="{{ $link['href'] }}" class="flex items-center gap-2 px-4 py-2 rounded-full duration-300 font-black text-[10px] uppercase tracking-widest shadow-sm bg-white text-orange-600 hover:bg-orange-50 border border-gray-200 dark:bg-slate-800 dark:text-amber-500 dark:hover:bg-slate-700 dark:border-white/5">
                        <x-dynamic-component :component="'lucide-' . $link['icon']" class="w-[18px] h-[18px]" />
                        <span class="hidden md:block">{{ $link['label'] }}</span>
                    </a>
                @endforeach

                <button x-show="!isFullscreen" @click="toggleFullscreen()" class="flex items-center gap-2 px-4 py-2 rounded-full duration-300 font-black text-[10px] uppercase tracking-widest shadow-sm bg-white text-orange-600 hover:bg-orange-50 border border-gray-200 dark:bg-slate-800 dark:text-amber-500 dark:hover:bg-slate-700 dark:border-white/5">
                    <x-lucide-fullscreen class="w-[18px] h-[18px]" />
                    <span class="hidden md:block">FullScreen</span>
                </button>
            </div>
        </header>

        <div class="flex pt-16 h-full">
            <aside :class="hide ? 'w-20' : 'w-64'" class="relative z-40 shrink-0 h-full duration-500 border-r bg-white border-gray-200 dark:bg-slate-900 dark:border-white/5">
                <div class="p-4 flex items-center justify-between w-full h-14 border-b border-transparent">
                    <span x-show="!hide" class="text-[10px] font-black uppercase tracking-widest opacity-40 px-2">
                        Navigation
                    </span>
                    <button @click="hide = !hide" :class="hide ? 'm-auto' : ''" class="p-2 rounded-lg transition-colors text-gray-400 hover:bg-gray-50 dark:text-gray-500 dark:hover:bg-white/5">
                        <template x-if="hide">
                            <x-lucide-chevron-right class="w-[18px] h-[18px]" />
                        </template>
                        <template x-if="!hide">
                            <x-lucide-chevron-left class="w-[18px] h-[18px]" />
                        </template>
                    </button>
                </div>

                <nav class="p-3 space-y-1.5">
                    @foreach($mainMenuItems as $item)
                        <a href="{{ $item['href'] }}" class="flex items-center p-3 rounded-full text-[11px] font-black uppercase tracking-widest duration-300 {{ $item['active'] ? 'bg-orange-600 text-white shadow-lg shadow-orange-600/20 dark:bg-amber-500 dark:text-slate-950 dark:shadow-amber-500/20' : 'text-gray-500 hover:bg-gray-100 hover:text-slate-900 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-white' }}">
                            <x-dynamic-component :component="'lucide-' . $item['icon']" x-bind:class="hide ? 'mx-auto w-[22px] h-[22px]' : 'w-[18px] h-[18px]'" />
                            <span x-show="!hide" class="ml-4">{{ $item['name'] }}</span>
                        </a>
                    @endforeach
                </nav>

                <div class="absolute bottom-6 space-y-4 w-full px-3">
                    <a href="/me" class="w-full flex items-center p-3 rounded-full transition-all {{ $isMeActive ? 'bg-orange-600 text-white shadow-lg shadow-orange-600/20 dark:bg-amber-500 dark:text-slate-950 dark:shadow-amber-500/20' : 'text-gray-500 hover:bg-gray-100 hover:text-slate-900 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-white' }}">
                        <x-lucide-user x-bind:class="hide ? 'mx-auto w-[22px] h-[22px]' : 'w-[18px] h-[18px]'" />
                        <span x-show="!hide" class="ml-4 text-[11px] font-black uppercase tracking-widest">Settings</span>
                    </a>

                    <a href="/restaurant/info" class="w-full flex items-center p-3 rounded-full transition-all {{ $isRestoActive ? 'bg-orange-600 text-white shadow-lg shadow-orange-600/20 dark:bg-amber-500 dark:text-slate-950 dark:shadow-amber-500/20' : 'text-gray-500 hover:bg-gray-100 hover:text-slate-900 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-white' }}">
                        <x-lucide-settings x-bind:class="hide ? 'mx-auto w-[22px] h-[22px]' : 'w-[18px] h-[18px]'" />
                        <span x-show="!hide" class="ml-4 text-[11px] font-black uppercase tracking-widest">Settings</span>
                    </a>
                </div>
            </aside>

            <main class="flex-1 overflow-hidden relative">
                <div class="absolute inset-0 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] bg-[size:20px_20px] opacity-40 pointer-events-none dark:hidden"></div>

                <div class="relative h-full overflow-y-auto no-scrollbar">
                    {{ $slot }}
                </div>
            </main>

            @if(!$atLeastPro)
                <div class="h-12 p-4 px-2 w-full fixed bottom-0 bg-amber-500 border border-amber-500 text-center z-50">
                    <p class="text-sm text-white">
                        ✨ <strong>Manajemen Restaurant lebih dalam?</strong> Upgrade ke Pro untuk membuka lebih banyak fitur
                    </p>
                </div>
            @endif
        </div>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('adminLayoutData', (config) => ({
                    isDark: config.theme === 'dark',
                    hide: true,
                    isFullscreen: false,

                    init() {
                        const savedTheme = localStorage.getItem('theme');
                        // Jika ada, pakai tema yang disimpan
                        if (savedTheme) {
                            this.isDark = savedTheme === 'dark';
                        } else {
                            this.isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                        }
                        window.addEventListener('toggle-theme', () => {
                            this.isDark = !this.isDark;
                            // SIMPAN ke LocalStorage biar pas refresh nggak ilang
                            localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
                        });


                        // Sinkronisasi preferensi sidebar
                        const hideSideBar = localStorage.getItem('hideSideBar');
                        if (hideSideBar !== null) {
                            this.hide = hideSideBar === 'true';
                        }
                        this.$watch('hide', value => localStorage.setItem('hideSideBar', value));
                    },

                    toggleFullscreen() {
                        if (!document.fullscreenElement) {
                            document.documentElement.requestFullscreen().catch(err => {
                                console.error(`Error attempting to enable fullscreen: ${err.message}`);
                            });
                            this.isFullscreen = true;
                        } else {
                            if (document.exitFullscreen) {
                                document.exitFullscreen();
                                this.isFullscreen = false;
                            }
                        }
                    }
                }));
            });
        </script>
    </div>

</body>
</html>
