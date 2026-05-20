<x-admin-layout>
    <x-slot:title>Riwayat Shift</x-slot:title>
    <x-slot:page_title>Account > My Profile > Shift History</x-slot:page_title>

    @php $atLeastPro = in_array(session('plan', 'starter'), ['pro', 'enterprise']); @endphp

    <div x-data="myShiftHistory({{ json_encode($data ?? []) }})" x-init="$store.theme.init();
    $nextTick(() => lucide.createIcons());
    $watch('isDark', () => $nextTick(() => lucide.createIcons()))"
        class="flex h-full flex-col overflow-hidden transition-colors duration-500">
        {{-- Background Ambient --}}
        <template x-if="isDark">
            <div class="pointer-events-none fixed inset-0 opacity-20">
                <div class="w-125 h-125 absolute left-0 top-0 rounded-full bg-purple-900 mix-blend-screen blur-[120px]">
                </div>
                <div
                    class="w-125 h-125 absolute bottom-0 right-0 rounded-full bg-blue-900 mix-blend-screen blur-[120px]">
                </div>
            </div>
        </template>

        {{-- Header --}}
        <div :class="isDark ? 'bg-slate-900/70 border-white/5' : 'bg-white/70 border-gray-200/50'"
            class="sticky top-0 z-40 w-full border-b px-8 py-4 backdrop-blur-xl transition-all duration-300">
            <div class="flex w-full flex-col items-center justify-between gap-6 md:flex-row">
                <div class="flex items-center gap-3">
                    <div :class="isDark ? 'bg-amber-500 text-slate-950 shadow-lg' : 'bg-orange-600 text-white shadow-sm'"
                        class="rounded-xl p-2.5">
                        <i data-lucide="activity" class="h-6 w-6"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold leading-none tracking-tight">My Shift History</h1>
                        <p :class="isDark ? 'text-gray-400' : 'text-gray-500'"
                            class="mt-1 text-[10px] font-bold uppercase tracking-widest">
                            Your personal work sessions and performance
                        </p>
                    </div>
                </div>

                <div class="flex w-full items-center gap-4 md:w-auto">
                    <div :class="isDark ? 'bg-slate-900 border-white/10 focus-within:border-amber-500/50' :
                        'bg-white border-gray-200 focus-within:border-orange-500'"
                        class="flex flex-1 items-center gap-3 rounded-full border p-3 px-5 shadow-sm transition-all">
                        <i data-lucide="search" :class="isDark ? 'text-slate-500' : 'text-gray-400'"
                            class="h-[18px] w-[18px]"></i>
                        <input type="text" x-model="searchQuery" placeholder="Cari bulan atau status..."
                            class="w-full border-none bg-transparent text-sm font-medium outline-none" />
                        <button @click="$dispatch('toggle-theme')"
                            class="rounded-full text-slate-600 shadow-sm transition-transform hover:scale-110 hover:bg-white active:rotate-90 dark:text-amber-400 dark:shadow-none dark:hover:bg-slate-800">
                            <span x-show="isDark"><x-lucide-sun class="h-[18px] w-[18px]" /></span>
                            <span x-show="!isDark"><x-lucide-moon class="h-[18px] w-[18px]" /></span>
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
                    <p class="text-xs font-black uppercase tracking-widest">No records for you</p>
                </div>
            </template>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <template x-for="s in filteredShifts" :key="s.id">
                    <div :class="isDark ? 'bg-slate-900/40 border-white/5 hover:bg-slate-800 shadow-2xl' :
                        'bg-white border-gray-100 hover:shadow-xl shadow-gray-200/50'"
                        class="group relative rounded-[3rem] border p-8 transition-all duration-300 hover:-translate-y-2">
                        {{-- Calendar Badge & Status --}}
                        <div class="mb-8 flex items-start justify-between">
                            <div :class="isDark ? 'bg-slate-950 border-white/5' : 'bg-gray-50 border-gray-100'"
                                class="flex h-14 w-14 flex-col items-center justify-center rounded-2xl border-2">
                                <span :class="isDark ? 'text-amber-500' : 'text-orange-600'"
                                    class="text-[10px] font-black uppercase"
                                    x-text="new Date(s.openedAt).toLocaleDateString('id-ID', { month: 'short' })"></span>
                                <span class="mt-1 text-xl font-black leading-none"
                                    x-text="new Date(s.openedAt).toLocaleDateString('id-ID', { day: '2-digit' })"></span>
                            </div>

                            <div :class="s.status === 'open' ?
                                (isDark ? 'bg-amber-500/10 text-amber-400 animate-pulse' :
                                    'bg-orange-100 text-orange-700') :
                                (isDark ? 'bg-emerald-500/10 text-emerald-400' :
                                    'bg-emerald-100 text-emerald-700')"
                                class="rounded-full px-4 py-1.5 text-[8px] font-black uppercase tracking-widest"
                                x-text="s.status">
                            </div>
                        </div>

                        {{-- Finance --}}
                        <div class="mb-8 space-y-4">
                            <div class="flex items-end justify-between">
                                <div>
                                    <p class="mb-1 text-[9px] font-black uppercase opacity-40">Cash Seharusnya</p>
                                    <p class="text-lg font-black tracking-tight" x-text="formatRp(s.startingCash)"></p>
                                </div>
                                <div class="text-right">
                                    <p class="mb-1 text-[9px] font-black uppercase opacity-40">Selisih</p>
                                    <p :class="(s.selisih || 0) < 0 ? 'text-red-500' : (s.selisih || 0) > 0 ? 'text-emerald-500' :
                                        ''"
                                        class="text-lg font-black tracking-tight"
                                        x-text="s.status === 'open' ? '---' : formatRp(s.selisih || 0)"></p>
                                </div>
                            </div>

                            <template x-if="s.status === 'closed'">
                                <div :class="isDark ? 'bg-white/5' : 'bg-gray-100'"
                                    class="h-1.5 w-full overflow-hidden rounded-full">
                                    <div :class="(s.selisih || 0) < 0 ? 'bg-red-500' : 'bg-emerald-500'"
                                        :style="'width: ' + ((s.selisih || 0) === 0 ? '100%' : '95%')"
                                        class="h-full transition-all duration-1000"></div>
                                </div>
                            </template>
                        </div>

                        <div :class="isDark ? 'bg-white/5' : 'bg-gray-100'" class="mb-6 h-px w-full">
                        </div>

                        {{-- Time & Action --}}
                        <div class="flex items-center justify-between">
                            <div class="flex flex-col gap-1.5">
                                <div class="flex items-center gap-2">
                                    <div class="h-1.5 w-1.5 rounded-full bg-emerald-500"></div>
                                    <span class="text-[10px] font-bold italic opacity-60">
                                        In: <span
                                            x-text="new Date(s.openedAt).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })"></span>
                                    </span>
                                </div>
                                <template x-if="s.closedAt">
                                    <div class="flex items-center gap-2">
                                        <div class="h-1.5 w-1.5 rounded-full bg-red-500"></div>
                                        <span class="text-[10px] font-bold italic opacity-60">
                                            Out: <span
                                                x-text="new Date(s.closedAt).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })"></span>
                                        </span>
                                    </div>
                                </template>
                            </div>

                            <a :href="'/shifts/' + s.id"
                                :class="isDark ?
                                    'bg-white/5 text-gray-400 hover:bg-amber-500 hover:text-slate-950 shadow-lg shadow-black/20' :
                                    'bg-gray-100 text-slate-600 hover:bg-slate-900 hover:text-white'"
                                class="flex h-10 w-10 items-center justify-center rounded-xl transition-all">
                                <i data-lucide="chevron-right" class="h-[18px] w-[18px]"></i>
                            </a>
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
                        new Date(s.openedAt).toLocaleDateString('id-ID', {
                            month: 'long'
                        })
                        .toLowerCase().includes(this.searchQuery.toLowerCase()) ||
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
