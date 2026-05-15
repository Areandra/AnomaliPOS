{{--
    Cara pakai di blade manapun:
    @include('components.menu-categories-sidebar', ['sidebarItems' => $categories, 'categorySelect' => -1])
--}}
<div
    x-data="menuCategoriesSidebar({{ json_encode($sidebarItems ?? []) }}, {{ $categorySelect ?? -1 }})"
    x-init="$nextTick(() => lucide.createIcons())"
    :class="$store.theme.isDark ? 'bg-slate-950' : 'bg-gray-50'"
    class="flex h-full transition-colors duration-500"
>
    <aside
        :class="$store.theme.isDark ? 'bg-slate-900 border-white/5' : 'bg-white border-gray-200'"
        class="w-72 shrink-0 h-full flex flex-col border-r transition-all duration-300"
    >
        {{-- Header --}}
        <div :class="$store.theme.isDark ? 'border-white/5' : 'border-gray-100'"
             class="px-6 flex items-center h-16 border-b justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="layers" :class="$store.theme.isDark ? 'text-amber-500' : 'text-orange-600'" class="w-4 h-4"></i>
                <span :class="$store.theme.isDark ? 'text-white' : 'text-slate-800'"
                      class="font-black text-[11px] uppercase tracking-[0.2em]">Categories</span>
            </div>
            <a href="/menu/categories/create"
               :class="$store.theme.isDark ? 'bg-white text-slate-950 hover:bg-gray-200' : 'bg-slate-900 text-white hover:bg-slate-800'"
               class="p-1.5 rounded-lg transition-all active:scale-95">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
            </a>
        </div>

        {{-- List --}}
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <template x-for="(item, idx) in sortedItems" :key="item.id">
                <div
                    :class="categorySelect === item.id
                        ? ($store.theme.isDark ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-500/20' : 'bg-orange-600 text-white shadow-lg shadow-orange-600/20')
                        : ($store.theme.isDark ? 'text-gray-400 hover:bg-white/5 hover:text-white' : 'text-gray-500 hover:bg-gray-100 hover:text-slate-900')"
                    class="group flex items-center justify-between px-4 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 cursor-pointer"
                >
                    <p @click="categorySelect = item.id" class="flex-1 truncate py-1" x-text="item.name"></p>

                    <template x-if="item.id !== -1">
                        <div :class="categorySelect === item.id ? 'opacity-100' : 'opacity-0 group-hover:opacity-100'"
                             class="flex gap-1 transition-all duration-300">
                            <div class="flex bg-black/10 backdrop-blur-md rounded-lg p-0.5 border border-white/10">
                                <button @click.stop="moveUp(idx)" :disabled="idx === 0"
                                        class="p-1.5 rounded-md hover:bg-white/20 transition-colors">
                                    <i data-lucide="arrow-up" class="w-3 h-3"></i>
                                </button>
                                <button @click.stop="moveDown(idx)" :disabled="idx >= sortedItems.length - 1"
                                        class="p-1.5 rounded-md hover:bg-white/20 transition-colors">
                                    <i data-lucide="arrow-down" class="w-3 h-3"></i>
                                </button>
                                <a :href="'/menu/categories/' + item.id"
                                   class="p-1.5 rounded-md hover:bg-white/20 transition-colors text-blue-400">
                                    <i data-lucide="edit-3" class="w-3 h-3"></i>
                                </a>
                                <button @click.stop="deleteCategory(item.id)"
                                        class="p-1.5 rounded-md hover:bg-white/20 transition-colors text-red-400">
                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
        </nav>
    </aside>

    <main class="flex-1 h-full overflow-hidden">
        @yield('sidebar_content')
    </main>
</div>

<script>
function menuCategoriesSidebar(sidebarItems, initialSelect) {
    return {
        categorySelect: initialSelect,
        items: [{ id: -1, name: 'All Categories', sortOrder: -1 }, ...sidebarItems],

        get sortedItems() {
            return [...this.items].sort((a, b) => a.sortOrder - b.sortOrder)
        },

        async moveUp(idx) {
            const sorted = this.sortedItems
            if (idx === 0) return
            const current = sorted[idx]
            const above = sorted[idx - 1]
            await fetch(`/menu/categories/${current.id}/move-up`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            })
            const ci = this.items.findIndex(i => i.id === current.id)
            const ai = this.items.findIndex(i => i.id === above.id)
            ;[this.items[ci].sortOrder, this.items[ai].sortOrder] = [this.items[ai].sortOrder, this.items[ci].sortOrder]
            this.$nextTick(() => lucide.createIcons())
        },

        async moveDown(idx) {
            const sorted = this.sortedItems
            if (idx >= sorted.length - 1) return
            const current = sorted[idx]
            const below = sorted[idx + 1]
            await fetch(`/menu/categories/${current.id}/move-down`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            })
            const ci = this.items.findIndex(i => i.id === current.id)
            const bi = this.items.findIndex(i => i.id === below.id)
            ;[this.items[ci].sortOrder, this.items[bi].sortOrder] = [this.items[bi].sortOrder, this.items[ci].sortOrder]
            this.$nextTick(() => lucide.createIcons())
        },

        async deleteCategory(id) {
            if (!confirm('Hapus kategori ini?')) return
            await fetch(`/menu/categories/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            })
            this.items = this.items.filter(i => i.id !== id)
            this.$nextTick(() => lucide.createIcons())
        }
    }
}
</script>