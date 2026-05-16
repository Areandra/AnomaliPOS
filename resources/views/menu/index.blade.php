@extends('layouts.admin')

@section('title', 'Menu Management')
@section('page_title', 'Management > Menu Items & Categories')

@section('content')
<div class="flex h-full transition-colors duration-500" x-data="menuIndex()">
    {{-- Sidebar Kategori Kiri --}}
    @include('components.menu-categories')

    {{-- Area Konten Utama Kanan --}}
    {{-- PERBAIKAN: Menambahkan :class dinamis untuk latar belakang --}}
    <div 
        :class="$store.theme.isDark ? 'bg-slate-950' : 'bg-[#FDFBF7]'" 
        class="flex-1 flex flex-col overflow-hidden transition-colors duration-500 relative"
    >
        
        {{-- Pola Background Dot (Hanya muncul di mode terang agar estetik) --}}
        <template x-if="!$store.theme.isDark">
            <div class="absolute inset-0 opacity-40 pointer-events-none"
                 style="background-image: radial-gradient(#e5e7eb 1px, transparent 1px); background-size: 20px 20px;">
            </div>
        </template>

        {{-- Header Konten --}}
        <div :class="$store.theme.isDark ? 'bg-slate-900/70 border-white/5' : 'bg-white/70 border-gray-200/50'"
             class="sticky top-0 z-40 w-full px-8 py-4 backdrop-blur-xl border-b transition-all duration-300">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 w-full text-gray-900 dark:text-white">
                <div class="flex items-center gap-4">
                    <div :class="$store.theme.isDark ? 'bg-slate-800 text-amber-500 shadow-lg shadow-black/20' : 'bg-orange-100 text-orange-600 shadow-sm'"
                         class="p-2.5 rounded-xl">
                        <i data-lucide="utensils" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h1 class="font-bold text-lg leading-none tracking-tight">Menu Items</h1>
                        <p class="text-[10px] mt-1 uppercase font-bold tracking-widest text-gray-500 dark:text-gray-400">
                            Manage catalog, pricing, and availability
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-4 w-full md:w-auto">
                    <div :class="$store.theme.isDark ? 'bg-slate-900 border-white/10 focus-within:border-amber-500/50' : 'bg-white border-gray-200 focus-within:border-orange-500'"
                         class="flex items-center gap-3 flex-1 p-3 px-5 border rounded-full transition-all shadow-sm">
                        <i data-lucide="search" class="w-[18px] h-[18px] text-gray-400 dark:text-slate-500"></i>
                        <input type="text" placeholder="Search menu or SKU..."
                               class="bg-transparent border-none outline-none w-full text-sm font-medium dark:text-white text-gray-900" />
                        
                        <button @click="$store.theme.toggle()"
                                class="rounded-full transition-transform active:rotate-90 hover:scale-110 text-slate-600 dark:text-amber-400">
                            <i data-lucide="sun" class="w-[18px] h-[18px]" x-show="$store.theme.isDark"></i>
                            <i data-lucide="moon" class="w-[18px] h-[18px]" x-show="!$store.theme.isDark"></i>
                        </button>
                    </div>

                    <a href="/menu/create"
                       class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all active:scale-95 flex items-center gap-2 shadow-lg bg-slate-900 text-white dark:bg-white dark:text-slate-950 hover:bg-slate-800">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Grid Content --}}
        <div class="flex-1 overflow-y-auto p-8 scrollbar-hide relative z-10">
            
            {{-- Bagian Nasi --}}
            <div class="mb-10">
                <h2 class="text-sm uppercase tracking-[0.2em] font-black mb-6 flex items-center gap-3 text-orange-600 dark:text-amber-500">
                    <span class="h-1.5 w-1.5 rounded-full bg-orange-600 dark:bg-amber-500"></span>
                    NASI
                    <span class="flex-1 h-px bg-gray-200 dark:bg-white/10"></span>
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6">
                    {{-- Card Menu Item --}}
                    <div :class="$store.theme.isDark ? 'border-white/10 bg-slate-950 text-white shadow-[0_20px_80px_-40px_rgba(15,23,42,0.4)] hover:shadow-[0_30px_90px_-30px_rgba(15,23,42,0.5)]' : 'border-gray-200 bg-white text-slate-900 shadow-[0_20px_80px_-40px_rgba(15,23,42,0.2)] hover:shadow-[0_30px_90px_-30px_rgba(15,23,42,0.18)]'"
                         class="group relative flex flex-col overflow-hidden rounded-[32px] transition-all duration-300 hover:-translate-y-1">
                        
                        {{-- Availability Badge --}}
                        <div :class="$store.theme.isDark ? 'border-red-500/20 bg-slate-900 text-red-400' : 'border-red-100 bg-white text-red-600'"
                             class="absolute right-4 top-4 z-10 rounded-full px-3 py-1 text-[10px] font-extrabold uppercase tracking-[0.24em] shadow-sm">
                            SOLD OUT
                        </div>

                        {{-- Image --}}
                        <div class="relative h-44 overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=400&h=300&auto=format&fit=crop"
                                 class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" />
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/50 via-transparent to-transparent"></div>
                        </div>

                        {{-- Details --}}
                        <div class="flex flex-1 flex-col gap-4 p-6">
                            <div>
                                <div class="flex items-center gap-2" :class="$store.theme.isDark ? 'text-gray-400' : 'text-gray-500'">
                                    <i data-lucide="tag" class="w-3 h-3 text-orange-500"></i>
                                    <span class="text-[10px] font-black uppercase tracking-[0.25em]">NG-001</span>
                                </div>
                                <h3 :class="$store.theme.isDark ? 'text-white' : 'text-slate-900'" class="mt-3 text-xl font-black uppercase tracking-tight">NASI GORENG</h3>
                            </div>

                            <div class="mt-auto flex flex-col gap-4">
                                <div class="flex items-baseline gap-2">
                                    <span :class="$store.theme.isDark ? 'text-gray-400' : 'text-gray-500'" class="text-sm font-bold">Rp</span>
                                    <span class="text-2xl font-black tracking-tight">15000.00</span>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <button class="flex items-center justify-center gap-2 rounded-2xl bg-emerald-500/10 px-4 py-3 text-[10px] font-black uppercase tracking-[0.24em] text-emerald-600 transition hover:bg-emerald-500 hover:text-white">
                                        <i data-lucide="eye" class="w-3 h-3"></i>
                                        Show
                                    </button>
                                    <a :class="$store.theme.isDark ? 'border-white/10 bg-slate-900 text-slate-200 hover:bg-slate-800' : 'border-slate-200 bg-slate-50 text-slate-700 hover:border-slate-300 hover:bg-slate-100'"
                                       class="flex items-center justify-center gap-2 rounded-2xl border px-4 py-3 text-[10px] font-black uppercase tracking-[0.24em] transition">
                                        <i data-lucide="edit-3" class="w-3 h-3"></i>
                                        Edit
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bagian Ayam --}}
            <div class="mb-6">
                <h2 class="text-sm uppercase tracking-[0.2em] font-black mb-6 flex items-center gap-3 text-orange-600 dark:text-amber-500">
                    <span class="h-1.5 w-1.5 rounded-full bg-orange-600 dark:bg-amber-500"></span>
                    AYAM
                    <span class="flex-1 h-px bg-gray-200 dark:bg-white/10"></span>
                </h2>
                {{-- Grid Items Ayam... --}}
            </div>

        </div>
    </div>
</div>

<script>
    function menuIndex() {
        return {
            init() {
                this.$nextTick(() => lucide.createIcons());
                // Sinkronkan ikon saat tema berubah
                this.$watch('$store.theme.isDark', () => {
                    this.$nextTick(() => lucide.createIcons());
                });
            }
        }
    }
</script>
@endsection