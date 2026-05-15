<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AnomaliPOS')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>[x-cloak] { display: none !important; }</style>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('notif', {
                open: false, type: 'success', title: '', message: '',
                set(type, title, message = '') {
                    this.type = type; this.title = title; this.message = message;
                    this.open = true;
                    setTimeout(() => this.open = false, 4000)
                }
            })

            Alpine.store('theme', {
                isDark: false,
                init() {
                    const stored = localStorage.getItem('theme')
                    this.isDark = stored ? stored === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches
                    document.documentElement.setAttribute('data-theme', this.isDark ? 'dark' : 'light')
                },
                toggle() {
                    this.isDark = !this.isDark
                    localStorage.setItem('theme', this.isDark ? 'dark' : 'light')
                    document.documentElement.setAttribute('data-theme', this.isDark ? 'dark' : 'light')
                }
            })

            Alpine.store('sidebar', {
                hidden: true,
                init() {
                    const stored = localStorage.getItem('hideSideBar')
                    this.hidden = stored !== null ? stored === 'true' : true
                },
                toggle() {
                    this.hidden = !this.hidden
                    localStorage.setItem('hideSideBar', String(this.hidden))
                }
            })
        })
    </script>
</head>
<body>
@php
    $currentPath = request()->path();
    $plan = session('plan', 'starter');
    $atLeastPro = in_array($plan, ['pro', 'enterprise']);

    $navItems = [
        ['name' => 'Dashboard', 'icon' => 'layout-dashboard', 'href' => '/dashboard', 'active' => str_contains($currentPath, 'dashboard')],
        ['name' => 'Menu',      'icon' => 'utensils-crossed', 'href' => '/menu',      'active' => str_contains($currentPath, 'menu')],
        ['name' => 'Shift',     'icon' => 'history',          'href' => '/shift', 'active' => str_contains($currentPath, 'shift')],
    ];
    if ($atLeastPro) {
        array_splice($navItems, 1, 0, [
            ['name' => 'Users', 'icon' => 'users', 'href' => '/users', 'active' => str_contains($currentPath, 'users')],
            ['name' => 'Table', 'icon' => 'map',   'href' => '/table', 'active' => str_contains($currentPath, 'table')],
        ]);
    }
@endphp

<div
    x-data
    x-init="$store.theme.init(); $store.sidebar.init(); $nextTick(() => lucide.createIcons())"
    :class="$store.theme.isDark ? 'bg-slate-950 text-gray-100' : 'bg-gray-50 text-gray-900'"
    class="relative h-dvh transition-colors duration-500"
