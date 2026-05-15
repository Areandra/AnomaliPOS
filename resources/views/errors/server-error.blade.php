@extends('layouts.app')

@section('title', '500 - System Failure')

@section('content')

<div
    x-data
    x-init="$store.theme.init()"
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

    <div class="relative z-10 max-w-lg w-full text-center">

        {{-- ICON --}}
        <div class="flex justify-center mb-10">
            <div
                :class="$store.theme.isDark ? 'bg-slate-900 border-amber-500/20 text-amber-500' : 'bg-white border-orange-100 text-orange-600'"
                class="relative h-32 w-32 rounded-[3rem] flex items-center justify-center shadow-2xl border-2"
            >
                <i data-lucide="server-crash" class="w-16 h-16 animate-bounce" style="stroke-width: 1.5"></i>
                <div
                    :class="$store.theme.isDark ? 'bg-amber-500 text-slate-950' : 'bg-orange-600 text-white'"
                    class="absolute -top-2 -right-2 h-12 w-12 rounded-2xl flex items-center justify-center shadow-xl rotate-12"
                >
                    <i data-lucide="wifi-off" class="w-6 h-6"></i>
                </div>
            </div>
        </div>

        {{-- ERROR CODE --}}
        <h1 class="text-7xl font-black uppercase tracking-tighter italic leading-none mb-2 opacity-10">500</h1>
        <h2 class="text-3xl font-black uppercase tracking-tighter italic leading-none mb-4">
            Sistem <span :class="$store.theme.isDark ? 'text-amber-500' : 'text-orange-600'">Bermasalah</span>
        </h2>

        <div :class="$store.theme.isDark ? 'bg-amber-500/20' : 'bg-orange-200'" class="h-1.5 w-16 mx-auto rounded-full mb-8"></div>

        <p class="text-[10px] font-black uppercase tracking-[0.3em] mb-3 opacity-80">Internal Server Error</p>

        {{-- ERROR LOG BOX --}}
        <div
            :class="$store.theme.isDark ? 'bg-black/40 border-white/5 text-amber-500/70' : 'bg-gray-100 border-gray-200 text-gray-600'"
            class="mb-10 p-4 rounded-2xl border font-mono text-left overflow-hidden relative"
        >
            <div class="flex items-center gap-2 mb-2 opacity-30">
                <i data-lucide="terminal" class="w-3 h-3"></i>
                <span class="text-[9px] font-black uppercase tracking-widest">Error Log</span>
            </div>
            <p class="text-[11px] break-words leading-relaxed">
                {{ isset($exception) ? $exception->getMessage() : 'An unexpected error occurred on our end. Please try again later.' }}
            </p>
        </div>

        {{-- ACTION --}}
        <div class="space-y-4">
            <button
                @click="window.location.reload()"
                :class="$store.theme.isDark
                    ? 'bg-amber-500 text-slate-950 hover:bg-amber-400 shadow-amber-500/20'
                    : 'bg-orange-600 text-white hover:bg-orange-700 shadow-orange-900/20'"
                class="flex items-center justify-center gap-3 w-full py-4 rounded-[1.5rem] font-black uppercase text-xs tracking-[0.2em] transition-all active:scale-95 shadow-xl"
            >
                <i data-lucide="refresh-ccw" class="w-4 h-4"></i>
                Coba Segarkan Halaman
            </button>

            <p class="text-[9px] font-black uppercase tracking-widest opacity-30 italic">
                Technical contact: system-admin@pos-center.id
            </p>
        </div>

    </div>
</div>

@endsection