<x-admin-layout>
    <x-slot:title>Menu Items</x-slot:title>
    <x-slot:page_title>Management > Menu Items & Categories </x-slot:page_title>
    <div x-data="menuIndex({{ json_encode(
        $items->map(
                fn($i) => [
                    'id' => $i->id,
                    'name' => $i->name,
                    'categoryId' => $i->category_id,
                    'price' => (float) $i->price,
                    'sku' => $i->sku,
                    'imageUrl' => $i->image_url,
                    'isAvailable' => (bool) $i->is_available,
                ],
            )->values(),
    ) }}, {{ json_encode(
        $categories->map(
                fn($c) => [
                    'id' => $c->id,
                    'name' => $c->name,
                    'sortOrder' => $c->sort_order,
                ],
            )->values(),
    ) }})" x-init="$nextTick(() => lucide.createIcons());
    $watch('isDark', () => $nextTick(() => lucide.createIcons()))" class="flex h-full">

        {{-- Sidebar Categories --}}
        <aside :class="isDark ? 'bg-slate-900 border-white/5' : 'bg-white border-gray-200'"
            class="flex h-full w-72 shrink-0 flex-col border-r">
            <div :class="isDark ? 'border-white/5' : 'border-gray-100'"
                class="flex h-16 items-center justify-between border-b px-6">
                <div class="flex items-center gap-2">
                    <i data-lucide="layers" :class="isDark ? 'text-amber-500' : 'text-orange-600'" class="h-4 w-4"></i>
                    <span :class="isDark ? 'text-white' : 'text-slate-800'"
                        class="text-[11px] font-black uppercase tracking-[0.2em]">Categories</span>
                </div>
                <a href="{{ route('menu.categories.create') }}"
                    :class="isDark ? 'bg-white text-slate-950 hover:bg-gray-200' :
                        'bg-slate-900 text-white hover:bg-slate-800'"
                    class="rounded-lg p-1.5 transition-all active:scale-95">
                    <i data-lucide="plus" class="h-3.5 w-3.5"></i>
                </a>
            </div>

            <nav class="overflow-y-auto] flex-1 space-y-1 p-4">
                <div @click="categorySelect = -1"
                    :class="categorySelect === -1 ?
                        (isDark ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-500/20' :
                            'bg-orange-600 text-white shadow-lg shadow-orange-600/20') :
                        (isDark ? 'text-gray-400 hover:bg-white/5 hover:text-white' :
                            'text-gray-500 hover:bg-gray-100 hover:text-slate-900')"
                    class="flex cursor-pointer items-center rounded-2xl px-4 py-3 text-[10px] font-black uppercase tracking-widest transition-all duration-300">
                    All Categories
                </div>
                <template x-for="(cat, idx) in sortedCategories" :key="cat.id">
                    <div :class="categorySelect === cat.id ?
                        (isDark ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-500/20' :
                            'bg-orange-600 text-white shadow-lg shadow-orange-600/20') :
                        (isDark ? 'text-gray-400 hover:bg-white/5 hover:text-white' :
                            'text-gray-500 hover:bg-gray-100 hover:text-slate-900')"
                        class="group flex items-center justify-between rounded-2xl px-4 py-3 text-[10px] font-black uppercase tracking-widest transition-all duration-300">
                        <p @click="categorySelect = cat.id" class="flex-1 cursor-pointer truncate py-1"
                            x-text="cat.name">
                        </p>
                        <div :class="categorySelect === cat.id ? 'opacity-100' : 'opacity-0 group-hover:opacity-100'"
                            class="flex gap-1 transition-all">
                            <div class="flex rounded-lg border border-white/10 bg-black/10 p-0.5">
                                <a :href="'/menu/categories/' + cat.id + '/edit'"
                                    class="rounded-md p-1.5 text-blue-400 hover:bg-white/20">
                                    <i data-lucide="edit-3" class="h-3 w-3"></i>
                                </a>
                                <button @click.stop="deleteCategory(cat.id)"
                                    class="rounded-md p-1.5 text-red-400 hover:bg-white/20">
                                    <i data-lucide="trash-2" class="h-3 w-3"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </nav>
        </aside>

        {{-- Main --}}
        <div class="flex h-full flex-1 flex-col overflow-hidden">
            {{-- Header --}}
            <div :class="isDark ? 'bg-slate-900/70 border-white/5' : 'bg-white/70 border-gray-200/50'"
                class="sticky top-0 z-40 w-full border-b px-8 py-4 backdrop-blur-xl">
                <div class="flex w-full flex-col items-center justify-between gap-6 md:flex-row">
                    <div class="flex items-center gap-4">
                        <div :class="isDark ? 'bg-slate-800 text-amber-500 shadow-lg shadow-black/20' :
                            'bg-orange-100 text-orange-600 shadow-sm'"
                            class="rounded-xl p-2.5">
                            <i data-lucide="utensils" class="h-6 w-6"></i>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold leading-none tracking-tight">Menu Items</h1>
                            <p :class="isDark ? 'text-gray-400' : 'text-gray-500'"
                                class="mt-1 text-[10px] font-bold uppercase tracking-widest">
                                Manage catalog, pricing, and availability
                            </p>
                        </div>
                    </div>
                    <div class="flex w-full items-center gap-4 md:w-auto">
                        <div :class="isDark ? 'bg-slate-900 border-white/10 focus-within:border-amber-500/50' :
                            'bg-white border-gray-200 focus-within:border-orange-500'"
                            class="flex flex-1 items-center gap-3 rounded-full border p-3 px-5 shadow-sm transition-all">
                            <i data-lucide="search" :class="isDark ? 'text-slate-500' : 'text-gray-400'"
                                class="h-[18px] w-[18px]"></i>
                            <input type="text" x-model="searchQuery" placeholder="Search menu or SKU..."
                                class="w-full border-none bg-transparent text-sm font-medium outline-none" />
                            <button @click="window.dispatchEvent(new CustomEvent('toggle-theme')); $nextTick(() => lucide.createIcons())"
                                :class="isDark ? 'text-amber-400 hover:bg-slate-800' :
                                    'text-slate-600 hover:bg-white shadow-sm'"
                                class="rounded-full transition-transform hover:scale-110 active:rotate-90">
                                <i :data-lucide="isDark ? 'sun' : 'moon'" class="h-[18px] w-[18px]"></i>
                            </button>
                        </div>
                        <a href="{{ route('menu.items.create') }}"
                            :class="isDark ? 'bg-white text-slate-950 hover:bg-gray-200' :
                                'bg-slate-900 text-white hover:bg-slate-800 shadow-slate-200'"
                            class="flex items-center gap-2 rounded-xl px-5 py-2.5 text-xs font-bold shadow-lg transition-all active:scale-95">
                            <i data-lucide="plus" class="h-4 w-4"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Grid --}}
            <div class="flex-1 overflow-y-auto p-8">
                <template x-if="groupedMenu.length === 0">
                    <div class="flex flex-col items-center justify-center py-32 opacity-20">
                        <i data-lucide="box" class="mb-4 h-16 w-16"></i>
                        <p class="text-xs font-black uppercase tracking-widest">Menu item not found</p>
                    </div>
                </template>

                <template x-for="cat in groupedMenu" :key="cat.id">
                    <div class="mb-6">
                        <h2 :class="isDark ? 'text-amber-500' : 'text-orange-600'"
                            class="mb-6 flex items-center gap-3 text-sm font-black uppercase tracking-[0.2em]">
                            <span :class="isDark ? 'bg-amber-500' : 'bg-orange-600'"
                                class="h-1.5 w-1.5 rounded-full"></span>
                            <span x-text="cat.name"></span>
                            <span :class="isDark ? 'bg-white/10' : 'bg-gray-100'" class="h-px flex-1"></span>
                        </h2>

                        <div
                            class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
                            <template x-for="item in cat.items" :key="item.id">
                                <div :class="isDark ?
                                    'bg-slate-900/50 border-white/5 hover:bg-slate-800 shadow-2xl shadow-black/20' :
                                    'bg-white border-gray-100 hover:shadow-xl shadow-gray-200/50'"
                                    class="group relative flex flex-col overflow-hidden rounded-3xl border transition-all duration-300 hover:-translate-y-1">

                                    {{-- Badge --}}
                                    <div :class="item.isAvailable ?
                                        (isDark ?
                                            'bg-emerald-500/20 text-emerald-400 border border-emerald-500/20' :
                                            'bg-emerald-100 text-emerald-700') :
                                        (isDark ?
                                            'bg-red-500/20 text-red-400 border border-red-500/20' :
                                            'bg-red-100 text-red-700')"
                                        class="absolute right-4 top-4 z-10 rounded-full px-3 py-1 text-[8px] font-black uppercase tracking-widest shadow-sm backdrop-blur-md"
                                        x-text="item.isAvailable ? 'Available' : 'Sold Out'">
                                    </div>

                                    {{-- Image --}}
                                    <div class="relative h-44 w-full overflow-hidden">
                                        <img :src="item.imageUrl ??
                                            'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=400&h=300&auto=format&fit=crop'"
                                            :alt="item.name"
                                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" />
                                        <div
                                            class="absolute inset-0 bg-gradient-to-t from-slate-950/60 to-transparent opacity-60">
                                        </div>
                                    </div>

                                    <div class="flex flex-1 flex-col p-6">
                                        <div class="mb-4">
                                            <div class="mb-1 flex items-center gap-2">
                                                <i data-lucide="tag"
                                                    :class="isDark ? 'text-amber-500' : 'text-orange-600'"
                                                    class="h-2.5 w-2.5"></i>
                                                <span :class="isDark ? 'text-gray-400' : 'text-gray-500'"
                                                    class="text-[9px] font-black uppercase tracking-widest opacity-50"
                                                    x-text="item.sku || 'NO-SKU'"></span>
                                            </div>
                                            <h3 :class="isDark ? 'text-white' : 'text-slate-800'"
                                                class="text-sm font-black uppercase leading-snug tracking-tight"
                                                x-text="item.name"></h3>
                                        </div>

                                        <div class="mt-auto">
                                            <div class="mb-5 flex items-baseline gap-1">
                                                <span :class="isDark ? 'text-gray-400' : 'text-gray-500'"
                                                    class="text-[10px] font-bold opacity-50">Rp</span>
                                                <span :class="isDark ? 'text-white' : 'text-slate-900'"
                                                    class="text-lg font-black tracking-tighter"
                                                    x-text="item.price.toLocaleString('id-ID')"></span>
                                            </div>

                                            <div class="grid grid-cols-2 gap-2">
                                                <button @click="toggleAvailability(item)"
                                                    :class="item.isAvailable ?
                                                        'bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white' :
                                                        'bg-emerald-500/10 text-emerald-500 hover:bg-emerald-500 hover:text-white'"
                                                    class="flex items-center justify-center gap-2 rounded-xl py-2.5 text-[9px] font-black uppercase tracking-widest transition-all">
                                                    <template x-if="item.isAvailable"><i data-lucide="eye-off"
                                                            class="h-3 w-3"></i></template>
                                                    <template x-if="!item.isAvailable"><i data-lucide="eye"
                                                            class="h-3 w-3"></i></template>
                                                    <span x-text="item.isAvailable ? 'Hide' : 'Show'"></span>
                                                </button>
                                                <a :href="'/menu/items/' + item.id + '/edit'"
                                                    :class="isDark ?
                                                        'bg-white/5 text-gray-300 hover:bg-white/10 hover:text-white' :
                                                        'bg-gray-100 text-slate-600 hover:bg-slate-900 hover:text-white'"
                                                    class="flex items-center justify-center gap-2 rounded-xl py-2.5 text-[9px] font-black uppercase tracking-widest transition-all">
                                                    <i data-lucide="edit-3" class="h-3 w-3"></i> Edit
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
                    return [...this.categories].sort((a, b) => a.sortOrder - b.sortOrder)
                },

                get groupedMenu() {
                    return this.sortedCategories
                        .map(cat => ({
                            ...cat,
                            items: this.menuItems.filter(item =>
                                item.categoryId === cat.id &&
                                (this.categorySelect === -1 || item.categoryId === this.categorySelect) &&
                                (!this.searchQuery ||
                                    item.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                                    (item.sku && item.sku.toLowerCase().includes(this.searchQuery
                                        .toLowerCase())))
                            )
                        }))
                        .filter(cat => cat.items.length > 0)
                },

                async toggleAvailability(item) {
                    const form = document.createElement('form')
                    form.method = 'POST'
                    form.action = `/menu/items/${item.id}/toggle-available`
                    form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}">`
                    document.body.appendChild(form)
                    form.submit()
                },

                async deleteCategory(id) {
                    if (!confirm('Hapus kategori ini?')) return
                    const form = document.createElement('form')
                    form.method = 'POST'
                    form.action = `/menu/categories/${id}`
                    form.innerHTML =
                        `<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">`
                    document.body.appendChild(form)
                    form.submit()
                }
            }
        }
    </script>
</x-admin-layout>
