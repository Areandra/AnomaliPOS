@extends('layouts.app')

@section('title', 'Bill Closed')

@section('content')

<div
    x-data="{ count: 5 }"
    x-init="
        $store.theme.init();
        const timer = setInterval(() => {
            count--;
            if (count <= 0) {
                clearInterval(timer);
                window.location.href = '/order/session/{{ $sessionToken }}';
            }
        }, 1000)
    "
    :class="$store.theme.isDark ? 'bg-slate-950 text-white' : 'bg-[#FDFBF7] text-slate-900'"
    class="min-h-screen flex items-center justify-center p-6 transition-colors duration-500"
>
    {{-- BACKGROUND DECORATIVE --}}
    <template x-if="$store.theme.isDark">
        <div class="fixed inset-0 pointer-events-none opacity-20">
            <div class="absolute top-0 left-0 w-125 h-125 bg-purple-900 rounded-full blur-[120px] mix-blend-screen"></div>
            <div class="absolute bottom-0 right-0 w-125 h-125 bg-blue-900 rounded-full blur-[120px] mix-blend-screen"></div>
        </div>
    </template>

    <div class="relative z-10 max-w-sm w-full text-center">

        {{-- ICON --}}
        <div class="flex justify-center mb-8">
            <div
                :class="$store.theme.isDark ? 'bg-amber-500/10 border-amber-500/20 text-amber-500' : 'bg-orange-50 border-orange-100 text-orange-600'"
                class="relative h-24 w-24 rounded-[2.5rem] flex items-center justify-center shadow-2xl border"
            >
                <i data-lucide="alert-triangle" class="w-12 h-12 animate-pulse"></i>
                <div
                    :class="$store.theme.isDark ? 'bg-slate-800 text-amber-500' : 'bg-white text-orange-600'"
                    class="absolute -bottom-2 -right-2 h-10 w-10 rounded-2xl flex items-center justify-center shadow-lg rotate-12"
                >
                    <i data-lucide="loader-2" class="w-5 h-5 animate-spin"></i>
                </div>
            </div>
        </div>

        {{-- TEXT --}}
        <h1 class="text-4xl font-black uppercase tracking-tighter italic leading-none mb-4">
            No Active <span :class="$store.theme.isDark ? 'text-amber-500' : 'text-orange-600'">Bill</span>
        </h1>

        <div :class="$store.theme.isDark ? 'bg-amber-500/20' : 'bg-orange-200'" class="h-1 w-12 mx-auto rounded-full mb-6"></div>

        <p class="text-sm font-black uppercase tracking-[0.2em] mb-2 opacity-80">Sesi Telah Berakhir</p>

        <p class="text-xs font-medium leading-relaxed mb-10 px-4 opacity-50 italic">
            Tagihan pada sesi ini sudah ditutup. Silakan hubungi kasir untuk mengaktifkan kembali meja Anda sebelum memesan lagi.
        </p>

        {{-- REDIRECT TIMER CARD --}}
        <div
            :class="$store.theme.isDark ? 'bg-slate-900/50 border-white/10 shadow-2xl' : 'bg-white border-slate-100 shadow-xl shadow-slate-200/50'"
            class="p-6 rounded-[2.5rem] border transition-all duration-300 backdrop-blur-md"
        >
            <div class="flex items-center justify-between gap-4">
                <div class="text-left">
                    <p class="text-[10px] font-black uppercase tracking-widest opacity-40">Auto Redirect</p>
                    <p class="text-xs font-bold italic">Checking Session...</p>
                </div>
                <div class="flex items-center gap-3">
                    <span
                        :class="$store.theme.isDark ? 'text-amber-500' : 'text-orange-600'"
                        class="text-3xl font-black italic"
                        x-text="'0' + count"
                    ></span>
                    <i data-lucide="arrow-right-circle" class="w-6 h-6 opacity-20"></i>
                </div>
            </div>

            {{-- PROGRESS BAR --}}
            <div :class="$store.theme.isDark ? 'bg-white/5' : 'bg-slate-100'" class="mt-4 h-1.5 w-full rounded-full overflow-hidden">
                <div
                    :class="$store.theme.isDark ? 'bg-amber-500' : 'bg-orange-600'"
                    :style="'width: ' + (count / 5 * 100) + '%'"
                    class="h-full transition-all duration-1000 ease-linear"
                ></div>
            </div>
        </div>

        {{-- SKIP --}}
        <button
            @click="window.location.href = '/order/session/{{ $sessionToken }}'"
            :class="$store.theme.isDark ? 'text-white' : 'text-slate-900'"
            class="mt-8 text-[10px] font-black uppercase tracking-[0.3em] opacity-30 hover:opacity-100 transition-opacity underline underline-offset-8"
        >
            Skip Waiting
        </button>

    </div>
</div>

@endsection