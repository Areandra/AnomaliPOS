@extends('layouts.admin', ['title' => 'Management > Menu Items > New Menu Item'])

@section('content')
<div class="p-8 max-w-5xl mx-auto" x-data="{ price: 0, cost: 0, isAvailable: true }">
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-2xl overflow-hidden">
        <div class="px-10 py-8 flex justify-between items-center border-b bg-gray-50/50 dark:bg-white/5 dark:border-white/5">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-2xl bg-orange-100 text-orange-600 dark:bg-amber-500/10 dark:text-amber-500">
                    <i data-lucide="utensils" class="w-6 h-6"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black uppercase tracking-tight dark:text-white">Register Menu Item</h2>
                    <p class="text-[10px] font-black uppercase tracking-widest opacity-50">Cataloging food, beverages, and pricing</p>
                </div>
            </div>
            <a href="/menu" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-white/10 text-gray-400"><i data-lucide="x" class="w-6 h-6"></i></a>
        </div>

        <form class="p-10 grid grid-cols-1 md:grid-cols-12 gap-12">
            <div class="md:col-span-7 space-y-8">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] mb-3 opacity-50">Item Product Name</label>
                    <input type="text" placeholder="e.g. Signature Wagyu Burger" class="w-full px-6 py-4 rounded-2xl border bg-gray-50 border-gray-200 dark:bg-slate-950 dark:border-white/5 dark:text-white outline-none font-bold text-sm focus:border-orange-500 transition-all">
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] mb-3 opacity-50">Category</label>
                        <select class="w-full px-6 py-4 rounded-2xl border bg-gray-50 border-gray-200 dark:bg-slate-950 dark:border-white/5 dark:text-white outline-none font-bold text-sm">
                            @foreach($categoryOptions as $cat)
                                <option value="{{ $cat['id'] }}">{{ strtoupper($cat['name']) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] mb-3 opacity-50">SKU Code</label>
                        <input type="text" placeholder="FD-001" class="w-full px-6 py-4 rounded-2xl border bg-gray-50 border-gray-200 dark:bg-slate-950 dark:border-white/5 dark:text-white outline-none font-bold text-sm">
                    </div>
                </div>
            </div>

            <div class="md:col-span-5 space-y-8">
                <div class="p-8 rounded-3xl border bg-gray-50 border-gray-200 dark:bg-white/5 dark:border-white/10">
                    <div class="flex items-center gap-2 mb-6">
                        <i data-lucide="wallet" class="text-emerald-500 w-4 h-4"></i>
                        <span class="text-[10px] font-black uppercase tracking-widest dark:text-white">Finance & Pricing</span>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[9px] font-black uppercase mb-2 opacity-50">Selling Price</label>
                            <input type="number" x-model="price" class="w-full px-5 py-4 rounded-xl border bg-white dark:bg-slate-950 dark:border-white/5 dark:text-white font-black text-lg outline-none">
                        </div>
                        <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-between">
                            <span class="text-[9px] font-black uppercase text-emerald-600">Est. Price</span>
                            <span class="text-sm font-black tracking-tight text-emerald-700 dark:text-white">Rp <span x-text="Number(price).toLocaleString('id-ID')"></span></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:col-span-12 pt-8 border-t dark:border-white/5 flex justify-between items-center">
                <button type="button" @click="isAvailable = !isAvailable" :class="isAvailable ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-red-50 border-red-200 text-red-700'" class="flex items-center gap-2 px-6 py-3 rounded-xl border transition-all">
                    <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest" x-text="isAvailable ? 'In Stock' : 'Out of Stock'"></span>
                </button>
                <div class="flex gap-4">
                    <a href="/menu" class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-gray-500">Discard</a>
                    <button type="submit" class="px-12 py-4 rounded-2xl bg-slate-900 text-white dark:bg-white dark:text-slate-950 font-black text-[10px] uppercase tracking-widest shadow-xl">Register Item</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection