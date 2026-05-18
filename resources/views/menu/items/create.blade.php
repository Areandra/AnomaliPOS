@extends('layouts.admin')
@section('title', 'Tambah Menu Item')
@section('page_title', 'Management > Menu Items > New Menu Item')

@section('content')
    <div x-data="menuCreate()" x-init="$store.theme.init();
    $nextTick(() => lucide.createIcons())" class="">
        <div class="mx-auto h-full max-w-6xl p-8">
            <div :class="$store.theme.isDark ? 'bg-slate-900 border-white/5' : 'bg-white border-gray-100'"
                class="overflow-hidden rounded-[2.5rem] border shadow-2xl transition-all duration-500">

                {{-- Header --}}
                <div :class="$store.theme.isDark ? 'border-white/5 bg-white/5' : 'border-gray-50 bg-gray-50/50'"
                    class="flex items-center justify-between border-b px-10 py-8">
                    <div class="flex items-center gap-4">
                        <div :class="$store.theme.isDark ? 'bg-amber-500/10 text-amber-500' : 'bg-orange-100 text-orange-600'"
                            class="rounded-2xl p-3">
                            <i data-lucide="utensils" class="h-6 w-6"></i>
                        </div>
                        <div>
                            <h2 :class="$store.theme.isDark ? 'text-white' : 'text-slate-800'"
                                class="text-xl font-black uppercase tracking-tight">Register Menu Item</h2>
                            <p class="text-[10px] font-black uppercase tracking-widest opacity-50">Cataloging food,
                                beverages, and pricing</p>
                        </div>
                    </div>
                    <a href="{{ route('menu.items.index') }}"
                        :class="$store.theme.isDark ? 'hover:bg-white/10 text-gray-400' : 'hover:bg-gray-200 text-gray-600'"
                        class="rounded-full p-2 transition-all">
                        <i data-lucide="x" class="h-6 w-6"></i>
                    </a>
                </div>

                @if ($errors->any())
                    <div class="mx-10 mt-6 rounded-2xl bg-red-100 p-4 text-sm text-red-700">
                        <ul class="list-inside list-disc">
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('menu.items.store') }}" method="POST" enctype="multipart/form-data" class="p-10">
                    @csrf
                    <div class="grid grid-cols-1 gap-12 md:grid-cols-12">
                        {{-- Left: Essential Details --}}
                        <div class="space-y-8 md:col-span-7">
                            <div class="group relative">
                                <label
                                    :class="$store.theme.isDark ? 'text-gray-500 group-focus-within:text-amber-500' :
                                        'text-gray-400 group-focus-within:text-orange-600'"
                                    class="mb-3 block text-[10px] font-black uppercase tracking-[0.2em] transition-colors">Item
                                    Product Name</label>
                                <div class="relative">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30"><i
                                            data-lucide="utensils" class="h-[18px] w-[18px]"></i></div>
                                    <input type="text" name="name" value="{{ old('name') }}" required
                                        :class="$store.theme.isDark ?
                                            'bg-slate-950 border-white/5 text-white focus:border-amber-500/50' :
                                            'bg-gray-50 border-gray-200 text-slate-800 focus:bg-white'"
                                        class="w-full rounded-2xl border py-4 pl-12 pr-4 text-sm font-bold outline-none transition-all"
                                        placeholder="e.g. Signature Wagyu Burger" />
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <div class="group relative">
                                    <label
                                        class="mb-3 block text-[10px] font-black uppercase tracking-[0.2em] opacity-50">Category</label>
                                    <div class="relative">
                                        <div class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30"><i
                                                data-lucide="layers" class="h-[18px] w-[18px]"></i></div>
                                        <select name="category_id" required
                                            :class="$store.theme.isDark ? 'bg-slate-950 border-white/5 text-white' :
                                                'bg-gray-50 border-gray-200 text-slate-800 focus:bg-white'"
                                            class="w-full appearance-none rounded-2xl border py-4 pl-12 pr-10 text-sm font-bold outline-none transition-all">
                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat->id }}"
                                                    {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                                    {{ strtoupper($cat->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="group relative">
                                    <label
                                        class="mb-3 block text-[10px] font-black uppercase tracking-[0.2em] opacity-50">SKU
                                        Code</label>
                                    <div class="relative">
                                        <div class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30"><i
                                                data-lucide="tag" class="h-[18px] w-[18px]"></i></div>
                                        <input type="text" name="sku" value="{{ old('sku') }}"
                                            :class="$store.theme.isDark ? 'bg-slate-950 border-white/5 text-white' :
                                                'bg-gray-50 border-gray-200'"
                                            class="w-full rounded-2xl border py-4 pl-12 pr-4 text-sm font-bold outline-none transition-all"
                                            placeholder="FD-001" />
                                    </div>
                                </div>
                            </div>

                            <div class="group relative">
                                <label class="mb-3 block text-[10px] font-black uppercase tracking-[0.2em] opacity-50">Item
                                    Description</label>
                                <div class="relative">
                                    <div class="absolute left-4 top-4 opacity-30"><i data-lucide="file-text"
                                            class="h-[18px] w-[18px]"></i></div>
                                    <textarea name="description" rows="3"
                                        :class="$store.theme.isDark ? 'bg-slate-950 border-white/5 text-white' :
                                            'bg-gray-50 border-gray-200'"
                                        class="min-h-30 w-full rounded-2xl border py-4 pl-12 pr-4 text-sm font-bold outline-none transition-all"
                                        placeholder="Ingredients, allergen info, etc...">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Right: Pricing & Image --}}
                        <div class="space-y-8 md:col-span-5">
                            <div :class="$store.theme.isDark ? 'bg-white/5 border-white/10' : 'bg-gray-50 border-gray-200'"
                                class="rounded-3xl border p-8">
                                <div class="mb-6 flex items-center gap-2">
                                    <i data-lucide="wallet" class="h-4 w-4 text-emerald-500"></i>
                                    <span :class="$store.theme.isDark ? 'text-white' : 'text-slate-800'"
                                        class="text-[10px] font-black uppercase tracking-widest">Finance & Pricing</span>
                                </div>
                                <div class="space-y-6">
                                    <div>
                                        <label
                                            class="mb-2 block text-[9px] font-black uppercase tracking-widest opacity-50">Selling
                                            Price</label>
                                        <input type="number" name="price" value="{{ old('price', 0) }}" min="0"
                                            step="100" required
                                            :class="$store.theme.isDark ? 'bg-slate-950 border-white/5 text-white' :
                                                'bg-white border-gray-200 text-slate-900'"
                                            class="w-full rounded-xl border px-5 py-4 text-lg font-black tracking-tighter outline-none" />
                                    </div>
                                    <div>
                                        <label
                                            class="mb-2 block text-[9px] font-black uppercase tracking-widest opacity-50">Production
                                            Cost (COGS)</label>
                                        <input type="number" name="cost_of_goods" value="{{ old('cost_of_goods', 0) }}"
                                            min="0" step="100"
                                            :class="$store.theme.isDark ? 'bg-slate-950 border-white/5 text-white' :
                                                'bg-white border-gray-200 text-slate-900'"
                                            class="w-full rounded-xl border px-5 py-4 text-lg font-black tracking-tighter outline-none" />
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <label class="mb-3 block text-[10px] font-black uppercase tracking-[0.2em] opacity-50">Item
                                    Media</label>
                                <div class="relative">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30"><i data-lucide="image"
                                            class="h-[18px] w-[18px]"></i></div>
                                    <input type="file" name="image" accept="image/*"
                                        :class="$store.theme.isDark ? 'bg-slate-950 border-white/5 text-white' :
                                            'bg-gray-50 border-gray-200'"
                                        class="w-full rounded-2xl border py-4 pl-12 pr-4 text-xs font-bold outline-none" />
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div :class="$store.theme.isDark ? 'border-white/5' : 'border-gray-100'"
                        class="mt-12 flex items-center justify-between border-t pt-8">
                        <div class="flex items-center gap-6">
                            <label class="flex cursor-pointer items-center gap-2">
                                <input type="checkbox" name="is_available" value="1" checked
                                    class="h-4 w-4 rounded accent-orange-500">
                                <span
                                    :class="$store.theme.isDark ? 'bg-emerald-500/10 border-emerald-500/50 text-emerald-400' :
                                        'bg-emerald-50 border-emerald-200 text-emerald-700'"
                                    class="flex items-center gap-2 rounded-xl border px-5 py-3 text-[10px] font-black uppercase tracking-widest">
                                    <i data-lucide="check-circle-2" class="h-4 w-4"></i> In Stock
                                </span>
                            </label>
                        </div>
                        <div class="flex items-center gap-4">
                            <a href="{{ route('menu.items.index') }}"
                                :class="$store.theme.isDark ? 'text-gray-400 hover:bg-white/5' :
                                    'text-gray-500 hover:bg-gray-100'"
                                class="rounded-2xl px-8 py-4 text-[10px] font-black uppercase tracking-widest transition-all">Discard</a>
                            <button type="submit"
                                :class="$store.theme.isDark ? 'bg-white text-slate-950 hover:bg-gray-200' :
                                    'bg-slate-900 text-white hover:bg-slate-800 shadow-slate-200'"
                                class="flex items-center gap-2 rounded-2xl px-12 py-4 text-[10px] font-black uppercase tracking-widest shadow-xl transition-all active:scale-95">
                                <i data-lucide="save" class="h-3.5 w-3.5"></i> Register Item
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function menuCreate() {
            return {}
        }
    </script>
@endsection
