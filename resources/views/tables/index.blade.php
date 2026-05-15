@extends('layouts.admin')

@section('title', 'Manajemen Meja')
@section('page_title', 'Management > Tables')

@section('content')
<div x-data="tableIndex({{ json_encode($tables->map(fn($t) => ['id' => $t->id, 'tableNumber' => $t->table_number, 'capacity' => $t->capacity, 'status' => $t->status, 'facing' => $t->facing, 'vertical' => $t->vertical])->values()) }})"
    x-init="$store.theme.init(); $nextTick(() => lucide.createIcons())"
    class="h-full flex flex-col overflow-hidden">

    {{-- Header --}}
    <div :class="$store.theme.isDark ? 'bg-slate-900/70 border-white/5' : 'bg-white/70 border-gray-200/50'"
        class="sticky top-0 z-40 w-full px-8 py-4 backdrop-blur-xl border-b">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6 w-full">
            <div class="flex items-center gap-3">
                <div :class="$store.theme.isDark ? 'bg-slate-800 text-amber-500' : 'bg-orange-100 text-orange-600'"
                    class="p-2.5 rounded-xl">
                    <i data-lucide="map" class="w-6 h-6"></i>
                </div>
                <div>
                    <h1 class="font-bold text-lg leading-none tracking-tight">Table Management</h1>
                    <p :class="$store.theme.isDark ? 'text-gray-400' : 'text-gray-500'"
                        class="text-[10px] mt-1 uppercase font-bold tracking-widest">Kelola meja restoran</p>
                </div>
            </div>
            <div class="flex items-center gap-4 w-full md:w-auto">
                <div :class="$store.theme.isDark ? 'bg-slate-900 border-white/10' : 'bg-white border-gray-200'"
                    class="flex items-center gap-3 flex-1 p-3 px-5 border rounded-full shadow-sm">
                    <i data-lucide="search" class="w-[18px] h-[18px] opacity-40"></i>
                    <input type="text" x-model="search" placeholder="Cari nomor meja..."
                        class="bg-transparent border-none outline-none w-full text-sm font-medium" />
                </div>
                <a href="{{ route('tables.create') }}"
                    :class="$store.theme.isDark ? 'bg-white text-slate-950 hover:bg-gray-200' : 'bg-slate-900 text-white hover:bg-slate-800'"
                    class="px-5 py-2.5 rounded-xl text-xs font-bold flex items-center gap-2 shadow-lg transition-all active:scale-95">
                    <i data-lucide="plus" class="w-4 h-4"></i> Tambah Meja
                </a>
            </div>
        </div>
    </div>

    {{-- Flash --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
        class="mx-8 mt-4 p-4 rounded-2xl bg-emerald-100 text-emerald-700 text-sm font-bold flex items-center gap-2">
        <i data-lucide="check-circle" class="w-4 h-4"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Grid --}}
    <div class="flex-1 overflow-y-auto p-8">
        <template x-if="filtered.length === 0">
            <div class="flex flex-col items-center justify-center py-32 opacity-30">
                <i data-lucide="map" class="w-16 h-16 mb-4"></i>
                <p class="font-black uppercase tracking-widest text-xs">Belum ada meja</p>
            </div>
        </template>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
            <template x-for="t in filtered" :key="t.id">
                <div :class="$store.theme.isDark ? 'bg-slate-900/50 border-white/5 hover:bg-slate-800' : 'bg-white border-gray-100 hover:shadow-xl'"
                    class="group relative p-5 rounded-3xl border transition-all duration-300 hover:-translate-y-1 flex flex-col items-center gap-3">

                    <div :class="{
                            'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400': t.status === 'available',
                            'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400': t.status === 'occupied',
                            'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400': t.status === 'waiting_payment'
                        }"
                        class="absolute top-3 right-3 px-2 py-0.5 rounded-full text-[7px] font-black uppercase tracking-widest"
                        x-text="t.status.replace('_', ' ')">
                    </div>

                    <div :class="$store.theme.isDark ? 'bg-slate-800 text-amber-500' : 'bg-orange-50 text-orange-600'"
                        class="w-14 h-14 rounded-2xl flex items-center justify-center">
                        <i data-lucide="armchair" class="w-7 h-7"></i>
                    </div>

                    <div class="text-center">
                        <p :class="$store.theme.isDark ? 'text-white' : 'text-slate-800'"
                            class="font-black text-sm uppercase tracking-tight" x-text="'Meja ' + t.tableNumber"></p>
                        <p class="text-[10px] opacity-50 font-bold" x-text="t.capacity + ' kursi'"></p>
                    </div>

                    <div class="flex gap-2 w-full mt-1">
                        <a :href="'/tables/' + t.id + '/edit'"
                            :class="$store.theme.isDark ? 'bg-white/5 hover:bg-white/10 text-gray-300' : 'bg-gray-100 hover:bg-slate-900 hover:text-white text-slate-600'"
                            class="flex-1 flex items-center justify-center py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">
                            <i data-lucide="edit-3" class="w-3 h-3"></i>
                        </a>
                        <button @click="deleteTable(t.id)"
                            class="flex-1 flex items-center justify-center py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white">
                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

<script>
function tableIndex(tables) {
    return {
        tables,
        search: '',
        get filtered() {
            return this.tables.filter(t =>
                t.tableNumber.toString().toLowerCase().includes(this.search.toLowerCase())
            )
        },
        async deleteTable(id) {
            if (!confirm('Hapus meja ini?')) return
            const form = document.createElement('form')
            form.method = 'POST'
            form.action = `/tables/${id}`
            form.innerHTML = `
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="DELETE">
            `
            document.body.appendChild(form)
            form.submit()
        }
    }
}
</script>
@endsection