>
    {{-- Notification --}}
    @include('components.notification')

    {{-- Glow --}}
    <template x-if="$store.theme.isDark">
        <div class="fixed inset-0 pointer-events-none opacity-10">
            <div class="absolute top-0 left-0 w-96 h-96 bg-indigo-600 rounded-full blur-[100px]"></div>
        </div>
    </template>

    {{-- HEADER --}}
    <header
        :class="$store.theme.isDark ? 'bg-slate-900/70 border-white/5' : 'bg-white/70 border-gray-200/50'"
        class="fixed top-0 left-0 w-full h-16 z-50 flex items-center justify-between px-4 backdrop-blur-xl border-b duration-300"
    >
        <div class="flex items-center gap-2">
            <div class="-ml-3">
                <button onclick="window.history.back()"
                    :class="$store.theme.isDark ? 'hover:bg-white/5 text-gray-400 hover:text-white' : 'hover:bg-gray-100 text-gray-600 hover:text-gray-900'"
                    class="p-2 rounded-xl duration-200 group">
                    <i data-lucide="chevron-left" class="w-5 h-5 transition-transform group-hover:-translate-x-1"></i>
                </button>
                <button onclick="window.history.forward()"
                    :class="$store.theme.isDark ? 'hover:bg-white/5 text-gray-400 hover:text-white' : 'hover:bg-gray-100 text-gray-600 hover:text-gray-900'"
                    class="p-2 rounded-xl duration-200 group">
                    <i data-lucide="chevron-right" class="w-5 h-5 transition-transform group-hover:translate-x-1"></i>
                </button>
            </div>

            <div class="flex flex-col">
                <span :class="$store.theme.isDark ? 'text-white' : 'text-slate-800'" class="font-black text-xl tracking-tighter uppercase">
                    Ano<span :class="$store.theme.isDark ? 'text-amber-500' : 'text-orange-600'">Pos</span>
                </span>
                <span class="text-[9px] font-black uppercase tracking-[0.2em] opacity-50">@yield('page_title', '')</span>
            </div>
        </div>

        <div class="flex items-center gap-3">
            @if($atLeastPro)
            <a href="/kitchen/kot"
                :class="$store.theme.isDark ? 'bg-slate-800 text-amber-500 hover:bg-slate-700 border border-white/5' : 'bg-white text-orange-600 hover:bg-orange-50 border border-gray-200'"
                class="flex items-center gap-2 px-4 py-2 rounded-full duration-300 font-black text-[10px] uppercase tracking-widest shadow-sm">
                <i data-lucide="utensils-crossed" class="w-[18px] h-[18px]"></i>
                <span class="hidden md:block">Kitchen</span>
            </a>
            @endif

            <a href="/cashier"
                :class="$store.theme.isDark ? 'bg-slate-800 text-amber-500 hover:bg-slate-700 border border-white/5' : 'bg-white text-orange-600 hover:bg-orange-50 border border-gray-200'"
                class="flex items-center gap-2 px-4 py-2 rounded-full duration-300 font-black text-[10px] uppercase tracking-widest shadow-sm">
                <i data-lucide="calculator" class="w-[18px] h-[18px]"></i>
                <span class="hidden md:block">Cashier</span>
            </a>

            <button
                x-data="{ isFull: false }"
                @click="document.documentElement.requestFullscreen(); isFull = true"
                x-show="!isFull"
                :class="$store.theme.isDark ? 'bg-slate-800 text-amber-500 hover:bg-slate-700 border border-white/5' : 'bg-white text-orange-600 hover:bg-orange-50 border border-gray-200'"
                class="flex items-center gap-2 px-4 py-2 rounded-full duration-300 font-black text-[10px] uppercase tracking-widest shadow-sm">
                <i data-lucide="fullscreen" class="w-[18px] h-[18px]"></i>
                <span class="hidden md:block">FullScreen</span>
            </button>
        </div>
    </header>

    <div class="flex pt-16 h-full">

        {{-- SIDEBAR --}}
        <aside
            :class="[
                $store.theme.isDark ? 'bg-slate-900 border-white/5' : 'bg-white border-gray-200',
                $store.sidebar.hidden ? 'w-20' : 'w-64'
            ]"
            class="relative z-40 shrink-0 h-full duration-500 border-r"
        >
            <div class="p-4 flex items-center justify-between w-full h-14 border-b border-transparent">
                <span x-show="!$store.sidebar.hidden"
                    class="text-[10px] font-black uppercase tracking-widest opacity-40 px-2">Navigation</span>
                <button @click="$store.sidebar.toggle(); $nextTick(() => lucide.createIcons())"
                    :class="[$store.theme.isDark ? 'text-gray-500 hover:bg-white/5' : 'text-gray-400 hover:bg-gray-50', $store.sidebar.hidden ? 'm-auto' : '']"
                    class="p-2 rounded-lg transition-colors">
                    <template x-if="$store.sidebar.hidden">
                        <i data-lucide="chevron-right" class="w-[18px] h-[18px]"></i>
                    </template>
                    <template x-if="!$store.sidebar.hidden">
                        <i data-lucide="chevron-left" class="w-[18px] h-[18px]"></i>
                    </template>
                </button>
            </div>

            <nav class="p-3 space-y-1.5">
                @foreach($navItems as $item)
                <a href="{{ $item['href'] }}"
                    :class="{{ $item['active'] ? 'true' : 'false' }}
                        ? ($store.theme.isDark ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-500/20' : 'bg-orange-600 text-white shadow-lg shadow-orange-600/20')
                        : ($store.theme.isDark ? 'text-gray-400 hover:bg-white/5 hover:text-white' : 'text-gray-500 hover:bg-gray-100 hover:text-slate-900')"
                    class="flex items-center p-3 rounded-full text-[11px] font-black uppercase tracking-widest duration-300">
                    <i data-lucide="{{ $item['icon'] }}"
                       :class="$store.sidebar.hidden ? 'mx-auto w-[22px] h-[22px]' : 'w-[18px] h-[18px]'"></i>
                    <span x-show="!$store.sidebar.hidden" class="ml-4">{{ $item['name'] }}</span>
                </a>
                @endforeach
            </nav>

            <div class="absolute bottom-6 space-y-4 w-full px-3">
                <a href="/me"
                    :class="{{ str_contains($currentPath, 'me') && !str_contains($currentPath, 'menu') ? 'true' : 'false' }}
                        ? ($store.theme.isDark ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-500/20' : 'bg-orange-600 text-white shadow-lg shadow-orange-600/20')
                        : ($store.theme.isDark ? 'text-gray-400 hover:bg-white/5 hover:text-white' : 'text-gray-500 hover:bg-gray-100 hover:text-slate-900')"
                    class="w-full flex items-center p-3 rounded-full transition-all">
                    <i data-lucide="user" :class="$store.sidebar.hidden ? 'mx-auto w-[22px] h-[22px]' : 'w-[18px] h-[18px]'"></i>
                    <span x-show="!$store.sidebar.hidden" class="ml-4 text-[11px] font-black uppercase tracking-widest">Profile</span>
                </a>

                <a href="/restaurant/info"
                    :class="{{ str_contains($currentPath, 'restaurant') ? 'true' : 'false' }}
                        ? ($store.theme.isDark ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-500/20' : 'bg-orange-600 text-white shadow-lg shadow-orange-600/20')
                        : ($store.theme.isDark ? 'text-gray-400 hover:bg-white/5 hover:text-white' : 'text-gray-500 hover:bg-gray-100 hover:text-slate-900')"
                    class="w-full flex items-center p-3 rounded-full transition-all">
                    <i data-lucide="settings" :class="$store.sidebar.hidden ? 'mx-auto w-[22px] h-[22px]' : 'w-[18px] h-[18px]'"></i>
                    <span x-show="!$store.sidebar.hidden" class="ml-4 text-[11px] font-black uppercase tracking-widest">Settings</span>
                </a>
            </div>
        </aside>

        {{-- MAIN --}}
        <main class="flex-1 overflow-hidden relative">
            <template x-if="!$store.theme.isDark">
                <div class="absolute inset-0 opacity-40 pointer-events-none"
                     style="background-image: radial-gradient(#e5e7eb 1px, transparent 1px); background-size: 20px 20px;"></div>
            </template>
            <div class="relative h-full overflow-y-auto">
                @yield('content')
            </div>
        </main>
    </div>

    {{-- Plan Banner --}}
    @if(!$atLeastPro)
    <div class="h-12 p-4 px-2 w-full fixed bottom-0 bg-amber-500 border border-amber-500 text-center z-50">
        <p class="text-sm text-white">✨ <strong>Manajemen Restaurant lebih dalam?</strong> Upgrade ke Pro untuk membuka lebih banyak fitur</p>
    </div>
    @endif

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => lucide.createIcons())
</script>
</body>
</html>