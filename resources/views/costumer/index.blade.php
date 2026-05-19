<x-app-layout title="Order Menu - AnoPos">
    <x-customer-layout :sessionToken="$sessionToken">

        <div x-data="customerMenuApp({
            categories: @js($category),
            menuItems: @js($menuItems),
            order: @js($currentOrder),
            sessionToken: @js($sessionToken),
        })" class="relative z-10">
            {{-- STICKY TOP NAVIGATION (SEARCH & CATEGORY) --}}
            <div class="sticky top-0 z-40 bg-transparent">
                <div class="px-4 py-4 transition-all">
                    <div class="flex w-full items-center gap-3">
                        <div class="flex flex-1 items-center gap-3 rounded-full border p-3 px-5 shadow-sm transition-all"
                            :class="isDark ? 'bg-slate-900 border-white/10 focus-within:border-amber-500/50' :
                                'bg-white border-gray-200 focus-within:border-orange-500'">

                            <i data-lucide="search" :class="isDark ? 'text-slate-500' : 'text-gray-400'"
                                class="h-4 w-4"></i>

                            <input type="text" placeholder="Search menu items..." x-model="search"
                                class="w-full border-none bg-transparent p-0 text-sm font-medium outline-none focus:ring-0" />

                            <button @click="isDark = !isDark"
                                class="rounded-full p-1 transition-transform hover:scale-110 active:rotate-90"
                                :class="isDark ? 'text-amber-400 hover:bg-slate-800' :
                                    'text-slate-600 hover:bg-gray-100 shadow-sm'">
                                <i :data-lucide="isDark ? 'sun' : 'moon'" class="h-4 w-4"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- CATEGORY TABS LAYOUT --}}
                <div class="no-scrollbar flex gap-2 overflow-x-auto px-4 pb-3">
                    <button @click="categorySelect = -1"
                        class="shrink-0 rounded-full border px-5 py-2 text-xs font-black uppercase tracking-wider shadow-sm transition-all"
                        :class="categorySelect === -1 ?
                            (isDark ? 'bg-amber-500 text-slate-950 border-amber-500' :
                                'bg-orange-600 text-white border-orange-600') :
                            (isDark ? 'bg-slate-900 text-slate-400 border-white/5' :
                                'bg-white text-gray-500 border-gray-200')">
                        Semua
                    </button>

                    <template x-for="cat in categories" :key="cat.id">
                        <button @click="categorySelect = cat.id" x-show="hasVisibleItems(cat)"
                            class="shrink-0 rounded-full border px-5 py-2 text-xs font-black uppercase tracking-wider shadow-sm transition-all"
                            :class="categorySelect === cat.id ?
                                (isDark ? 'bg-amber-500 text-slate-950 border-amber-500' :
                                    'bg-orange-600 text-white border-orange-600') :
                                (isDark ? 'bg-slate-900 text-slate-400 border-white/5' :
                                    'bg-white text-gray-500 border-gray-200')">
                            <span x-text="cat.name"></span>
                        </button>
                    </template>
                </div>
            </div>

            {{-- MENU GRID LAYER --}}
            <div class="mx-auto max-w-lg px-4 pb-40 pt-2">
                <div class="space-y-12">

                    <template x-for="cat in categories" :key="cat.id">
                        <div x-show="shouldShowCategory(cat)" class="transition-all">

                            {{-- Category Title --}}
                            <h2 class="mb-6 flex items-center gap-2 text-sm font-black uppercase tracking-widest"
                                :class="isDark ? 'text-amber-500' : 'text-orange-600'">
                                <span class="h-1 w-4 rounded-full"
                                    :class="isDark ? 'bg-amber-500' : 'bg-orange-600'"></span>
                                <span x-text="cat.name"></span>
                            </h2>

                            {{-- Items Inside Category --}}
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <template x-for="item in filteredItems(cat)" :key="item.id">
                                    <div class="group relative rounded-3xl border p-4 transition-all duration-300"
                                        :class="isDark ? 'bg-slate-900 border-white/5 hover:border-amber-500/30 shadow-xl' :
                                            'bg-white border-gray-100 hover:border-orange-200 shadow-md'">

                                        <div class="flex gap-4">
                                            {{-- Image Container --}}
                                            <div class="relative h-24 w-24 shrink-0">
                                                <img :src="item.imageUrl ||
                                                    'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=400&h=300&auto=format&fit=crop'"
                                                    class="h-full w-full rounded-2xl object-cover shadow-inner transition-transform group-hover:scale-105"
                                                    :alt="item.name">

                                                {{-- Badge Quantity --}}
                                                <div x-show="getItemQty(item.id) > 0" x-text="getItemQty(item.id)"
                                                    class="absolute -right-2 -top-2 flex h-6 w-6 animate-bounce items-center justify-center rounded-full border-2 border-white bg-red-500 text-[10px] font-bold text-white shadow-lg">
                                                </div>
                                            </div>

                                            {{-- Info Container --}}
                                            <div class="flex flex-1 flex-col justify-between py-1">
                                                <div>
                                                    <h3 class="line-clamp-1 text-sm font-bold"
                                                        :class="isDark ? 'text-white' : 'text-slate-800'"
                                                        x-text="item.name"></h3>
                                                    <p class="mt-1 line-clamp-2 text-[10px] leading-relaxed"
                                                        :class="isDark ? 'text-slate-400' : 'text-gray-500'"
                                                        x-text="item.description"></p>
                                                </div>
                                                <p class="text-sm font-black italic">
                                                    <span class="mr-0.5 text-[10px]"
                                                        :class="isDark ? 'text-amber-500' : 'text-orange-600'">Rp</span>
                                                    <span x-text="formatRupiah(item.price)"></span>
                                                </p>
                                            </div>
                                        </div>

                                        {{-- ACTION BUTTONS --}}
                                        <div class="mt-4 flex items-center justify-between border-t pt-4"
                                            :class="isDark ? 'border-white/5' : 'border-gray-50'">
                                            <span
                                                class="text-[9px] font-black uppercase tracking-tighter opacity-30">Tambahkan</span>

                                            <div class="flex items-center gap-1 rounded-full p-1"
                                                :class="isDark ? 'bg-black/20' : 'bg-gray-100'">
                                                {{-- Minus & Qty Selector --}}
                                                <div x-show="getItemQty(item.id) > 0" class="flex items-center gap-1">
                                                    <button @click="updateQty(item.id, getItemQty(item.id) - 1)"
                                                        class="rounded-full p-1.5 transition-all"
                                                        :class="isDark ? 'bg-slate-700 text-white hover:bg-red-500' :
                                                            'bg-white text-gray-800 hover:bg-orange-100'">
                                                        <i data-lucide="minus" class="h-3.5 w-3.5"
                                                            style="stroke-width: 3px"></i>
                                                    </button>
                                                    <span class="w-8 text-center text-xs font-black"
                                                        x-text="getItemQty(item.id)"></span>
                                                </div>

                                                {{-- Plus / Add Button --}}
                                                <button
                                                    @click="getItemQty(item.id) > 0 ? updateQty(item.id, getItemQty(item.id) + 1) : addItem(item.id)"
                                                    class="rounded-full p-1.5 shadow-sm transition-all active:scale-90"
                                                    :class="isDark ? 'bg-amber-500 text-slate-900 hover:bg-amber-400' :
                                                        'bg-orange-600 text-white hover:bg-orange-700'">
                                                    <i data-lucide="plus" class="h-3.5 w-3.5"
                                                        style="stroke-width: 3px"></i>
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </template>
                            </div>

                        </div>
                    </template>

                </div>

                {{-- EMPTY STATE LAYOUT --}}
                <div x-show="isMenuEmpty()" class="flex flex-col items-center py-20 opacity-20">
                    <i data-lucide="utensils" class="h-12 w-12"></i>
                    <p class="mt-4 text-[10px] font-black uppercase tracking-widest">Menu tidak ditemukan</p>
                </div>

            </div>
        </div>

    </x-customer-layout>
