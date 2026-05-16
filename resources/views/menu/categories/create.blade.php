@extends('layouts.admin')

@php
    // SIMULASI DATA DUMMY
    // Cek apakah route saat ini adalah edit
    $isEdit = request()->routeIs('category.edit'); 
    
    $category = $isEdit ? (object) [
        'id' => 1,
        'name' => 'Nasi',
        'description' => 'Segala jenis hidangan nasi goreng dan nasi putih.',
        'sort_order' => 1,
    ] : null;

    $pageTitle = $isEdit ? "Management > Categories > {$category->name} > Update" : "Management > Categories > New Category";
@endphp

@section('title', $isEdit ? 'Edit Category' : 'Create Category')
@section('page_subtitle', $pageTitle)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl border dark:border-white/5 overflow-hidden">
        
        {{-- Header --}}
        <div class="px-10 py-8 border-b dark:border-white/5 flex justify-between items-center bg-gray-50/50 dark:bg-white/5">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-2xl bg-orange-100 text-orange-600 dark:bg-amber-500/10 dark:text-amber-500">
                    <i data-lucide="layers" size="24"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black uppercase tracking-tight dark:text-white">
                        {{ $isEdit ? 'Update Category' : 'Save Category' }}
                    </h2>
                    <p class="text-[10px] font-black uppercase tracking-widest opacity-50 dark:text-gray-400">
                        {{ $isEdit ? 'Modify existing menu category' : 'Define a new group for your menu items' }}
                    </p>
                </div>
            </div>
            <a href="/menu" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-white/10 text-gray-400">
                <i data-lucide="x" size="24"></i>
            </a>
        </div>

        <form class="p-10 space-y-8">
            {{-- Name Input --}}
            <div>
                <label class="block text-[10px] font-black uppercase tracking-[0.2em] mb-3 opacity-50 dark:text-gray-400">Category Name</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-6 flex items-center pointer-events-none opacity-30">
                        <i data-lucide="tag" size="18"></i>
                    </div>
                    <input type="text" value="{{ $category->name ?? '' }}" placeholder="e.g. Beverages"
                           class="w-full pl-14 pr-6 py-4 rounded-2xl border bg-gray-50 dark:bg-slate-950 dark:border-white/5 dark:text-white outline-none font-bold focus:border-orange-500 transition-all">
                </div>
            </div>

            {{-- Description Input --}}
            <div>
                <label class="block text-[10px] font-black uppercase tracking-[0.2em] mb-3 opacity-50 dark:text-gray-400">Description (Optional)</label>
                <textarea class="w-full px-6 py-4 rounded-2xl border bg-gray-50 dark:bg-slate-950 dark:border-white/5 dark:text-white min-h-[120px] outline-none" 
                          placeholder="Brief explanation about this category...">{{ $category->description ?? '' }}</textarea>
            </div>

            {{-- Sort Order --}}
            <div class="p-8 rounded-3xl border dark:bg-white/5 dark:border-white/10 bg-gray-50">
                <div class="flex items-center gap-2 mb-4">
                    <i data-lucide="hash" class="text-orange-600" size="16"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest dark:text-white">Display Priority</span>
                </div>
                <div class="flex items-center gap-6">
                    <input type="number" value="{{ $category->sort_order ?? 0 }}" 
                           class="w-32 px-5 py-4 rounded-xl border dark:bg-slate-950 dark:border-white/5 dark:text-white font-black text-center outline-none">
                    <p class="text-[10px] font-medium text-gray-500 italic">
                        Urutan menentukan posisi kategori pada menu pelanggan & dashboard kasir.
                    </p>
                </div>
            </div>

            {{-- Footer / Actions --}}
            <div class="pt-8 border-t dark:border-white/5 flex justify-end items-center gap-4">
                <a href="/menu" class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-gray-500 hover:text-gray-700 transition-colors">
                    Discard
                </a>
                <button type="submit" class="px-12 py-4 rounded-2xl bg-slate-900 text-white dark:bg-white dark:text-slate-950 font-black text-[10px] uppercase shadow-xl shadow-slate-200 dark:shadow-none transition-all active:scale-95">
                    <div class="flex items-center gap-2">
                        <i data-lucide="save" size="14"></i>
                        <span>{{ $isEdit ? 'Update Category' : 'Save Category' }}</span>
                    </div>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection