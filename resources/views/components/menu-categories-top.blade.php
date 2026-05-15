{{--
    Cara pakai:
    @include('components.menu-categories-top', ['sidebarItems' => $categories, 'categorySelect' => -1])
--}}
<div
    x-data="menuCategoriesTop({{ json_encode($sidebarItems ?? []) }}, {{ $categorySelect ?? -1 }})"
    x-init="$nextTick(() => lucide.createIcons())"
    class="flex flex-1 flex-col w-full h-full"
>
    <div class="w-full sticky top-0 z-30 px-4 py-2">
        <div
            :class="$store.theme.isDark ? 'bg-slate-900/80 border-white/10' : 'bg-white/90 border-gray-200'"
            class="w-full border rounded-full shadow-sm backdrop-blur-md transition-all duration-300"
        >
            <div
                :class="$store.theme.isDark ? 'bg-slate-900 border-white/5' : 'bg-gray-100 border-gray-200'"
                class="flex items-center gap-2 p-1.5 rounded-full border shadow-inner overflow-x-auto max-w-full"
            >
                <template x-for="item in sortedItems" :key="item.id">
                    <div class="flex items-center relative group shrink-0">
                        <button
                            @click="categorySelect = item.id"
                            :class="categorySelect === item.id
                                ? ($store.theme.isDark ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-500/20' : 'bg-orange-600 text-white shadow-lg shadow-orange-600/20')
                                : ($store.theme.isDark ? 'text-gray-400 hover:bg-white/5 hover:text-white' : 'text-gray-500 hover:bg-gray-100 hover:text-slate-900')"
                            class="flex items-center px-6 p-2.5 rounded-full text-[11px] font-black uppercase tracking-widest transition-all duration-300"
                            x-text="item.name"
                        ></button>

                        <template x-if="item.id !== -1">
                            <div class="ml-1">
                                <button
                                    :class="categorySelect === item.id
                                        ? ($store.theme.isDark ? 'text-amber-200 hover:bg-amber-700' : 'text-red-100 hover:bg-red-700')
                                        : ($store.theme.isDark ? 'text-slate-600 hover:bg-slate-700 hover:text-slate-300' : 'text-gray-400 hover:bg-gray-200 hover:text-gray-600')"
                                    class="p-1.5 rounded-full transition-colors"
                                >
                                    <i data-lucide="more-horizontal" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <main class="flex-1 w-full h-full relative">
        @yield('top_content')
    </main>
</div>

<script>
function menuCategoriesTop(sidebarItems, initialSelect) {
    return {
        categorySelect: initialSelect,
        items: [{ id: -1, name: 'All', sortOrder: -1 }, ...sidebarItems],
        get sortedItems() {
            return [...this.items].sort((a, b) => a.sortOrder - b.sortOrder)
        }
    }
}
</script>