</x-app-layout>

{{-- <script src="[https://cdn.socket.io/4.7.5/socket.io.min.js](https://cdn.socket.io/4.7.5/socket.io.min.js)"></script> --}}
<script>
    function customerMenuApp(props) {
        return {
            search: '',
            categorySelect: -1,

            categories: props.categories ?? [],
            menuItems: props.menuItems ?? [],
            order: props.order ?? null,
            sessionToken: props.sessionToken ?? null,

            init() {
                this.$watch('search', () => {
                    this.$nextTick(() => lucide.createIcons());
                });

                this.$watch('categorySelect', () => {
                    this.$nextTick(() => lucide.createIcons());
                });
            },

            filteredItems(cat) {
                return this.menuItems.filter(item => {
                    const matchCategory = item.category_id === cat.id;

                    const matchSearch = !this.search ||
                        item.name.toLowerCase().includes(
                            this.search.toLowerCase()
                        );

                    return matchCategory && matchSearch;
                });
            },

            hasVisibleItems(cat) {
                return this.filteredItems(cat).length > 0;
            },

            shouldShowCategory(cat) {
                if (
                    this.categorySelect > 0 &&
                    cat.id !== this.categorySelect
                ) {
                    return false;
                }

                return this.hasVisibleItems(cat);
            },

            isMenuEmpty() {
                return !this.categories.some(cat =>
                    this.shouldShowCategory(cat)
                );
            },

            getItemQty(menu_item_id) {
                if (!this.order || !this.order.items) return 0;

                const cartItem = this.order.items.find(i =>
                    i.menu_item_id === menu_item_id &&
                    i.status === 'cart'
                );

                console.log(cartItem)

                return cartItem ? cartItem.quantity : 0;
            },

            async sendPostRequest(url, payload) {
                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document
                                .querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(payload)
                    });

                    if (response.ok) {
                        const newOrder = await response.json()
                        this.order = newOrder.order;

                        this.$nextTick(() => {
                            lucide.createIcons();
                        });
                    }
                } catch (error) {
                    console.error(
                        'Gagal memproses aksi order:',
                        error
                    );
                }
            },

            addItem(menu_item_id) {
                this.sendPostRequest(
                    `/order/session/${this.sessionToken}/add-item`, {
                        menu_item_id,
                        qty: 1
                    }
                );
            },

            updateQty(menu_item_id, qty) {
                if (!this.order?.items) return;
                const cartItem = this.order.items.find(i =>
                    i.menu_item_id === menu_item_id &&
                    i.status === 'cart'
                );

                if (!cartItem) return;

                if (qty < 1) {
                    this.sendPostRequest(
                        `/order/session/${this.sessionToken}/delete-item`, {
                            itemId: cartItem.id
                        }
                    );
                } else {
                    this.sendPostRequest(
                        `/order/session/${this.sessionToken}/update-qty`, {
                            itemId: cartItem.id,
                            qty
                        }
                    );
                }
            },

            formatRupiah(amount) {
                return Number(amount).toLocaleString('id-ID');
            }
        }
    }
</script>
