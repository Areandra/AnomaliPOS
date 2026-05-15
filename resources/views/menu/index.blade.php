@extends('layouts.admin')

@section('title', 'Menu Management')
@section('page_title', 'Management > Menu Items & Categories')

@section('content')
    @php $atLeastPro = in_array(session('plan', 'pro'), ['pro', 'enterprise']); @endphp
    <div x-data="menuIndex({{ json_encode($menuItems ?? []) }}, {{ json_encode($categories ?? []) }})"
        x-init="$store.theme.init(); $nextTick(() => lucide.createIcons()) $watch('$store.theme.isDark', () => $nextTick(() => lucide.createIcons()))" class="h-full flex">
        {{-- SIDEBAR CATEGORIES --}}
        <aside :class="$store.theme.isDark ? 'bg-slate-900 border-white/5' : 'bg-white border-gray-200'"
            class="w-72 shrink-0 h-full flex flex-col border-r">
            <div :class="$store.theme.isDark ? 'border-white/5' : 'border-gray-100'"
                class="px-6 flex items-center h-16 border-b justify-between">
                <div class="flex items-center gap-2">
                    <i data-lucide="layers" :class="$store.theme.isDark ? 'text-amber-500' : 'text-orange-600'"
                        class="w-4 h-4"></i>
                    <span :class="$store.theme.isDark ? 'text-white' : 'text-slate-800'"
                        class="font-black text-[11px] uppercase tracking-[0.2em]">Categories</span>
                </div>
                <a href="/menu/categories/create"
                    :class="$store.theme.isDark ? 'bg-white text-slate-950 hover:bg-gray-200' : 'bg-slate-900 text-white hover:bg-slate-800'"
                    class="p-1.5 rounded-lg transition-all active:scale-95">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                </a>
            </div>

            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                <template x-for="(cat, idx) in sortedCategories" :key="cat.id">
                    <div :class="categorySelect === cat.id
                                ? ($store.theme.isDark ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-500/20' : 'bg-orange-600 text-white shadow-lg shadow-orange-600/20')
                                : ($store.theme.isDark ? 'text-gray-400 hover:bg-white/5 hover:text-white' : 'text-gray-500 hover:bg-gray-100 hover:text-slate-900')"
                        class="group flex items-center justify-between px-4 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all duration-300">
                        <p @click="categorySelect = cat.id" class="flex-1 truncate py-1 cursor-pointer" x-text="cat.name">
                        </p>
                        <template x-if="cat.id !== -1">
                            <div :class="categorySelect === cat.id ? 'opacity-100' : 'opacity-0 group-hover:opacity-100'"
                                class="flex gap-1 transition-all">
                                <div class="flex bg-black/10 rounded-lg p-0.5 border border-white/10">
                                    <button @click.stop="moveCatUp(idx)" class="p-1.5 rounded-md hover:bg-white/20">
                                        <i data-lucide="arrow-up" class="w-3 h-3"></i>
                                    </button>
                                    <button @click.stop="moveCatDown(idx)" class="p-1.5 rounded-md hover:bg-white/20">
                                        <i data-lucide="arrow-down" class="w-3 h-3"></i>
                                    </button>
                                    <a :href="'/menu/categories/' + cat.id"
                                        class="p-1.5 rounded-md hover:bg-white/20 text-blue-400">
                                        <i data-lucide="edit-3" class="w-3 h-3"></i>
                                    </a>
                                    <button @click.stop="deleteCat(cat.id)"
                                        class="p-1.5 rounded-md hover:bg-white/20 text-red-400">
                                        <i data-lucide="trash-2" class="w-3 h-3"></i>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </nav>
        </aside>

        {{-- MAIN --}}
        <div class="flex-1 h-full flex flex-col overflow-hidden">

            {{-- Header --}}
            <div :class="$store.theme.isDark ? 'bg-slate-900/70 border-white/5' : 'bg-white/70 border-gray-200/50'"
                class="sticky top-0 z-40 w-full px-8 py-4 backdrop-blur-xl border-b">
                <div class="flex flex-col md:flex-row justify-between items-center gap-6 w-full">
                    <div class="flex items-center gap-4">
                        <div :class="$store.theme.isDark ? 'bg-slate-800 text-amber-500' : 'bg-orange-100 text-orange-600'"
                            class="p-2.5 rounded-xl">
                            <i data-lucide="utensils" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h1 class="font-bold text-lg leading-none tracking-tight">Menu Items</h1>
                            <p :class="$store.theme.isDark ? 'text-gray-400' : 'text-gray-500'"
                                class="text-[10px] mt-1 uppercase font-bold tracking-widest">
                                Manage catalog, pricing, and availability
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 w-full md:w-auto">
                        <div :class="$store.theme.isDark ? 'bg-slate-900 border-white/10 focus-within:border-amber-500/50' : 'bg-white border-gray-200'"
                            class="flex items-center gap-3 flex-1 p-3 px-5 border rounded-full transition-all shadow-sm">
                            <i data-lucide="search" :class="$store.theme.isDark ? 'text-slate-500' : 'text-gray-400'"
                                class="w-[18px] h-[18px]"></i>
                            <input type="text" x-model="searchQuery" placeholder="Search menu or SKU..."
                                class="bg-transparent border-none outline-none w-full text-sm font-medium" />
                            <button @click="$store.theme.toggle(); $nextTick(() => lucide.createIcons())"
                                :class="$store.theme.isDark ? 'text-amber-400 hover:bg-slate-800' : 'text-slate-600 hover:bg-white shadow-sm'"
                                class="rounded-full transition-transform active:rotate-90 hover:scale-110">
                                <i :data-lucide="$store.theme.isDark ? 'sun' : 'moon'" class="w-[18px] h-[18px]"></i>
                            </button>
                        </div>

                        <a href="/menu/create"
                            :class="$store.theme.isDark ? 'bg-white text-slate-950 hover:bg-gray-200' : 'bg-slate-900 text-white hover:bg-slate-800 shadow-slate-200'"
                            class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all active:scale-95 flex items-center gap-2 shadow-lg">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Grid --}}
            <div class="flex-1 overflow-y-auto p-8">
                <template x-if="groupedMenu.length === 0">
                    <div class="flex flex-col items-center justify-center py-32 opacity-20">
                        <i data-lucide="box" class="w-16 h-16 mb-4"></i>
                        <p class="font-black uppercase tracking-widest text-xs">Menu item not found</p>
                    </div>
                </template>

                <template x-for="cat in groupedMenu" :key="cat.id">
                    <div class="mb-6">
                        <h2 :class="$store.theme.isDark ? 'text-amber-500' : 'text-orange-600'"
                            class="text-sm uppercase tracking-[0.2em] font-black mb-6 flex items-center gap-3">
                            <span :class="$store.theme.isDark ? 'bg-amber-500' : 'bg-orange-600'"
                                class="h-1.5 w-1.5 rounded-full"></span>
                            <span x-text="cat.name"></span>
                            <span :class="$store.theme.isDark ? 'bg-white/10' : 'bg-gray-100'" class="flex-1 h-px"></span>
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6">
                            <template x-for="item in cat.items" :key="item.id">
                                <div :class="$store.theme.isDark ? 'bg-slate-900/50 border-white/5 hover:bg-slate-800 shadow-2xl shadow-black/20' : 'bg-white border-gray-100 hover:shadow-xl shadow-gray-200/50'"
                                    class="group relative flex flex-col rounded-3xl border overflow-hidden transition-all duration-300 hover:-translate-y-1">

                                    {{-- Badge --}}
                                    <div :class="item.isAvailable
                                                ? ($store.theme.isDark ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/20' : 'bg-emerald-100 text-emerald-700')
                                                : ($store.theme.isDark ? 'bg-red-500/20 text-red-400 border border-red-500/20' : 'bg-red-100 text-red-700')"
                                        class="absolute top-4 right-4 z-10 px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest shadow-sm backdrop-blur-md"
                                        x-text="item.isAvailable ? 'Available' : 'Sold Out'">
                                    </div>

                                    {{-- Image (pro only) --}}
                                    @if($atLeastPro)
                                        <div class="h-44 w-full overflow-hidden relative">
                                            <img :src="item.imageUrl ?? 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=400&h=300&auto=format&fit=crop'"
                                                :alt="item.name"
                                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
                                            <div
                                                class="absolute inset-0 bg-gradient-to-t from-slate-950/60 to-transparent opacity-60">
                                            </div>
                                        </div>
                                    @endif

                                    <div class="p-6 flex-1 flex flex-col">
                                        <div class="mb-4">
                                            <div class="flex items-center gap-2 mb-1">
                                                <i data-lucide="tag"
                                                    :class="$store.theme.isDark ? 'text-amber-500' : 'text-orange-600'"
                                                    class="w-2.5 h-2.5"></i>
                                                <span :class="$store.theme.isDark ? 'text-gray-400' : 'text-gray-500'"
                                                    class="text-[9px] font-black uppercase tracking-widest opacity-50"
                                                    x-text="item.sku || 'NO-SKU'"></span>
                                            </div>
                                            <h3 :class="$store.theme.isDark ? 'text-white' : 'text-slate-800'"
                                                class="text-sm font-black uppercase tracking-tight leading-snug"
                                                x-text="item.name"></h3>
                                        </div>

                                        <div class="mt-auto">
                                            <div class="flex items-baseline gap-1 mb-5">
                                                <span :class="$store.theme.isDark ? 'text-gray-400' : 'text-gray-500'"
                                                    class="text-[10px] font-bold opacity-50">Rp</span>
                                                <span :class="$store.theme.isDark ? 'text-white' : 'text-slate-900'"
                                                    class="text-lg font-black tracking-tighter"
                                                    x-text="item.price.toLocaleString('id-ID')"></span>
                                            </div>

                                            <div class="grid grid-cols-2 gap-2">
                                                <button @click="toggleAvailability(item)"
                                                    :class="item.isAvailable ? 'bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white' : 'bg-emerald-500/10 text-emerald-500 hover:bg-emerald-500 hover:text-white'"
                                                    class="flex items-center justify-center gap-2 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">
                                                    <template x-if="item.isAvailable">
                                                        <i data-lucide="eye-off" class="w-3 h-3"></i>
                                                    </template>
                                                    <template x-if="!item.isAvailable">
                                                        <i data-lucide="eye" class="w-3 h-3"></i>
                                                    </template>
                                                    <span x-text="item.isAvailable ? 'Hide' : 'Show'"></span>
                                                </button>

                                                <a :href="'/menu/' + item.id"
                                                    :class="$store.theme.isDark ? 'bg-white/5 text-gray-300 hover:bg-white/10 hover:text-white' : 'bg-gray-100 text-slate-600 hover:bg-slate-900 hover:text-white'"
                                                    class="flex items-center justify-center gap-2 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">
                                                    <i data-lucide="edit-3" class="w-3 h-3"></i> Edit
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <script>
        function menuIndex(menuItems, categories) {
            return {
                menuItems,
                categories,
                searchQuery: '',
                categorySelect: -1,

                get sortedCategories() {
                    return [{ id: -1, name: 'All Categories', sortOrder: -1 }, ...this.categories]
                        .sort((a, b) => a.sortOrder - b.sortOrder)
                },

                get groupedMenu() {
                    return this.categories
                        .sort((a, b) => a.sortOrder - b.sortOrder)
                        .map(cat => ({
                            ...cat,
                            items: this.menuItems.filter(item =>
                                item.categoryId === cat.id &&
                                (this.categorySelect === -1 || item.categoryId === this.categorySelect) &&
                                (!this.searchQuery ||
                                    item.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                                    (item.sku && item.sku.toLowerCase().includes(this.searchQuery.toLowerCase())))
                            )
                        }))
                        .filter(cat => cat.items.length > 0)
                },

                async toggleAvailability(item) {
                    await fetch(`/menu/${item.id}/toggle-availability`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ status: !item.isAvailable })
                    })
                    item.isAvailable = !item.isAvailable
                    this.$nextTick(() => lucide.createIcons())
                },

                async moveCatUp(idx) {
                    const sorted = this.sortedCategories
                    if (idx <= 1) return
                    const current = sorted[idx], above = sorted[idx - 1]
                    await fetch(`/menu/categories/${current.id}/move-up`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                    })
                    const ci = this.categories.findIndex(i => i.id === current.id)
                    const ai = this.categories.findIndex(i => i.id === above.id)
                        ;[this.categories[ci].sortOrder, this.categories[ai].sortOrder] = [this.categories[ai].sortOrder, this.categories[ci].sortOrder]
                },

                async moveCatDown(idx) {
                    const sorted = this.sortedCategories
                    if (idx >= sorted.length - 1) return
                    const current = sorted[idx], below = sorted[idx + 1]
                    await fetch(`/menu/categories/${current.id}/move-down`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                    })
                    const ci = this.categories.findIndex(i => i.id === current.id)
                    const bi = this.categories.findIndex(i => i.id === below.id)
                        ;[this.categories[ci].sortOrder, this.categories[bi].sortOrder] = [this.categories[bi].sortOrder, this.categories[ci].sortOrder]
                },

                async deleteCat(id) {
                    if (!confirm('Hapus kategori ini?')) return
                    await fetch(`/menu/categories/${id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                    })
                    this.categories = this.categories.filter(c => c.id !== id)
                    this.$nextTick(() => lucide.createIcons())
                }
            }
        }
    </script>

@endsection