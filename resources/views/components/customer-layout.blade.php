@props([
    'sessionToken' => null,
    'pageTitle' => 'Menu',
])

@php
    // Logika untuk mendeteksi active menu secara backend (opsional, tapi aman sebagai fallback)
    $currentUrl = request()->url();

    $navItems = [
        [
            'name' => 'Menu',
            'url' => url("/order/session/{$sessionToken}"),
            'icon' => 'menu', // Nama icon Lucide
            'active' => request()->is("order/session/{$sessionToken}"),
        ],
        [
            'name' => 'Cart',
            'url' => url("/order/session/{$sessionToken}/cart"),
            'icon' => 'shopping-cart',
            'active' => request()->is("order/session/{$sessionToken}/cart*"),
        ],
        [
            'name' => 'Pesanan',
            'url' => url("/order/session/{$sessionToken}/order"),
            'icon' => 'clipboard-list',
            'active' => request()->is("order/session/{$sessionToken}/order*"),
        ],
    ];
@endphp

<div class="flex min-h-dvh flex-col pb-28 transition-colors duration-500">

    {{-- MAIN CONTENT AREA --}}
    <main class="w-full flex-1">
        <div class="mx-auto max-w-lg px-4 pt-4">
            {{ $slot }}
        </div>
    </main>

    {{-- FLOATING GLASS BOTTOM NAVIGATION --}}
    <div class="pointer-events-none fixed bottom-0 left-0 right-0 z-50 px-4 pb-6 pt-2">
        <nav class="pointer-events-auto mx-auto flex h-20 max-w-lg items-center justify-around rounded-[2.5rem] border shadow-2xl backdrop-blur-xl transition-all duration-500"
            :class="isDark ? 'bg-slate-900/80 border-white/10 shadow-black/40' : 'bg-white/80 border-gray-200 shadow-slate-200'">

            @foreach ($navItems as $item)
                <a href="{{ $item['url'] }}"
                    class="group relative flex h-full w-full select-none flex-col items-center justify-center">

                    {{-- ACTIVE INDICATOR DOT --}}
                    @if ($item['active'])
                        <div class="absolute -top-1 h-1.5 w-1.5 animate-pulse rounded-full"
                            :class="isDark ? 'bg-amber-500 shadow-[0_0_10px_rgba(245,158,11,0.5)]' : 'bg-orange-600'">
                        </div>
                    @endif

                    {{-- ICON & TEXT CONTAINER --}}
                    <div
                        class="{{ $item['active'] ? 'scale-110' : '' }} {{ $item['active']
                            ? 'text-orange-600 dark:text-amber-500'
                            : 'text-gray-400 hover:text-gray-700 dark:text-slate-500 dark:hover:text-slate-300' }} flex flex-col items-center justify-center transition-all duration-300">

                        {{-- Lucide Icon dengan attribute data-lucide --}}
                        <i data-lucide="{{ $item['icon'] }}"
                            class="mb-1 h-6 w-6 transition-transform group-active:scale-75"
                            style="stroke-width: {{ $item['active'] ? '3px' : '2px' }}">
                        </i>

                        <span
                            class="{{ $item['active'] ? 'opacity-100' : 'opacity-60 font-bold' }} text-[10px] font-black uppercase tracking-[0.15em] transition-all">
                            {{ $item['name'] }}
                        </span>
                    </div>
                </a>
            @endforeach
        </nav>
    </div>

    {{-- MOBILE SAFE AREA SPACING --}}
    <div class="pointer-events-none fixed bottom-0 left-0 right-0 h-4 bg-transparent"></div>
</div>
