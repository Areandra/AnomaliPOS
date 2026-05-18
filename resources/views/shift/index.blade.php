<x-admin-layout>
    <x-slot:title>Riwayat Shift</x-slot:title>
    <x-slot:page_title>Management > Shifts</x-slot:page_title>


    <div x-data="shiftIndex({{ json_encode(
        $shifts->map(
                fn($s) => [
                    'id' => $s->id,
                    'user' => ['name' => $s->user->name ?? 'Unknown', 'avatarUrl' => null],
                    'openedAt' => $s->opened_at,
                    'closedAt' => $s->closed_at,
                    'startingCash' => (float) $s->modal_awal,
                    'cashSystem' => (float) $s->cash_system,
                    'cashPhysical' => (float) $s->cash_physical,
                    'selisih' => (float) $s->cash_variance,
                    'status' => $s->status,
                ],
            )->values(),
    ) }})" class="flex h-full flex-col overflow-hidden transition-colors duration-500">
        {{-- Background Ambient --}}
        <template x-if="isDark">
            <div class="pointer-events-none fixed inset-0 opacity-20">
                <div class="w-125 h-125 absolute left-0 top-0 rounded-full bg-indigo-900 mix-blend-screen blur-[120px]">
                </div>
                <div
                    class="w-125 h-125 absolute bottom-0 right-0 rounded-full bg-amber-900 mix-blend-screen blur-[120px]">
                </div>
            </div>
        </template>

        {{-- Header --}}
        <div :class="isDark ? 'bg-slate-900/70 border-white/5' : 'bg-white/70 border-gray-200/50'"
            class="sticky top-0 z-40 w-full border-b px-8 py-4 backdrop-blur-xl transition-all duration-300">
            <div class="mx-auto flex w-full flex-col items-center justify-between gap-6 md:flex-row">
                <div class="flex items-center gap-3">
                    <div :class="isDark ? 'bg-slate-800 text-amber-500 shadow-lg shadow-black/20' :
                        'bg-orange-100 text-orange-600 shadow-sm'"
                        class="rounded-xl p-2.5">
                        <i data-lucide="history" class="h-6 w-6"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold leading-none tracking-tight">Shift History</h1>
                        <p :class="isDark ? 'text-gray-400' : 'text-gray-500'"
                            class="mt-1 text-[10px] font-bold uppercase tracking-widest">
                            Monitoring session and cash reconciliation
                        </p>
                    </div>
                </div>
                <div class="flex w-full items-center gap-4 md:w-auto">
                    <div :class="isDark ? 'bg-slate-900 border-white/10 focus-within:border-amber-500/50' :
                        'bg-white border-gray-200 focus-within:border-orange-500'"
                        class="flex flex-1 items-center gap-3 rounded-full border p-3 px-5 shadow-sm transition-all">
                        <i data-lucide="search" :class="isDark ? 'text-slate-500' : 'text-gray-400'"
                            class="h-[18px] w-[18px]"></i>
                        <input type="text" x-model="searchQuery" placeholder="Cari nama kasir..."
                            class="w-full border-none bg-transparent text-sm font-medium outline-none" />
                        <button @click="$store.theme.toggle(); $nextTick(() => lucide.createIcons())"
                            :class="isDark ? 'text-amber-400 hover:bg-slate-800' :
                                'text-slate-600 hover:bg-white shadow-sm'"
                            class="rounded-full transition-transform hover:scale-110 active:rotate-90">
                            <i :data-lucide="isDark ? 'sun' : 'moon'" class="h-[18px] w-[18px]"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="w-full flex-1 overflow-y-auto p-8 pb-32">
            <template x-if="filteredShifts.length === 0">
                <div class="flex flex-col items-center justify-center py-20 opacity-30">
                    <i data-lucide="history" class="mb-4 h-12 w-12"></i>
                    <p class="text-xs font-black uppercase tracking-widest">No shift records found</p>
                </div>
            </template>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <template x-for="s in filteredShifts" :key="s.id">
                    <div :class="isDark ?
                        'bg-slate-900/50 border-white/5 hover:bg-slate-800 shadow-2xl shadow-black/20' :
                        'bg-white border-gray-100 hover:shadow-xl shadow-gray-200/50'"
                        class="group relative rounded-[2.5rem] border p-6 transition-all duration-300 hover:-translate-y-1">

                        {{-- Status Badge --}}
                        <div :class="s.status === 'open' ?
                            (isDark ? 'bg-amber-500/10 text-amber-400 animate-pulse' :
                                'bg-orange-100 text-orange-700') :
                            (isDark ? 'bg-emerald-500/10 text-emerald-400' :
                                'bg-emerald-100 text-emerald-700')"
                            class="absolute right-6 top-6 rounded-full px-3 py-1 text-[8px] font-black uppercase tracking-widest"
                            x-text="s.status">
                        </div>

                        {{-- User Info --}}
                        <div class="mb-6 flex items-center gap-3">
                            <img :src="s.user.avatarUrl ??
                                `https://ui-avatars.com/api/?name=${encodeURIComponent(s.user.name)}&background=${isDark ? '1e293b' : 'f1f5f9'}&color=${isDark ? 'f59e0b' : 'ea580c'}&bold=true`"
                                :alt="s.user.name"
                                :class="isDark ? 'border-white/5' : 'border-gray-100'"
                                class="h-12 w-12 rounded-2xl border object-cover shadow-sm" />
                            <div>
                                <h3 :class="isDark ? 'text-white' : 'text-slate-800'"
                                    class="text-sm font-black uppercase tracking-tight" x-text="s.user.name"></h3>
                                <div :class="isDark ? 'text-gray-400' : 'text-gray-500'"
                                    class="flex items-center gap-1 text-[9px] font-bold opacity-60">
                                    <i data-lucide="calendar" class="h-2.5 w-2.5"></i>
                                    <span
                                        x-text="new Date(s.openedAt).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })"></span>
                                </div>
                            </div>
                        </div>

                        {{-- Finance Grid --}}
                        <div class="mb-6 grid grid-cols-2 gap-3">
                            <div :class="isDark ? 'bg-white/5' : 'bg-gray-50'" class="rounded-2xl p-3">
                                <p class="mb-1 flex items-center gap-1 text-[8px] font-black uppercase opacity-40">
                                    <i data-lucide="arrow-up-right" class="h-2.5 w-2.5"></i> Modal
                                </p>
                                <p class="text-[11px] font-bold"
                                    x-text="formatRp(Number(s.cashSystem) + Number(s.startingCash))"></p>
                            </div>
                            <div :class="isDark ? 'bg-white/5' : 'bg-gray-50'" class="rounded-2xl p-3">
                                <p class="mb-1 flex items-center gap-1 text-[8px] font-black uppercase opacity-40">
                                    <i data-lucide="banknote" class="h-2.5 w-2.5"></i> Fisik
                                </p>
                                <p class="text-[11px] font-bold"
                                    x-text="s.cashPhysical ? formatRp(s.cashPhysical) : '---'"></p>
                            </div>
                        </div>

                        {{-- Variance --}}
                        <div :class="s.status === 'open' ?
                            'border-gray-100 opacity-50' :
                            (s.selisih < 0 ? 'border-red-500/20 bg-red-500/5 text-red-500' : s.selisih > 0 ?
                                'border-emerald-500/20 bg-emerald-500/5 text-emerald-500' :
                                'border-indigo-500/20 bg-indigo-500/5 text-indigo-500')"
                            class="mb-6 flex items-center justify-between rounded-2xl border-2 border-dashed p-4">
                            <div>
                                <p class="text-[8px] font-black uppercase opacity-60">Selisih Kas</p>
                                <p class="text-sm font-black tracking-tighter"
                                    x-text="s.status === 'open' ? 'PENDING' : formatRp(s.selisih || 0)"></p>
                            </div>
                            <template x-if="s.status === 'closed' && s.selisih < 0">
                                <i data-lucide="trending-down" class="h-5 w-5"></i>
                            </template>
                            <template x-if="s.status === 'closed' && s.selisih > 0">
                                <i data-lucide="trending-up" class="h-5 w-5"></i>
                            </template>
                            <template x-if="s.status === 'closed' && s.selisih === 0">
                                <i data-lucide="minus-circle" class="h-5 w-5"></i>
                            </template>
                        </div>

                        <div :class="isDark ? 'bg-white/5' : 'bg-gray-50'" class="mb-4 h-px w-full"></div>

                        {{-- Time & Action --}}
                        <div class="flex w-full items-center justify-between">
                            <div class="flex flex-col gap-1">
                                <div :class="isDark ? 'text-gray-400' : 'text-gray-500'"
                                    class="flex items-center gap-1.5 text-[10px] font-bold">
                                    <i data-lucide="clock" class="h-3 w-3 text-emerald-500"></i>
                                    In: <span
                                        x-text="new Date(s.openedAt).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })"></span>
                                </div>
                                <template x-if="s.closedAt">
                                    <div :class="isDark ? 'text-gray-400' : 'text-gray-500'"
                                        class="flex items-center gap-1.5 text-[10px] font-bold">
                                        <i data-lucide="clock" class="h-3 w-3 text-red-500"></i>
                                        Out: <span
                                            x-text="new Date(s.closedAt).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })"></span>
                                    </div>
                                </template>
                            </div>
                            <a :href="'/shifts/' + s.id"
                                :class="isDark ?
                                    'bg-white/5 text-gray-300 hover:bg-amber-500 hover:text-slate-950' :
                                    'bg-gray-100 text-slate-600 hover:bg-slate-900 hover:text-white'"
                                class="rounded-xl p-2.5 transition-all">
                                <i data-lucide="chevron-right" class="h-[18px] w-[18px]"></i>
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
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(v)
                }
            }
        }
    </script>
</x-admin-layout>
