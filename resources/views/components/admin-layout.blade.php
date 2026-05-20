@props([
    'title' => null,
    'not_admin' => false,
])

@php
    $plan = session('restaurant_plan', 'starter');
    $atLeastPro = $plan !== 'starter';
    $currentUrl = request()->path();

    $mainMenuItems = [
        [
            'name' => 'Dashboard',
            'icon' => 'layout-dashboard',
            'href' => '/',
            'active' => $currentUrl === '/' || str_contains($currentUrl, 'dashboard'),
        ],
    ];

    if ($atLeastPro) {
        $mainMenuItems[] = [
            'name' => 'Users',
            'icon' => 'users',
            'href' => '/users',
            'active' => str_contains($currentUrl, 'users'),
        ];
        $mainMenuItems[] = [
            'name' => 'Table',
            'icon' => 'map',
            'href' => '/tables',
            'active' => str_contains($currentUrl, 'table'),
        ];
    }

    $mainMenuItems[] = [
        'name' => 'Menu',
        'icon' => 'utensils',
        'href' => '/menu/items',
        'active' => str_contains($currentUrl, 'menu'),
    ];

    $mainMenuItems[] = [
        'name' => 'Shift',
        'icon' => 'history',
        'href' => '/shifts',
        'active' => str_contains($currentUrl, 'shift') || str_contains($currentUrl, 'history'),
    ];

    $links = [];
    if ($atLeastPro) {
        $links[] = ['label' => 'Kitchen', 'href' => '/kitchen', 'icon' => 'utensils'];
    }
    $links[] = ['label' => 'Cashier', 'href' => '/cashier', 'icon' => 'calculator'];

    $isMeActive = str_contains($currentUrl, '/me') && !str_contains($currentUrl, '/menu');
    $isRestoActive = str_contains($currentUrl, '/restaurant');
@endphp

