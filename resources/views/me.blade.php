@extends('layouts.cashier', [
    'title' => 'My Profile',
    'links' => [
        ['label' => 'Manajemen', 'href' => '#', 'icon' => 'settings'],
        ['label' => 'Kitchen', 'href' => '#', 'icon' => 'utensils-cross-lines'],
        ['label' => 'Cashier', 'href' => '#', 'icon' => 'calculator'],
    ]
])

@section('header_links')
    @php
        $links = [
            ['label' => 'MANAGEMENT', 'href' => '/menu', 'icon' => 'settings'],
            ['label' => 'KITCHEN', 'href' => '/kitchen/kot', 'icon' => 'utensils-crossed'],
            ['label' => 'CASHIER', 'href' => '/cashier/order', 'icon' => 'calculator'],
        ];
    @endphp

    <div class="flex items-center gap-3" x-data="{ 
        isFullscreen: false,
        toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
                this.isFullscreen = true;
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                    this.isFullscreen = false;
                }
            }
        }
    }" @fullscreenchange.window="isFullscreen = !!document.fullscreenElement">
        
        {{-- Tombol Navigasi Utama --}}
        @foreach($links as $link)
            <a href="{{ $link['href'] }}"
               class="flex items-center gap-3 px-6 py-2.5 rounded-full duration-300 font-black text-[11px] uppercase tracking-wider transition-all"
               :class="$store.theme.isDark 
                    ? 'bg-slate-800/40 text-amber-500 hover:bg-slate-800/60 border border-white/5' 
                    : 'bg-white text-orange-600 hover:bg-orange-50 border border-gray-200'">
                <i data-lucide="{{ $link['icon'] }}" class="w-4 h-4 text-amber-500"></i>
                <span class="hidden md:block">{{ $link['label'] }}</span>
            </a>
        @endforeach

        {{-- Tombol Fullscreen --}}
        <button @click="toggleFullscreen()"
                class="flex items-center gap-3 px-6 py-2.5 rounded-full duration-300 font-black text-[11px] uppercase tracking-wider transition-all"
                :class="$store.theme.isDark 
                    ? 'bg-slate-800/40 text-amber-500 hover:bg-slate-800/60 border border-white/5' 
                    : 'bg-white text-orange-600 hover:bg-orange-50 border border-gray-200'">
            <i data-lucide="maximize" class="w-4 h-4 text-amber-500"></i>
            <span class="hidden md:block" x-text="isFullscreen ? 'EXIT FULLSCREEN' : 'FULLSCREEN'"></span>
        </button>
    </div>
@endsection

