@extends('layouts.admin')
@section('title', 'Kategori Menu')
@section('page_title', 'Menu > Kategori')

@section('content')
<div x-init="$store.theme.init(); $nextTick(() => lucide.createIcons())" class="h-full flex flex-col overflow-hidden">

    {{-- Header --}}
    <div :class="$store.theme.isDark ? 'bg-slate-900/70 border-white/5' : 'bg-white/70 border-gray-200/50'"
        class="sticky top-0 z-40 w-full px-8 py-4 backdrop-blur-xl border-b">
        <div class="flex justify-between items-center gap-6">
            <div class="flex items-center gap-3">
                <div :class="$store.theme.isDark ? 'bg-slate-800 text-amber-500' : 'bg-orange-100 text-orange-600'"
                    class="p-2.5 rounded-xl">
                    <i data-lucide="layers" class="w-6 h-6"></i>
                </div>
                <div>
                    <h1 class="font-bold text-lg leading-none tracking-tight">Kategori Menu</h1>
                    <p class="text-[10px] mt-1 uppercase font-bold tracking-widest opacity-50">Kelola kategori menu</p>
                </div>
            </div>
            <a href="{{ route('menu.categories.create') }}"
                :class="$store.theme.isDark ? 'bg-white text-slate-950 hover:bg-gray-200' : 'bg-slate-900 text-white hover:bg-slate-800'"
                class="px-5 py-2.5 rounded-xl text-xs font-bold flex items-center gap-2 shadow-lg transition-all active:scale-95">
                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Kategori
            </a>
        </div>
    </div>

    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
        class="mx-8 mt-4 p-4 rounded-2xl bg-emerald-100 text-emerald-700 text-sm font-bold flex items-center gap-2">
        <i data-lucide="check-circle" class="w-4 h-4"></i> {{ session('success') }}
    </div>
    @endif

    <div class="flex-1 overflow-y-auto p-8">
        @if($categories->isEmpty())
        <div class="flex flex-col items-center justify-center py-32 opacity-30">
            <i data-lucide="layers" class="w-16 h-16 mb-4"></i>
            <p class="font-black uppercase tracking-widest text-xs">Belum ada kategori</p>
        </div>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($categories as $cat)
            <div :class="$store.theme.isDark ? 'bg-slate-900/50 border-white/5 hover:bg-slate-800' : 'bg-white border-gray-100 hover:shadow-xl'"
                class="group p-6 rounded-3xl border transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-start justify-between mb-4">
                    <div :class="$store.theme.isDark ? 'bg-slate-800 text-amber-500' : 'bg-orange-50 text-orange-600'"
                        class="p-2.5 rounded-xl">
                        <i data-lucide="tag" class="w-5 h-5"></i>
                    </div>
                    <span :class="$store.theme.isDark ? 'bg-white/5 text-gray-400' : 'bg-gray-100 text-gray-500'"
                        class="text-[9px] font-black uppercase tracking-widest px-2 py-1 rounded-full">
                        #{{ $cat->sort_order }}
                    </span>
                </div>
                <h3 :class="$store.theme.isDark ? 'text-white' : 'text-slate-800'"
                    class="font-black text-sm uppercase tracking-tight mb-1">{{ $cat->name }}</h3>
                <p class="text-[10px] opacity-50 mb-4 line-clamp-2">{{ $cat->description ?? '-' }}</p>
                <div class="flex items-center justify-between">
                    <span :class="$store.theme.isDark ? 'text-amber-500' : 'text-orange-600'"
                        class="text-[10px] font-black">{{ $cat->items_count }} item</span>
                    <div class="flex gap-2">
                        <a href="{{ route('menu.categories.edit', $cat->id) }}"
                            :class="$store.theme.isDark ? 'bg-white/5 hover:bg-white/10 text-gray-300' : 'bg-gray-100 hover:bg-slate-900 hover:text-white text-slate-600'"
                            class="p-2 rounded-xl transition-all">
                            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                        </a>
                        <form action="{{ route('menu.categories.destroy', $cat->id) }}" method="POST"
                            onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="p-2 rounded-xl transition-all bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