<x-app-layout :title="$title ?? 'Admin AnoPos'">
    <div x-data="{
        hide: localStorage.getItem('hideSideBar') === 'true',
        init() {
            this.$watch('hide', value => localStorage.setItem('hideSideBar', value));
        }
    }"
        class="relative h-dvh bg-gray-50 font-sans text-gray-900 transition-colors duration-500 dark:bg-slate-950 dark:text-gray-100">

        <template x-if="isDark">
            <div class="pointer-events-none fixed inset-0 opacity-10">
                <div class="absolute left-0 top-0 h-96 w-96 rounded-full bg-indigo-600 blur-[100px]"></div>
            </div>
        </template>

        <header
            class="fixed left-0 top-0 z-50 flex h-16 w-full items-center justify-between border-b border-gray-200/50 bg-white/70 px-4 backdrop-blur-xl duration-300 dark:border-white/5 dark:bg-slate-900/70">
            <div class="flex items-center gap-2">
                <div class="-ml-3 flex">
                    <button onclick="window.history.back()"
                        class="group rounded-xl p-2 text-gray-600 duration-200 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-white">
                        <x-lucide-chevron-left class="h-5 w-5 transition-transform group-hover:translate-x-1" />
                    </button>
                    <button onclick="window.history.forward()"
                        class="group rounded-xl p-2 text-gray-600 duration-200 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-white">
                        <x-lucide-chevron-right class="h-5 w-5 transition-transform group-hover:-translate-x-1" />
                    </button>
                </div>

                <div class="flex flex-col">
                    <span class="text-xl font-black uppercase tracking-tighter text-slate-800 dark:text-white">
                        Anomali<span class="text-orange-600 dark:text-amber-500">POS</span>
                    </span>
                    @if (isset($page_title) || isset($title))
                        <span
                            class="text-[9px] font-black uppercase tracking-[0.2em] opacity-50">{{ isset($page_title) ? $page_title : $title }}</span>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-3">
                @foreach ($links as $link)
                    <a href="{{ $link['href'] }}"
                        class="flex items-center gap-2 rounded-full border border-gray-200 bg-white px-4 py-2 text-[10px] font-black uppercase tracking-widest text-orange-600 shadow-sm duration-300 hover:bg-orange-50 dark:border-white/5 dark:bg-slate-800 dark:text-amber-500 dark:hover:bg-slate-700">
                        <x-dynamic-component :component="'lucide-' . $link['icon']" class="h-[18px] w-[18px]" />
                        <span class="hidden md:block">{{ $link['label'] }}</span>
                    </a>
                @endforeach

                {{-- Memanggil logic toggleFullscreen dari master layout langsung --}}
                <button x-show="!isFullscreen" @click="toggleFullscreen()"
                    class="flex items-center gap-2 rounded-full border border-gray-200 bg-white px-4 py-2 text-[10px] font-black uppercase tracking-widest text-orange-600 shadow-sm duration-300 hover:bg-orange-50 dark:border-white/5 dark:bg-slate-800 dark:text-amber-500 dark:hover:bg-slate-700">
                    <x-lucide-fullscreen class="h-[18px] w-[18px]" />
                    <span class="hidden md:block">FullScreen</span>
                </button>
            </div>
        </header>

        <div class="flex h-full pt-16">
            <aside :x-if={{ !$not_admin }} :class="hide ? 'w-20' : 'w-64'"
                class="relative z-40 h-full shrink-0 border-r border-gray-200 bg-white duration-500 dark:border-white/5 dark:bg-slate-900">
                <div class="flex h-14 w-full items-center justify-between border-b border-transparent p-4">
                    <span x-show="!hide"
                        class="px-2 text-[10px] font-black uppercase tracking-widest opacity-40">Navigation</span>
                    <button @click="hide = !hide" :class="hide ? 'm-auto' : ''"
                        class="rounded-lg p-2 text-gray-400 transition-colors hover:bg-gray-50 dark:text-gray-500 dark:hover:bg-white/5">
                        <template x-if="hide"><x-lucide-chevron-right class="h-[18px] w-[18px]" /></template>
                        <template x-if="!hide"><x-lucide-chevron-left class="h-[18px] w-[18px]" /></template>
                    </button>
                </div>

                <nav class="space-y-1.5 p-3">
                    @foreach ($mainMenuItems as $item)
                        <a href="{{ $item['href'] }}"
                            class="{{ $item['active'] ? 'bg-orange-600 text-white shadow-lg shadow-orange-600/20 dark:bg-amber-500 dark:text-slate-950 dark:shadow-amber-500/20' : 'text-gray-500 hover:bg-gray-100 hover:text-slate-900 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-white' }} flex items-center rounded-full p-3 text-[11px] font-black uppercase tracking-widest duration-300">
                            <x-dynamic-component :component="'lucide-' . $item['icon']"
                                x-bind:class="hide ? 'mx-auto w-[22px] h-[22px]' : 'w-[18px] h-[18px]'" />
                            <span x-show="!hide" class="ml-4">{{ $item['name'] }}</span>
                        </a>
                    @endforeach
                </nav>

                <div class="absolute bottom-6 w-full space-y-4 px-3">
                    <a href="/me"
                        class="{{ $isMeActive ? 'bg-orange-600 text-white shadow-lg shadow-orange-600/20 dark:bg-amber-500 dark:text-slate-950 dark:shadow-amber-500/20' : 'text-gray-500 hover:bg-gray-100 hover:text-slate-900 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-white' }} flex w-full items-center rounded-full p-3 transition-all">
                        <x-lucide-user x-bind:class="hide ? 'mx-auto w-[22px] h-[22px]' : 'w-[18px] h-[18px]'" />
                        <span x-show="!hide"
                            class="ml-4 text-[11px] font-black uppercase tracking-widest">Profile</span>
                    </a>

                    {{-- <a href="/restaurant/info"
                        class="{{ $isRestoActive ? 'bg-orange-600 text-white shadow-lg shadow-orange-600/20 dark:bg-amber-500 dark:text-slate-950 dark:shadow-amber-500/20' : 'text-gray-500 hover:bg-gray-100 hover:text-slate-900 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-white' }} flex w-full items-center rounded-full p-3 transition-all">
                        <x-lucide-settings x-bind:class="hide ? 'mx-auto w-[22px] h-[22px]' : 'w-[18px] h-[18px]'" />
                        <span x-show="!hide"
                            class="ml-4 text-[11px] font-black uppercase tracking-widest">Settings</span>
                    </a> --}}
                </div>
            </aside>

            <main class="relative flex-1 overflow-hidden">
                <div
                    class="pointer-events-none absolute inset-0 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] bg-[size:20px_20px] opacity-40 dark:hidden">
                </div>
                <div class="no-scrollbar relative h-full overflow-y-auto">
                    {{ $slot }}
                </div>
            </main>

            @if (!$atLeastPro)
                <div class="fixed bottom-0 z-50 h-12 w-full border border-amber-500 bg-amber-500 p-4 px-2 text-center">
                    <p class="text-sm text-white">
                        ✨ <strong>Manajemen Restaurant lebih dalam?</strong> Upgrade ke Pro untuk membuka lebih banyak
                        fitur
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
