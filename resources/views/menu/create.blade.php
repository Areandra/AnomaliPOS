@extends('layouts.admin')

@section('title', 'Manajemen Menu')
@section('page_title', isset($initialData) ? 'Management > Menu Items > Update' : 'Management > Menu Items > New Menu Item')

@section('content')

@php $atLeastPro = in_array(session('plan', 'starter'), ['pro', 'enterprise']); @endphp

<div
    x-data="menuItemForm({{ json_encode($initialData ?? null) }}, {{ json_encode($categoryOptions ?? []) }}, {{ $itemId ?? 'null' }})"
    x-init="$nextTick(() => lucide.createIcons())"
    class="p-8 max-w-6xl mx-auto"
>
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
                    <h2 :class="$store.theme.isDark ? 'text-white' : 'text-slate-800'"
                        class="text-xl font-black uppercase tracking-tight"
                        x-text="isEdit ? 'Edit Menu Item' : 'Register Menu Item'"></h2>
                    <p :class="$store.theme.isDark ? 'text-gray-400' : 'text-gray-500'"
                       class="text-[10px] font-black uppercase tracking-widest opacity-50">Cataloging food, beverages, and pricing</p>
                </div>
            </div>
            <a href="/menu" :class="$store.theme.isDark ? 'hover:bg-white/10 text-gray-400' : 'hover:bg-gray-200 text-gray-600'"
               class="p-2 rounded-full transition-all">
                <i data-lucide="x" class="w-6 h-6"></i>
            </a>
        </div>

        <div class="p-10">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-12">

                {{-- LEFT --}}
                <div class="md:col-span-7 space-y-8">

                    {{-- Name --}}
                    <div class="relative group">
                        <label :class="$store.theme.isDark ? 'text-gray-500 group-focus-within:text-amber-500' : 'text-gray-400 group-focus-within:text-orange-600'"
                               class="block text-[10px] font-black uppercase tracking-[0.2em] mb-3 transition-colors">Item Product Name</label>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30">
                                <i data-lucide="utensils" class="w-[18px] h-[18px]"></i>
                            </div>
                            <input type="text" x-model="form.name" placeholder="e.g. Signature Wagyu Burger" required
                                :class="$store.theme.isDark ? 'bg-slate-950 border-white/5 text-white focus:border-amber-500/50' : 'bg-gray-50 border-gray-200 text-slate-800 focus:bg-white'"
                                class="w-full pl-12 pr-4 py-4 rounded-2xl border text-sm font-bold transition-all outline-none" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        {{-- Category --}}
                        <div class="relative group">
                            <label :class="$store.theme.isDark ? 'text-gray-400' : 'text-gray-500'"
                                   class="block text-[10px] font-black uppercase tracking-[0.2em] mb-3 opacity-50">Category</label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30">
                                    <i data-lucide="layers" class="w-[18px] h-[18px]"></i>
                                </div>
                                <select x-model="form.categoryId"
                                    :class="$store.theme.isDark ? 'bg-slate-950 border-white/5 text-white' : 'bg-gray-50 border-gray-200 text-slate-800 focus:bg-white'"
                                    class="w-full pl-12 pr-10 py-4 rounded-2xl border text-sm font-bold transition-all outline-none appearance-none">
                                    @foreach($categoryOptions ?? [] as $cat)
                                    <option value="{{ $cat['id'] }}">{{ strtoupper($cat['name']) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- SKU --}}
                        <div class="relative group">
                            <label :class="$store.theme.isDark ? 'text-gray-400' : 'text-gray-500'"
                                   class="block text-[10px] font-black uppercase tracking-[0.2em] mb-3 opacity-50">SKU Code</label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30">
                                    <i data-lucide="tag" class="w-[18px] h-[18px]"></i>
                                </div>
                                <input type="text" x-model="form.sku" placeholder="FD-001"
                                    :class="$store.theme.isDark ? 'bg-slate-950 border-white/5 text-white' : 'bg-gray-50 border-gray-200'"
                                    class="w-full pl-12 pr-4 py-4 rounded-2xl border text-sm font-bold transition-all outline-none" />
                            </div>
                        </div>
                    </div>

                    {{-- Description (pro only) --}}
                    @if($atLeastPro)
                    <div class="relative group">
                        <label :class="$store.theme.isDark ? 'text-gray-400' : 'text-gray-500'"
                               class="block text-[10px] font-black uppercase tracking-[0.2em] mb-3 opacity-50">Item Description</label>
                        <div class="relative">
                            <div class="absolute left-4 top-4 opacity-30">
                                <i data-lucide="file-text" class="w-[18px] h-[18px]"></i>
                            </div>
                            <textarea x-model="form.description" placeholder="Ingredients, allergen info, etc..."
                                :class="$store.theme.isDark ? 'bg-slate-950 border-white/5 text-white' : 'bg-gray-50 border-gray-200'"
                                class="w-full pl-12 pr-4 py-4 rounded-2xl border text-sm font-bold transition-all outline-none min-h-[7.5rem]"></textarea>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- RIGHT --}}
                <div class="md:col-span-5 space-y-8">

                    {{-- Finance Card --}}
                    <div :class="$store.theme.isDark ? 'bg-white/5 border-white/10' : 'bg-gray-50 border-gray-200'"
                         class="p-8 rounded-3xl border">
                        <div class="flex items-center gap-2 mb-6">
                            <i data-lucide="wallet" class="w-4 h-4 text-emerald-500"></i>
                            <span :class="$store.theme.isDark ? 'text-white' : 'text-slate-800'"
                                  class="text-[10px] font-black uppercase tracking-widest">Finance & Pricing</span>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label :class="$store.theme.isDark ? 'text-gray-400' : 'text-gray-500'"
                                       class="block text-[9px] font-black uppercase tracking-widest mb-2 opacity-50">Selling Price</label>
                                <input type="number" x-model="form.price"
                                    :class="$store.theme.isDark ? 'bg-slate-950 border-white/5 text-white' : 'bg-white border-gray-200 text-slate-900'"
                                    class="w-full px-5 py-4 rounded-xl border text-lg font-black tracking-tighter outline-none" />
                            </div>

                            @if($atLeastPro)
                            <div>
                                <label :class="$store.theme.isDark ? 'text-gray-400' : 'text-gray-500'"
                                       class="block text-[9px] font-black uppercase tracking-widest mb-2 opacity-50">Production Cost (COGS)</label>
                                <input type="number" x-model="form.costOfGoods"
                                    :class="$store.theme.isDark ? 'bg-slate-950 border-white/5 text-white' : 'bg-white border-gray-200 text-slate-900'"
                                    class="w-full px-5 py-4 rounded-xl border text-lg font-black tracking-tighter outline-none" />
                            </div>
                            @endif

                            <div :class="$store.theme.isDark ? 'bg-emerald-500/10' : 'bg-emerald-50'"
                                 class="p-4 rounded-2xl flex items-center justify-between">
                                <div class="flex items-center gap-2 text-emerald-500">
                                    <i data-lucide="trending-up" class="w-4 h-4"></i>
                                    <span class="text-[9px] font-black uppercase tracking-widest">Est. Margin</span>
                                </div>
                                <span :class="$store.theme.isDark ? 'text-white' : 'text-emerald-700'"
                                      class="text-sm font-black tracking-tight">
                                    Rp <span x-text="(Number(form.price) - Number(form.costOfGoods)).toLocaleString('id-ID')"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Media (pro only) --}}
                    @if($atLeastPro)
                    <div class="space-y-4">
                        <label :class="$store.theme.isDark ? 'text-gray-400' : 'text-gray-500'"
                               class="block text-[10px] font-black uppercase tracking-[0.2em] mb-3 opacity-50">Item Media</label>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30">
                                <i data-lucide="image" class="w-[18px] h-[18px]"></i>
                            </div>
                            <input type="url" x-model="form.imageUrl" placeholder="Image URL..."
                                :class="$store.theme.isDark ? 'bg-slate-950 border-white/5 text-white' : 'bg-gray-50 border-gray-200'"
                                class="w-full pl-12 pr-4 py-4 rounded-2xl border text-xs font-bold outline-none" />
                        </div>

                        <div :class="$store.theme.isDark ? 'border-white/10 bg-white/5' : 'border-gray-200 bg-gray-50'"
                             class="h-40 rounded-3xl overflow-hidden border-2 border-dashed flex items-center justify-center relative">
                            <template x-if="form.imageUrl">
                                <img :src="form.imageUrl" class="w-full h-full object-cover" alt="Preview" />
                            </template>
                            <template x-if="!form.imageUrl">
                                <div class="text-center opacity-30">
                                    <i data-lucide="image" class="w-8 h-8 mx-auto mb-2"></i>
                                    <span class="text-[9px] font-black uppercase tracking-widest">No Preview</span>
                                </div>
                            </template>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Footer --}}
            <div :class="$store.theme.isDark ? 'border-white/5' : 'border-gray-100'"
                 class="mt-12 pt-8 border-t flex justify-between items-center">
                <button type="button" @click="form.isAvailable = !form.isAvailable"
                    :class="form.isAvailable
                        ? ($store.theme.isDark ? 'bg-emerald-500/10 border-emerald-500/50 text-emerald-400' : 'bg-emerald-50 border-emerald-200 text-emerald-700')
                        : ($store.theme.isDark ? 'bg-red-500/10 border-red-500/50 text-red-400' : 'bg-red-50 border-red-200 text-red-700')"
                    class="flex items-center gap-2 px-5 py-3 rounded-xl transition-all border">
                    <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest"
                          x-text="form.isAvailable ? 'In Stock' : 'Out of Stock'"></span>
                </button>

                <div class="flex items-center gap-4">
                    <a href="/menu" :class="$store.theme.isDark ? 'text-gray-400 hover:bg-white/5' : 'text-gray-500 hover:bg-gray-100'"
                       class="px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all">Discard</a>

                    <button type="button" @click="submit" :disabled="processing || !form.name || form.price <= 0"
                        :class="$store.theme.isDark ? 'bg-white text-slate-950 hover:bg-gray-200' : 'bg-slate-900 text-white hover:bg-slate-800 shadow-slate-200'"
                        class="flex items-center gap-2 px-12 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl transition-all active:scale-95 disabled:opacity-50">
                        <i data-lucide="save" class="w-3.5 h-3.5"></i>
                        <span x-text="processing ? 'Processing...' : (isEdit ? 'Update Item' : 'Register Item')"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function menuItemForm(initialData, categoryOptions, itemId) {
    return {
        isEdit: !!initialData,
        processing: false,
        form: initialData || {
            name: '',
            categoryId: categoryOptions[0]?.id || 1,
            description: '',
            price: 0,
            costOfGoods: 0,
            imageUrl: '',
            isAvailable: true,
            sku: '',
        },

        async submit() {
            this.processing = true
            const url    = this.isEdit ? `/menu/${itemId}` : '/menu'
            const method = this.isEdit ? 'PUT' : 'POST'
            try {
                const res = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(this.form)
                })
                const data = await res.json()
                if (!res.ok) {
                    this.$store.notif.set('error', 'Error', data.message ?? 'Terjadi kesalahan')
                    return
                }
                this.$store.notif.set('success', 'Berhasil', this.isEdit ? 'Menu item diperbarui' : 'Menu item ditambahkan')
                setTimeout(() => window.location.href = '/menu', 1000)
            } catch (e) {
                this.$store.notif.set('error', 'Error', 'Ada kesalahan, coba lagi nanti')
            } finally {
                this.processing = false
            }
        }
    }
}
</script>

@endsection