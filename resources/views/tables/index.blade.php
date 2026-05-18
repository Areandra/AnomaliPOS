@php
    $tableData = $tables
        ->map(
            fn($t) => [
                'id' => $t->id,
                'tableNumber' => $t->table_number,
                'capacity' => $t->capacity,
                'status' => $t->status,
                'facing' => $t->facing,
                'vertical' => $t->vertical,
                'positionX' => $t->position_x ?? 0,
                'positionY' => $t->position_y ?? 0,
            ],
        )
        ->values();

    $tableMapProps = [
        'table' => $tableData,
        'drag' => false,
        'editMode' => 'information',
        'theme' => 'dark',
    ];
@endphp

<x-admin-layout>
    <x-slot name="title">Manajemen Meja</x-slot>
    <x-slot name="page_title">Management > Tables</x-slot>

    <div class="flex h-full flex-col overflow-hidden transition-colors duration-500"
        :class="isDark ? 'bg-slate-950 text-gray-100' : 'bg-[#FDFBF7] text-gray-900'">

        {{-- ── Background Glow ─────────────────────────────────────── --}}
        <template x-if="isDark">
            <div class="pointer-events-none fixed inset-0 opacity-20">
                <div
                    class="absolute left-0 top-0 h-[500px] w-[500px] rounded-full bg-indigo-900 mix-blend-screen blur-[120px]">
                </div>
                <div
                    class="absolute bottom-0 right-0 h-[500px] w-[500px] rounded-full bg-amber-900 mix-blend-screen blur-[120px]">
                </div>
            </div>
        </template>

        {{-- ── Header ────────────────────────────────────────────────── --}}
        <div class="sticky top-0 z-40 w-full border-b px-8 py-4 backdrop-blur-xl transition-all duration-300"
            :class="isDark ?
                'bg-slate-900/70 border-white/5' :
                'bg-white/70 border-gray-200/50'">
            <div class="mx-auto flex flex-col items-center justify-between gap-4 md:flex-row">

                {{-- Title --}}
                <div class="flex items-center gap-3">
                    <div class="rounded-xl p-2.5"
                        :class="isDark ?
                            'bg-slate-800 text-amber-500 shadow-lg shadow-black/20' :
                            'bg-orange-100 text-orange-600 shadow-sm'">
                        <i data-lucide="map" class="h-5 w-5"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold leading-none tracking-tight">Table Management</h1>
                        <p class="mt-1 text-[10px] font-bold uppercase tracking-widest"
                            :class="isDark ? 'text-gray-400' : 'text-gray-500'">Map Editor System</p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3">

                    {{-- Save Layout — muncul hanya jika ada perubahan --}}
                    <div id="activity-wrapper" class="hidden">
                        <button id="btn-save-layout"
                            class="animate-pulse rounded-xl bg-gradient-to-r from-amber-400 to-orange-400 px-5 py-2.5 text-xs font-bold text-slate-950 shadow-lg shadow-orange-500/20 transition-all active:scale-95">
                            <div class="flex items-center gap-2">
                                <i data-lucide="save" class="h-4 w-4"></i>

                                <span>SAVE LAYOUT</span>
                            </div>
                        </button>
                    </div>
                    <a href="{{ route('tables.create') }}"
                        class="flex items-center gap-2 rounded-xl px-5 py-2.5 text-xs font-bold shadow-lg transition-all active:scale-95"
                        :class="isDark ?
                            'bg-white text-slate-950 hover:bg-gray-200' :
                            'bg-slate-900 text-white hover:bg-slate-800 shadow-slate-200'">
                        <i data-lucide="plus" class="h-4 w-4"></i> NEW TABLE
                    </a>

                    <button onclick="window.printTableMap()"
                        class="rounded-xl bg-gradient-to-r from-amber-400 to-orange-400 px-5 py-2.5 text-xs font-bold text-slate-950 shadow-lg shadow-orange-500/20 transition-all active:scale-95">
                        <div class="flex items-center gap-2">
                            <i data-lucide="map" class="h-4 w-4"></i>

                            <span>PRINT LAYOUT</span>
                        </div>
                    </button>

                    <button @click="$store.theme.toggle(); $nextTick(() => { lucide.createIcons(); renderMap(); })"
                        class="rounded-full p-2 transition-transform hover:scale-110 active:rotate-90"
                        :class="isDark ?
                            'text-amber-400 hover:bg-slate-800' :
                            'text-slate-600 hover:bg-white shadow-sm'">
                        <i :data-lucide="isDark ? 'sun' : 'moon'" class="h-4 w-4"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- ── Flash ──────────────────────────────────────────────────── --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                class="mx-8 mt-4 flex items-center gap-2 rounded-2xl bg-emerald-100 p-4 text-sm font-bold text-emerald-700">
                <i data-lucide="check-circle" class="h-4 w-4"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- ── Canvas Area ─────────────────────────────────────────────── --}}
        <div class="relative flex-1 overflow-hidden transition-colors duration-500"
            :class="isDark ? 'bg-slate-950' : 'bg-slate-100'">
            <div id="table-map-react" data-props='@json($tableMapProps)' class="h-full w-full"></div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const wrapper = document.getElementById('activity-wrapper');
            const button = document.getElementById('btn-save-layout');

            // 1. Dengarkan update data dari React
            window.addEventListener('reactLayoutUpdated', (e) => {
                const {
                    isTableLayoutChanged
                } = e.detail;

                console.log(window.newTablesData)


                // Tampilkan/sembunyikan wrapper button mirip seperti tag <Activity>
                if (isTableLayoutChanged) {
                    wrapper.classList.remove('hidden');
                } else {
                    wrapper.classList.add('hidden');
                }
            });

            // 2. Aksi saat tombol di Blade diklik
            button.addEventListener('click', () => {
                // Ambil data terbaru yang sudah ditaruh React di window object
                const latestData = window.newTablesData;


                if (latestData && window.isTableLayoutChanged) {
                    // Karena Anda menggunakan Inertia, panggil router globalnya via window
                    // Pastikan @inertiaHead / @inertia sudah meload core Inertia ke window
                    if (window.Inertia) {
                        window.Inertia.post('/table/update-layout', {
                            tables: latestData
                        });
                    } else if (window.modules && window.modules.router) {
                        // Jika Anda mengekspos router Inertia v6 secara custom
                        window.modules.router.post('/table/update-layout', {
                            tables: latestData
                        });
                    } else {
                        // Alternatif fallback jika object Inertia global tidak terikat:
                        // Menggunakan fetch bawaan browser (tetap mengarah ke controller yang sama)
                        fetch('/table/update-layout', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .content,
                            },
                            body: JSON.stringify({
                                tables: latestData
                            })
                        }).then(() => {
                            window.location.reload(); // Refresh halaman setelah sukses
                        });
                    }
                }
            });
        });
    </script>

</x-admin-layout>