@section('content')
<div class="h-screen pb-16 overflow-hidden transition-colors duration-500 bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-white">
    <div class="fixed inset-0 pointer-events-none opacity-20 hidden dark:block">
        <div class="absolute top-0 left-0 w-125 h-125 bg-purple-900 rounded-full blur-[120px] mix-blend-screen"></div>
        <div class="absolute bottom-0 right-0 w-125 h-125 bg-blue-900 rounded-full blur-[120px] mix-blend-screen"></div>
    </div>

    <main class="h-full overflow-auto w-full p-6 lg:p-12 relative z-10">
        <div class="relative overflow-hidden p-8 lg:p-12 rounded-[3rem] border mb-8 transition-all bg-white border-slate-200 shadow-xl dark:bg-slate-900 dark:border-white/5 dark:shadow-2xl">
            <div class="relative z-10 flex flex-col md:flex-row items-center gap-8">
                <div class="relative">
                    <div class="w-32 h-32 rounded-[2.5rem] p-1.5 border-2 border-orange-500/50 dark:border-amber-500/50">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=f59e0b&color=fff&size=128" class="w-full h-full object-cover rounded-[2rem]">
                    </div>
                    <div class="absolute -bottom-2 -right-2 p-2.5 bg-amber-500 text-slate-950 rounded-2xl shadow-lg cursor-pointer">
                        <i data-lucide="camera" class="w-4 h-4"></i>
                    </div>
                </div>

                <div class="flex-1 text-center md:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/20 mb-3">
                        <span class="text-[10px] font-black uppercase tracking-widest text-amber-500">{{ $user->role }}</span>
                    </div>
                    <h1 class="text-4xl font-black tracking-tighter uppercase mb-1">{{ $user->name }}</h1>
                    <div class="flex items-center justify-center md:justify-start gap-2 opacity-50 text-sm font-bold italic">
                        <i data-lucide="mail" class="w-3.5 h-3.5"></i>
                        <span>{{ $user->email }}</span>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button @click="$dispatch('notify', {title: 'Berhasil', message: 'Link ubah password dikirim!', type: 'success'})" 
                            class="px-6 py-4 rounded-[1.5rem] flex items-center gap-3 font-black text-xs uppercase tracking-widest transition-all bg-rose-50 text-rose-600 border border-rose-100 hover:bg-rose-600 hover:text-white dark:bg-rose-500/10 dark:text-rose-500 dark:border-rose-500/20">
                        <i data-lucide="lock" class="w-5 h-5 text-amber-500"></i> Change Password
                    </button>
                    <form action="#" method="POST">
                        <button class="px-6 py-4 rounded-[1.5rem] flex items-center gap-3 font-black text-xs uppercase tracking-widest transition-all bg-rose-50 text-rose-600 border border-rose-100 hover:bg-rose-600 hover:text-white dark:bg-rose-500/10 dark:text-rose-500 dark:border-rose-500/20">
                            <i data-lucide="log-out" class="w-4.5 h-4.5"></i> Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            @php
                $avgPerf = $shifts->count() > 0 ? round(($shifts->filter(fn($i) => $i->cashVariance >= 0)->count() / $shifts->count()) * 100) : 0;
                $stats = [
                    ['label' => 'Shift Completed', 'value' => $shifts->count(), 'icon' => 'clock', 'color' => 'text-blue-500'],
                    ['label' => 'Avg Performance', 'value' => $avgPerf . '%', 'icon' => 'target', 'color' => 'text-emerald-500'],
                    ['label' => 'Access Level', 'value' => strtoupper($user->role), 'icon' => 'shield', 'color' => 'text-amber-500'],
                ];
            @endphp

            @foreach($stats as $stat)
            <div class="p-6 rounded-[2rem] border bg-white border-slate-200 dark:bg-slate-900/50 dark:border-white/5">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 rounded-2xl bg-slate-50 dark:bg-white/5 {{ $stat['color'] }}">
                        <i data-lucide="{{ $stat['icon'] }}" class="w-5 h-5"></i>
                    </div>
                </div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-500 mb-1">{{ $stat['label'] }}</p>
                <p class="text-2xl font-black tracking-tighter">{{ $stat['value'] }}</p>
            </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="p-8 rounded-[2.5rem] border bg-white border-slate-200 dark:bg-slate-900/50 dark:border-white/5">
                <h3 class="text-sm font-black uppercase tracking-widest mb-8 flex items-center gap-3 text-slate-900 dark:text-white">
                    <i data-lucide="layout-grid" class="w-4.5 h-4.5 text-amber-500"></i> Account Details
                </h3>
                <div class="space-y-6">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-white/5">
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-500">Employee ID</span>
                        <span class="text-sm font-bold font-mono">#USR-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-white/5">
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-500">Status</span>
                        <div class="flex items-center gap-2 text-emerald-500">
                            <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                            <span class="text-sm font-bold uppercase">{{ $user->status }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-8 rounded-[2.5rem] border bg-white border-slate-200 dark:bg-slate-900/50 dark:border-white/5">
                <h3 class="text-sm font-black uppercase tracking-widest mb-8 flex items-center gap-3 text-slate-900 dark:text-white">
                    <i data-lucide="clock" class="w-4.5 h-4.5 text-amber-500"></i> Quick Actions
                </h3>
                <div class="space-y-3">
                    @foreach(['Attendance History', 'Performance Report', 'Shift Schedules'] as $item)
                    <a href="#" class="w-full p-4 rounded-2xl border flex items-center justify-between group transition-all bg-slate-50 border-slate-100 hover:bg-white hover:shadow-md dark:bg-white/5 dark:border-white/5 dark:hover:bg-white/10">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-900 dark:text-gray-300">{{ $item }}</p>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-amber-500 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </main>
</div>
@endsection