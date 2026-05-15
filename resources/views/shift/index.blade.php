@extends('layouts.admin')

@section('title', 'Riwayat Shift')
@section('page_title', 'Management > Shifts')

@section('content')

<div
    x-data="shiftIndex({{ json_encode($history ?? []) }})"
    x-init="$store.theme.init(); $nextTick(() => lucide.createIcons()); $watch('$store.theme.isDark', () => $nextTick(() => lucide.createIcons()))"
    class="h-full flex flex-col transition-colors duration-500 overflow-hidden"
>
    {{-- Background Ambient --}}
    <template x-if="$store.theme.isDark">
        <div class="fixed inset-0 pointer-events-none opacity-20">
            <div class="absolute top-0 left-0 w-125 h-125 bg-indigo-900 rounded-full blur-[120px] mix-blend-screen"></div>
            <div class="absolute bottom-0 right-0 w-125 h-125 bg-amber-900 rounded-full blur-[120px] mix-blend-screen"></div>
        </div>
    </template>

    {{-- Header --}}
    <div
        :class="$store.theme.isDark ? 'bg-slate-900/70 border-white/5' : 'bg-white/70 border-gray-200/50'"
        class="sticky top-0 z-40 w-full px-8 py-4 backdrop-blur-xl border-b transition-all duration-300"
    >
        <div class="flex flex-col md:flex-row justify-between items-center gap-6 w-full">
            <div class="flex items-center gap-3">
                <div :class="$store.theme.isDark ? 'bg-slate-800 text-amber-500 shadow-lg shadow-black/20' : 'bg-orange-100 text-orange-600 shadow-sm'"
                     class="p-2.5 rounded-xl">
                    <i data-lucide="history" class="w-6 h-6"></i>
                </div>
                <div>
                    <h1 class="font-bold text-lg leading-none tracking-tight">Shift History</h1>
                    <p :class="$store.theme.isDark ? 'text-gray-400' : 'text-gray-500'"
                       class="text-[10px] mt-1 uppercase font-bold tracking-widest">
                        Monitoring session and cash reconciliation
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-4 w-full md:w-auto">
                <div :class="$store.theme.isDark ? 'bg-slate-900 border-white/10 focus-within:border-amber-500/50' : 'bg-white border-gray-200 focus-within:border-orange-500'"
                     class="flex items-center gap-3 flex-1 p-3 px-5 border rounded-full transition-all shadow-sm">
                    <i data-lucide="search" :class="$store.theme.isDark ? 'text-slate-500' : 'text-gray-400'" class="w-[18px] h-[18px]"></i>
                    <input type="text" x-model="searchQuery" placeholder="Cari nama kasir..."
                           class="bg-transparent border-none outline-none w-full text-sm font-medium" />
                    <button @click="$store.theme.toggle(); $nextTick(() => lucide.createIcons())"
                        :class="$store.theme.isDark ? 'text-amber-400 hover:bg-slate-800' : 'text-slate-600 hover:bg-white shadow-sm'"
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
                <p class="font-black uppercase tracking-widest text-xs">No shift records found</p>
            </div>
        </template>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <template x-for="s in filteredShifts" :key="s.id">
                <div
                    :class="$store.theme.isDark
                        ? 'bg-slate-900/50 border-white/5 hover:bg-slate-800 shadow-2xl shadow-black/20'
                        : 'bg-white border-gray-100 hover:shadow-xl shadow-gray-200/50'"
                    class="group relative p-6 rounded-[2.5rem] border transition-all duration-300 hover:-translate-y-1"
                >
                    {{-- Status Badge --}}
                    <div
                        :class="s.status === 'open'
                            ? ($store.theme.isDark ? 'bg-amber-500/10 text-amber-400 animate-pulse' : 'bg-orange-100 text-orange-700')
                            : ($store.theme.isDark ? 'bg-emerald-500/10 text-emerald-400' : 'bg-emerald-100 text-emerald-700')"
                        class="absolute top-6 right-6 px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest"
                        x-text="s.status"
                    ></div>

                    {{-- User Info --}}
                    <div class="flex items-center gap-3 mb-6">
                        <img
                            :src="s.user.avatarUrl ?? `https://ui-avatars.com/api/?name=${encodeURIComponent(s.user.name)}&background=${$store.theme.isDark ? '1e293b' : 'f1f5f9'}&color=${$store.theme.isDark ? 'f59e0b' : 'ea580c'}&bold=true`"
                            :alt="s.user.name"
                            :class="$store.theme.isDark ? 'border-white/5' : 'border-gray-100'"
                            class="w-12 h-12 rounded-2xl object-cover border shadow-sm"
                        />
                        <div>
                            <h3 :class="$store.theme.isDark ? 'text-white' : 'text-slate-800'"
                                class="text-sm font-black uppercase tracking-tight"
                                x-text="s.user.name"></h3>
                            <div :class="$store.theme.isDark ? 'text-gray-400' : 'text-gray-500'"
                                 class="flex items-center gap-1 text-[9px] font-bold opacity-60">
                                <i data-lucide="calendar" class="w-2.5 h-2.5"></i>
                                <span x-text="new Date(s.openedAt).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Finance Grid --}}
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <div :class="$store.theme.isDark ? 'bg-white/5' : 'bg-gray-50'" class="p-3 rounded-2xl">
                            <p class="text-[8px] font-black uppercase opacity-40 mb-1 flex items-center gap-1">
                                <i data-lucide="arrow-up-right" class="w-2.5 h-2.5"></i> Modal
                            </p>
                            <p class="text-[11px] font-bold"
                               x-text="formatRp(Number(s.cashSystem) + Number(s.startingCash))"></p>
                        </div>
                        <div :class="$store.theme.isDark ? 'bg-white/5' : 'bg-gray-50'" class="p-3 rounded-2xl">
                            <p class="text-[8px] font-black uppercase opacity-40 mb-1 flex items-center gap-1">
                                <i data-lucide="banknote" class="w-2.5 h-2.5"></i> Fisik
                            </p>
                            <p class="text-[11px] font-bold"
                               x-text="s.cashPhysical ? formatRp(s.cashPhysical) : '---'"></p>
                        </div>
                    </div>

                    {{-- Variance --}}
                    <div
                        :class="s.status === 'open'
                            ? 'border-gray-100 opacity-50'
                            : (s.selisih < 0 ? 'border-red-500/20 bg-red-500/5 text-red-500' : s.selisih > 0 ? 'border-emerald-500/20 bg-emerald-500/5 text-emerald-500' : 'border-indigo-500/20 bg-indigo-500/5 text-indigo-500')"
                        class="p-4 rounded-2xl border-2 border-dashed mb-6 flex justify-between items-center"
                    >
                        <div>
                            <p class="text-[8px] font-black uppercase opacity-60">Selisih Kas</p>
                            <p class="text-sm font-black tracking-tighter"
                               x-text="s.status === 'open' ? 'PENDING' : formatRp(s.selisih || 0)"></p>
                        </div>
                        <template x-if="s.status === 'closed' && s.selisih < 0">
                            <i data-lucide="trending-down" class="w-5 h-5"></i>
                        </template>
                        <template x-if="s.status === 'closed' && s.selisih > 0">
                            <i data-lucide="trending-up" class="w-5 h-5"></i>
                        </template>
                        <template x-if="s.status === 'closed' && s.selisih === 0">
                            <i data-lucide="minus-circle" class="w-5 h-5"></i>
                        </template>
                    </div>

                    <div :class="$store.theme.isDark ? 'bg-white/5' : 'bg-gray-50'" class="w-full h-px mb-4"></div>

                    {{-- Time & Action --}}
                    <div class="flex items-center justify-between w-full">
                        <div class="flex flex-col gap-1">
                            <div :class="$store.theme.isDark ? 'text-gray-400' : 'text-gray-500'"
                                 class="flex items-center gap-1.5 text-[10px] font-bold">
                                <i data-lucide="clock" class="w-3 h-3 text-emerald-500"></i>
                                In: <span x-text="new Date(s.openedAt).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })"></span>
                            </div>
                            <template x-if="s.closedAt">
                                <div :class="$store.theme.isDark ? 'text-gray-400' : 'text-gray-500'"
                                     class="flex items-center gap-1.5 text-[10px] font-bold">
                                    <i data-lucide="clock" class="w-3 h-3 text-red-500"></i>
                                    Out: <span x-text="new Date(s.closedAt).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })"></span>
                                </div>
                            </template>
                        </div>

                        <a :href="'/shift/' + s.id"
                           :class="$store.theme.isDark ? 'bg-white/5 text-gray-300 hover:bg-amber-500 hover:text-slate-950' : 'bg-gray-100 text-slate-600 hover:bg-slate-900 hover:text-white'"
                           class="p-2.5 rounded-xl transition-all">
                            <i data-lucide="chevron-right" class="w-[18px] h-[18px]"></i>
                        </a>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

<script>
function shiftIndex(history) {
    return {
        history,
        searchQuery: '',

        get filteredShifts() {
            return this.history.filter(s =>
                s.user.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
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