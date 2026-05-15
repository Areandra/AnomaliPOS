@extends('layouts.admin')
@section('title', 'Edit Menu Item')
@section('page_title', 'Management > Menu Items > ' . $item->name . ' > Update')

@section('content')
<div x-init="$store.theme.init(); $nextTick(() => lucide.createIcons())" class="">
    <div class="p-8 h-full max-w-6xl mx-auto">
        <div :class="$store.theme.isDark ? 'bg-slate-900 border-white/5' : 'bg-white border-gray-100'"
            class="rounded-[2.5rem] shadow-2xl transition-all duration-500 border overflow-hidden">

            {{-- Header --}}
            <div :class="$store.theme.isDark ? 'border-white/5 bg-white/5' : 'border-gray-50 bg-gray-50/50'"
                class="px-10 py-8 flex justify-between items-center border-b">
                <div class="flex items-center gap-4">
                    <div :class="$store.theme.isDark ? 'bg-amber-500/10 text-amber-500' : 'bg-orange-100 text-orange-600'" class="p-3 rounded-2xl">
                        <i data-lucide="utensils" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h2 :class="$store.theme.isDark ? 'text-white' : 'text-slate-800'" class="text-xl font-black uppercase tracking-tight">Edit Menu Item</h2>
                        <p class="text-[10px] font-black uppercase tracking-widest opacity-50">{{ $item->name }}</p>
                    </div>
                </div>
                <a href="{{ route('menu.items.index') }}"
                    :class="$store.theme.isDark ? 'hover:bg-white/10 text-gray-400' : 'hover:bg-gray-200 text-gray-600'"
                    class="p-2 rounded-full transition-all">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </a>
            </div>

            @if($errors->any())
            <div class="mx-10 mt-6 p-4 rounded-2xl bg-red-100 text-red-700 text-sm">
                <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <form action="{{ route('menu.items.update', $item->id) }}" method="POST" enctype="multipart/form-data" class="p-10">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-12 gap-12">
                    <div class="md:col-span-7 space-y-8">
                        <div class="relative group">
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] mb-3 opacity-50">Item Product Name</label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30"><i data-lucide="utensils" class="w-[18px] h-[18px]"></i></div>
                                <input type="text" name="name" value="{{ old('name', $item->name) }}" required
                                    :class="$store.theme.isDark ? 'bg-slate-950 border-white/5 text-white focus:border-amber-500/50' : 'bg-gray-50 border-gray-200 text-slate-800 focus:bg-white'"
                                    class="w-full pl-12 pr-4 py-4 rounded-2xl border text-sm font-bold transition-all outline-none" />
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div class="relative group">
                                <label class="block text-[10px] font-black uppercase tracking-[0.2em] mb-3 opacity-50">Category</label>
                                <div class="relative">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30"><i data-lucide="layers" class="w-[18px] h-[18px]"></i></div>
                                    <select name="category_id" required
                                        :class="$store.theme.isDark ? 'bg-slate-950 border-white/5 text-white' : 'bg-gray-50 border-gray-200 text-slate-800'"
                                        class="w-full pl-12 pr-10 py-4 rounded-2xl border text-sm font-bold transition-all outline-none appearance-none">
                                        @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id', $item->category_id) == $cat->id ? 'selected' : '' }}>{{ strtoupper($cat->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="relative group">
                                <label class="block text-[10px] font-black uppercase tracking-[0.2em] mb-3 opacity-50">SKU Code</label>
                                <div class="relative">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30"><i data-lucide="tag" class="w-[18px] h-[18px]"></i></div>
                                    <input type="text" name="sku" value="{{ old('sku', $item->sku) }}"
                                        :class="$store.theme.isDark ? 'bg-slate-950 border-white/5 text-white' : 'bg-gray-50 border-gray-200'"
                                        class="w-full pl-12 pr-4 py-4 rounded-2xl border text-sm font-bold transition-all outline-none"
                                        placeholder="FD-001" />
                                </div>
                            </div>
                        </div>

                        <div class="relative group">
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] mb-3 opacity-50">Item Description</label>
                            <div class="relative">
                                <div class="absolute left-4 top-4 opacity-30"><i data-lucide="file-text" class="w-[18px] h-[18px]"></i></div>
                                <textarea name="description" rows="3"
                                    :class="$store.theme.isDark ? 'bg-slate-950 border-white/5 text-white' : 'bg-gray-50 border-gray-200'"
                                    class="w-full pl-12 pr-4 py-4 rounded-2xl border text-sm font-bold transition-all outline-none min-h-30">{{ old('description', $item->description) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-5 space-y-8">
                        <div :class="$store.theme.isDark ? 'bg-white/5 border-white/10' : 'bg-gray-50 border-gray-200'" class="p-8 rounded-3xl border">
                            <div class="flex items-center gap-2 mb-6">
                                <i data-lucide="wallet" class="w-4 h-4 text-emerald-500"></i>
                                <span class="text-[10px] font-black uppercase tracking-widest">Finance & Pricing</span>
                            </div>
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-[9px] font-black uppercase tracking-widest mb-2 opacity-50">Selling Price</label>
                                    <input type="number" name="price" value="{{ old('price', $item->price) }}" min="0" step="100" required
                                        :class="$store.theme.isDark ? 'bg-slate-950 border-white/5 text-white' : 'bg-white border-gray-200 text-slate-900'"
                                        class="w-full px-5 py-4 rounded-xl border text-lg font-black tracking-tighter outline-none" />
                                </div>
                                <div>
                                    <label class="block text-[9px] font-black uppercase tracking-widest mb-2 opacity-50">Production Cost (COGS)</label>
                                    <input type="number" name="cost_of_goods" value="{{ old('cost_of_goods', $item->cost_of_goods) }}" min="0" step="100"
                                        :class="$store.theme.isDark ? 'bg-slate-950 border-white/5 text-white' : 'bg-white border-gray-200 text-slate-900'"
                                        class="w-full px-5 py-4 rounded-xl border text-lg font-black tracking-tighter outline-none" />
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            @if($item->image_url)
                            <div class="mb-2">
                                <p class="text-[10px] font-black uppercase tracking-widest opacity-60 mb-2">Current Image</p>
                                <img src="{{ asset('storage/' . $item->image_url) }}" alt="{{ $item->name }}"
                                    class="h-32 w-32 object-cover rounded-2xl border border-gray-200 dark:border-white/10">
                            </div>
                            @endif
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] mb-3 opacity-50">Replace Image (optional)</label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30"><i data-lucide="image" class="w-[18px] h-[18px]"></i></div>
                                <input type="file" name="image" accept="image/*"
                                    :class="$store.theme.isDark ? 'bg-slate-950 border-white/5 text-white' : 'bg-gray-50 border-gray-200'"
                                    class="w-full pl-12 pr-4 py-4 rounded-2xl border text-xs font-bold outline-none" />
                            </div>
                        </div>
                    </div>
                </div>

                <div :class="$store.theme.isDark ? 'border-white/5' : 'border-gray-100'"
                    class="mt-12 pt-8 border-t flex justify-between items-center">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_available" value="1" {{ old('is_available', $item->is_available) ? 'checked' : '' }}
                            class="w-4 h-4 rounded accent-orange-500">
                        <span class="{{ $item->is_available ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-red-50 border-red-200 text-red-700' }}
                            flex items-center gap-2 px-5 py-3 rounded-xl border text-[10px] font-black uppercase tracking-widest">
                            <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                            {{ $item->is_available ? 'In Stock' : 'Out of Stock' }}
                        </span>
                    </label>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('menu.items.index') }}"
                            :class="$store.theme.isDark ? 'text-gray-400 hover:bg-white/5' : 'text-gray-500 hover:bg-gray-100'"
                            class="px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all">Discard</a>
                        <button type="submit"
                            :class="$store.theme.isDark ? 'bg-white text-slate-950 hover:bg-gray-200' : 'bg-slate-900 text-white hover:bg-slate-800 shadow-slate-200'"
                            class="flex items-center gap-2 px-12 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl transition-all active:scale-95">
                            <i data-lucide="save" class="w-3.5 h-3.5"></i> Update Item
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
