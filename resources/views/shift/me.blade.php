@extends('layouts.admin')

@section('title', 'Riwayat Shift Saya')
@section('page_title', 'Account > My Profile > Shift History')

@section('content')

@php $atLeastPro = in_array(session('plan', 'starter'), ['pro', 'enterprise']); @endphp

<div
    x-data="myShiftHistory({{ json_encode($data ?? []) }})"
    x-init="$store.theme.init(); $nextTick(() => lucide.createIcons()); $watch('$store.theme.isDark', () => $nextTick(() => lucide.createIcons()))"
    class="h-full flex flex-col transition-colors duration-500 overflow-hidden"
>
    {{-- Background Ambient --}}
    <template x-if="$store.theme.isDark">
        <div class="fixed inset-0 pointer-events-none opacity-20">
            <div class="absolute top-0 left-0 w-125 h-125 bg-amber-900 rounded-full blur-[120px] mix-blend-screen"></div>
            <div class="absolute bottom-0 right-0 w-125 h-125 bg-slate-900 rounded-full blur-[120px] mix-blend-screen"></div>
        </div>
    </template>

    {{-- Header --}}
    <div :class="$store.theme.isDark ? 'bg-slate-900/70 border-white/5' : 'bg-white/70 border-gray-200/50'"
         class="sticky top-0 z-40 w-full px-8 py-4 backdrop-blur-xl border-b transition-all duration-300">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6 w-full">
            <div class="flex items-center gap-3">
                <div :class="$store.theme.isDark ? 'bg-amber-500 text-slate-950 shadow-lg' : 'bg-orange-600 text-white shadow-sm'"
                     class="p-2.5 rounded-xl">
                    <i data-lucide="activity" class="w-6 h-6"></i>
                </div>
                <div>
                    <h1 class="font-bold text-lg leading-none tracking-tight">My Shift History</h1>
                    <p :class="$store.theme.isDark ? 'text-gray-400' : 'text-gray-500'"
                       class="text-[10px] mt-1 uppercase font-bold tracking-widest">
                        Your personal work sessions and performance
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-4 w-full md:w-auto">
                <div :class="$store.theme.isDark ? 'bg-slate-900 border-white/10 focus-within:border-amber-500/50' : 'bg-white border-gray-200 focus-within:border-orange-500'"
                     class="flex items-center gap-3 flex-1 p-3 px-5 border rounded-full transition-all shadow-sm">
                    <i data-lucide="search" :class="$store.theme.isDark ? 'text-slate-500' : 'text-gray-400'" class="w-[18px] h-[18px]"></i>
                    <input type="text" x-model="searchQuery" placeholder="Cari bulan atau status..."
                           class="bg-transparent border-none outline-none w-full text-sm font-medium" />
                    <button @click="$store.theme.toggle(); $nextTick(() => lucide.createIcons())"
                        :class="$store.theme.isDark ? 'text-amber-400 hover:bg-slate-800' : 'text-slate-600 hover:bg-white'"
                        class="rounded-full transition-transform active:rotate-90 hover:scale-110">
                        <i :data-lucide="$store.theme.isDark ? 'sun' : 'moon'" class="w-[18px] h-[18px]"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div class="p-8 w-full flex-1 overflow-y-auto pb-32">
        <template x-if="filteredShifts.length === 0">
            <div class="flex flex-col items-center justify-center py-20 opacity-30">
                <i data-lucide="history" class="w-12 h-12 mb-4"></i>
                <p class="font-black uppercase tracking-widest text-xs">No records for you</p>
            </div>
        </template>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <template x-for="s in filteredShifts" :key="s.id">
                <div
                    :class="$store.theme.isDark ? 'bg-slate-900/40 border-white/5 hover:bg-slate-800 shadow-2xl' : 'bg-white border-gray-100 hover:shadow-xl shadow-gray-200/50'"
                    class="group relative p-8 rounded-[3rem] border transition-all duration-300 hover:-translate-y-2"
                >
                    {{-- Calendar Badge & Status --}}
                    <div class="flex justify-between items-start mb-8">
                        <div :class="$store.theme.isDark ? 'bg-slate-950 border-white/5' : 'bg-gray-50 border-gray-100'"
                             class="flex flex-col items-center justify-center w-14 h-14 rounded-2xl border-2">
                            <span :class="$store.theme.isDark ? 'text-amber-500' : 'text-orange-600'"
                                  class="text-[10px] font-black uppercase"
                                  x-text="new Date(s.openedAt).toLocaleDateString('id-ID', { month: 'short' })"></span>
                            <span class="text-xl font-black leading-none mt-1"
                                  x-text="new Date(s.openedAt).toLocaleDateString('id-ID', { day: '2-digit' })"></span>
                        </div>

                        <div :class="s.status === 'open'
                                ? ($store.theme.isDark ? 'bg-amber-500/10 text-amber-400 animate-pulse' : 'bg-orange-100 text-orange-700')
                                : ($store.theme.isDark ? 'bg-emerald-500/10 text-emerald-400' : 'bg-emerald-100 text-emerald-700')"
                             class="px-4 py-1.5 rounded-full text-[8px] font-black uppercase tracking-widest"
                             x-text="s.status">
                        </div>
                    </div>

                    {{-- Finance --}}
                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between items-end">
                            <div>
                                <p class="text-[9px] font-black uppercase opacity-40 mb-1">Cash Seharusnya</p>
                                <p class="text-lg font-black tracking-tight" x-text="formatRp(s.startingCash)"></p>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] font-black uppercase opacity-40 mb-1">Selisih</p>
                                <p :class="(s.selisih || 0) < 0 ? 'text-red-500' : (s.selisih || 0) > 0 ? 'text-emerald-500' : ''"
                                   class="text-lg font-black tracking-tight"
                                   x-text="s.status === 'open' ? '---' : formatRp(s.selisih || 0)"></p>
                            </div>
                        </div>

                        <template x-if="s.status === 'closed'">
                            <div :class="$store.theme.isDark ? 'bg-white/5' : 'bg-gray-100'" class="w-full h-1.5 rounded-full overflow-hidden">
                                <div :class="(s.selisih || 0) < 0 ? 'bg-red-500' : 'bg-emerald-500'"
                                     :style="'width: ' + ((s.selisih || 0) === 0 ? '100%' : '95%')"
                                     class="h-full transition-all duration-1000"></div>
                            </div>
                        </template>
                    </div>

                    <div :class="$store.theme.isDark ? 'bg-white/5' : 'bg-gray-100'" class="w-full h-px mb-6"></div>

                    {{-- Time & Action --}}
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col gap-1.5">
                            <div class="flex items-center gap-2">
                                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                                <span class="text-[10px] font-bold opacity-60 italic">
                                    In: <span x-text="new Date(s.openedAt).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })"></span>
                                </span>
                            </div>
                            <template x-if="s.closedAt">
                                <div class="flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-red-500"></div>
                                    <span class="text-[10px] font-bold opacity-60 italic">
                                        Out: <span x-text="new Date(s.closedAt).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })"></span>
                                    </span>
                                </div>
                            </template>
                        </div>

                        @if($atLeastPro)
                        <a :href="'/shift/' + s.id"
                           :class="$store.theme.isDark ? 'bg-white/5 text-gray-400 hover:bg-amber-500 hover:text-slate-950 shadow-lg shadow-black/20' : 'bg-gray-100 text-slate-600 hover:bg-slate-900 hover:text-white'"
                           class="w-10 h-10 rounded-xl flex items-center justify-center transition-all">
                            <i data-lucide="chevron-right" class="w-[18px] h-[18px]"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

<script>
function myShiftHistory(data) {
    return {
        data,
        searchQuery: '',

        get filteredShifts() {
            return this.data.filter(s =>
                new Date(s.openedAt).toLocaleDateString('id-ID', { month: 'long' })
                    .toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                s.status.toLowerCase().includes(this.searchQuery.toLowerCase())
            )
        },

        formatRp(v) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency', currency: 'IDR', minimumFractionDigits: 0
            }).format(v)
        }
    }
}
</script>

@endsection