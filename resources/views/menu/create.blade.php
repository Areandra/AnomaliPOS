@extends('layouts.admin')

@php
    // SIMULASI DATA DUMMY
    // Di aplikasi nyata, variabel $menu ini dikirim dari Controller.
    // Untuk testing dummy, Anda bisa mengubah null menjadi true/false secara manual.
    $isEdit = request()->routeIs('menu.edit'); 
    
    $menu = $isEdit ? (object) [
        'id' => 1,
        'name' => 'Nasi Goreng Special',
        'category_id' => 1,
        'sku' => 'NG-001',
        'description' => 'Nasi goreng rempah dengan telur mata sapi.',
        'price' => 15000,
        'cost_of_goods' => 8000,
        'image_url' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=400',
        'is_available' => true
    ] : null;

    $pageTitle = $isEdit ? "Management > Menu Items > {$menu->name} > Update" : "Management > Menu Items > New Menu Item";
@endphp

@section('title', $isEdit ? 'Edit Menu' : 'Register Menu')
@section('page_subtitle', $pageTitle)

@section('content')
<div class="max-w-4xl mx-auto" 
     x-data="{ 
        price: {{ $menu->price ?? 0 }}, 
        cost: {{ $menu->cost_of_goods ?? 0 }}, 
        isAvailable: {{ ($menu->is_available ?? true) ? 'true' : 'false' }} 
     }">
    
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl border dark:border-white/5 overflow-hidden">
        
        {{-- Header Section --}}
        <div class="px-10 py-8 border-b dark:border-white/5 flex justify-between items-center bg-gray-50/50 dark:bg-white/5">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-2xl bg-orange-100 text-orange-600 dark:bg-amber-500/10 dark:text-amber-500">
                    <i data-lucide="utensils" size="24"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black uppercase tracking-tight dark:text-white">
                        {{ $isEdit ? 'Edit Menu Item' : 'Register Menu Item' }}
                    </h2>
                    <p class="text-[10px] font-black uppercase tracking-widest opacity-50 dark:text-gray-400">
                        {{ $isEdit ? 'Updating product details and pricing' : 'Cataloging food, beverages, and pricing' }}
                    </p>
                </div>
            </div>
            <a href="/menu" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-white/10 text-gray-400">
                <i data-lucide="x" size="24"></i>
            </a>
        </div>

        <form class="p-10 grid grid-cols-1 md:grid-cols-12 gap-12">
            {{-- Kolom Kiri --}}
            <div class="md:col-span-7 space-y-8">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] mb-3 opacity-50 dark:text-gray-400">Item Product Name</label>
                    <input type="text" value="{{ $menu->name ?? '' }}" placeholder="e.g. Signature Wagyu Burger"
                           class="w-full px-6 py-4 rounded-2xl border bg-gray-50 dark:bg-slate-950 dark:border-white/5 dark:text-white outline-none font-bold focus:border-orange-500 transition-all">
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] mb-3 opacity-50 dark:text-gray-400">Category</label>
                        <select class="w-full px-6 py-4 rounded-2xl border bg-gray-50 dark:bg-slate-950 dark:border-white/5 dark:text-white outline-none font-bold appearance-none">
                            <option value="1" {{ ($menu->category_id ?? '') == 1 ? 'selected' : '' }}>NASI</option>
                            <option value="2" {{ ($menu->category_id ?? '') == 2 ? 'selected' : '' }}>AYAM</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] mb-3 opacity-50 dark:text-gray-400">SKU Code</label>
                        <input type="text" value="{{ $menu->sku ?? '' }}" placeholder="FD-001"
                               class="w-full px-6 py-4 rounded-2xl border bg-gray-50 dark:bg-slate-950 dark:border-white/5 dark:text-white font-bold">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] mb-3 opacity-50 dark:text-gray-400">Item Description</label>
                    <textarea class="w-full px-6 py-4 rounded-2xl border bg-gray-50 dark:bg-slate-950 dark:border-white/5 dark:text-white min-h-[120px] outline-none" 
                              placeholder="Ingredients...">{{ $menu->description ?? '' }}</textarea>
                </div>
            </div>

            {{-- Kolom Kanan --}}
            <div class="md:col-span-5 space-y-8">
                <div class="p-8 rounded-3xl border dark:bg-white/5 dark:border-white/10 bg-gray-50 shadow-sm">
                    <div class="flex items-center gap-2 mb-6">
                        <i data-lucide="wallet" class="text-emerald-500" size="16"></i>
                        <span class="text-[10px] font-black uppercase tracking-widest dark:text-white">Finance & Pricing</span>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[9px] font-black uppercase mb-2 opacity-50 dark:text-gray-400">Selling Price</label>
                            <input type="number" x-model="price" class="w-full px-5 py-4 rounded-xl border dark:bg-slate-950 dark:border-white/5 dark:text-white font-black text-lg outline-none">
                        </div>
                        <div class="p-4 rounded-2xl bg-emerald-500/10 flex justify-between items-center">
                            <span class="text-[9px] font-black uppercase text-emerald-500 tracking-widest">Est. Margin</span>
                            <span class="text-sm font-black dark:text-white">Rp <span x-text="(price - cost).toLocaleString('id-ID')"></span></span>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="block text-[10px] font-black uppercase mb-3 opacity-50 dark:text-gray-400">Item Media</label>
                    <div class="h-44 rounded-3xl overflow-hidden border-2 border-dashed dark:border-white/10 relative group flex items-center justify-center bg-gray-50/50 dark:bg-white/5">
                        @if($isEdit && $menu->image_url)
                            <img src="{{ $menu->image_url }}" class="w-full h-full object-cover">
                        @else
                            <div class="text-center opacity-30">
                                <i data-lucide="image" size="32" class="mx-auto mb-2"></i>
                                <span class="text-[9px] font-black uppercase">No Preview</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Footer Section --}}
            <div class="md:col-span-12 pt-8 border-t dark:border-white/5 flex justify-between items-center">
                <button type="button" @click="isAvailable = !isAvailable" 
                        :class="isAvailable ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-red-50 border-red-200 text-red-700'" 
                        class="flex items-center gap-2 px-6 py-3 rounded-xl border transition-all duration-300">
                    <i data-lucide="check-circle" size="16"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest" x-text="isAvailable ? 'In Stock' : 'Out of Stock'"></span>
                </button>
                
                <div class="flex gap-4">
                    <a href="/menu" class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-gray-500">Discard</a>
                    <button type="submit" class="px-12 py-4 rounded-2xl bg-slate-900 text-white dark:bg-white dark:text-slate-950 font-black text-[10px] uppercase shadow-xl">
                        {{ $isEdit ? 'Update Item' : 'Register Item' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection