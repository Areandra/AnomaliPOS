<x-app-layout title="Keranjang - AnoPos">
    <x-customer-layout :sessionToken="$sessionToken">

        <div x-data="cartApp({
            orders: @js($ordersData),
            sessionToken: @js($sessionToken),
        })" class="relative z-10">

            {{-- ── Section Title ── --}}
            <div class="px-2 pb-2 pt-4">
                <h2 class="flex items-center gap-2 text-sm font-black uppercase tracking-widest"
                    :class="isDark ? 'text-amber-500' : 'text-orange-600'">
                    <span class="h-1 w-4 rounded-full" :class="isDark ? 'bg-amber-500' : 'bg-orange-600'"></span>
                    Keranjang
                </h2>
            </div>

            {{-- ── Empty State ── --}}
            <div x-show="cartItems().length === 0" class="flex flex-col items-center justify-center py-20 opacity-30">
                <i data-lucide="receipt-text" class="h-16 w-16" style="stroke-width: 1px"></i>
                <p class="mt-4 text-xs font-black uppercase tracking-widest">Keranjang Masih Kosong</p>
            </div>

            {{-- ── Cart List ── --}}
            <div class="space-y-4 pb-80 pt-2">
                <template x-for="item in cartItems()" :key="item.id">
                    <div class="group relative flex items-center gap-4 rounded-3xl border p-4 transition-all duration-300"
                        :class="isDark
                            ?
                            'bg-slate-900 border-white/5 shadow-xl hover:border-amber-500/30' :
                            'bg-white border-gray-100 shadow-md hover:border-orange-200'">

                        {{-- Image --}}
                        <div class="h-20 w-20 shrink-0 overflow-hidden rounded-2xl shadow-inner">
                            <img :src="item.menuItem?.imageUrl ??
                                'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=400&h=300&auto=format&fit=crop'"
                                :alt="item.menuItem?.name"
                                class="h-full w-full object-cover transition-transform group-hover:scale-105" />
                        </div>

                        {{-- Info --}}
                        <div class="min-w-0 flex-1">
                            <h3 class="mb-1 line-clamp-1 text-sm font-bold"
                                :class="isDark ? 'text-white' : 'text-slate-800'"
                                x-text="item.menuItem?.name"></h3>

                            <p class="mb-2 text-[10px] font-bold"
                                :class="isDark ? 'text-slate-400' : 'text-gray-500'">
                                <span class="mr-0.5"
                                    :class="isDark ? 'text-amber-500' : 'text-orange-600'">Rp</span>
                                <span x-text="formatRupiah(parseInt(item.price))"></span> / Unit
                            </p>

                            <div class="flex items-end justify-between">
                                {{-- Subtotal --}}
                                <p class="text-sm font-black italic">
                                    <span class="mr-0.5 text-[10px]"
                                        :class="isDark ? 'text-amber-500' : 'text-orange-600'">Rp</span>
                                    <span x-text="formatRupiah(parseInt(item.price) * item.quantity)"></span>
                                </p>

                                {{-- Qty Controller --}}
                                <div class="flex items-center gap-1 rounded-full p-1"
                                    :class="isDark ? 'bg-black/20' : 'bg-gray-100'">
                                    <button @click="updateQty(item.id, item.quantity - 1)"
                                        class="rounded-full p-1.5 transition-all active:scale-90"
                                        :class="isDark
                                            ?
                                            'bg-slate-700 text-white hover:bg-red-500' :
                                            'bg-white text-gray-800 shadow-sm hover:bg-orange-100'">
                                        <i data-lucide="minus" class="h-3.5 w-3.5" style="stroke-width: 3px"></i>
                                    </button>
                                    <span class="w-8 text-center text-xs font-black"
                                        x-text="item.quantity"></span>
                                    <button @click="updateQty(item.id, item.quantity + 1)"
                                        class="rounded-full p-1.5 shadow-sm transition-all active:scale-90"
                                        :class="isDark
                                            ?
                                            'bg-amber-500 text-slate-900 hover:bg-amber-400' :
                                            'bg-orange-600 text-white hover:bg-orange-700'">
                                        <i data-lucide="plus" class="h-3.5 w-3.5" style="stroke-width: 3px"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Delete Button --}}
                        <button @click="deleteItem(item.id)"
                            class="absolute -right-1 -top-1 rounded-full border p-2 shadow-lg transition-all active:scale-75"
                            :class="isDark
                                ?
                                'bg-slate-800 border-white/5 text-rose-500 hover:bg-rose-500 hover:text-white' :
                                'bg-white border-gray-100 text-rose-600 hover:bg-rose-50'">
                            <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                        </button>
                    </div>
                </template>
            </div>

            {{-- ── Sticky Bottom Summary ── --}}
            <div x-show="cartItems().length > 0"
                class="pointer-events-none fixed bottom-28 left-0 right-0 z-50 px-4 pb-2 pt-0">
                <div class="pointer-events-auto mx-auto flex max-w-lg items-center justify-between rounded-[2.5rem] border p-6 shadow-2xl backdrop-blur-xl transition-all duration-500"
                    :class="isDark
                        ?
                        'bg-slate-900/80 border-white/10 shadow-black/40' :
                        'bg-white/80 border-gray-200 shadow-slate-200'">

                    <div class="flex flex-col">
                        <span class="text-[9px] font-black uppercase tracking-widest opacity-40">
                            Total Tagihan
                        </span>
                        <p class="mt-0.5 text-sm font-black italic">
                            <span class="mr-0.5 text-[10px]"
                                :class="isDark ? 'text-emerald-400' : 'text-emerald-600'">Rp</span>
                            <span :class="isDark ? 'text-emerald-400' : 'text-emerald-600'"
                                x-text="formatRupiah(totalWithTax())"></span>
                        </p>
                        <span class="mt-1 text-[9px] font-bold uppercase tracking-wider opacity-30">
                            Sudah termasuk pajak 10%
                        </span>
                    </div>

                    <button @click="placeOrder()"
                        class="flex items-center gap-2 rounded-2xl px-8 py-4 text-xs font-black uppercase tracking-widest shadow-xl transition-all active:scale-95"
                        :class="isDark
                            ?
                            'bg-emerald-500 text-slate-950 shadow-emerald-500/20 hover:bg-emerald-400' :
                            'bg-emerald-600 text-white shadow-emerald-600/20 hover:bg-emerald-700'">
                        Order
                        <i data-lucide="arrow-right" class="h-4 w-4"></i>
                    </button>
                </div>
            </div>

        </div>

    </x-customer-layout>
</x-app-layout>

<script>
    function cartApp(props) {
        return {
            orders: props.orders ?? [],
            sessionToken: props.sessionToken ?? null,

            init() {
                this.$nextTick(() => lucide.createIcons());
            },

            cartItems() {
                return this.orders.flatMap(o =>
                    (o.items ?? []).filter(item => item.status === 'cart')
                );
            },

            totalHarga() {
                return this.cartItems().reduce(
                    (total, item) => total + parseInt(item.price) * item.quantity,
                    0
                );
            },

            totalWithTax() {
                const total = this.totalHarga();
                return total + total * 0.1;
            },

            formatRupiah(amount) {
                return Number(amount).toLocaleString('id-ID');
            },

            async sendPostRequest(url, payload) {
                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify(payload),
                    });

                    if (response.ok) {
                        const data = await response.json();
                        if (data.order) {
                            this.orders = [data.order];
                        }
                        this.$nextTick(() => lucide.createIcons());
                    }
                } catch (error) {
                    console.error('Gagal memproses aksi cart:', error);
                }
            },

            updateQty(itemId, qty) {
                if (qty < 1) return this.deleteItem(itemId);
                this.sendPostRequest(
                    `/order/session/${this.sessionToken}/update-qty`, {
                        itemId,
                        qty
                    }
                );
            },

            deleteItem(itemId) {
                this.sendPostRequest(
                    `/order/session/${this.sessionToken}/delete-item`, {
                        itemId
                    }
                );
            },

            placeOrder() {
                if (!this.orders[0]?.id) return;
                window.location.href = `/order/session/${this.sessionToken}/place-order`;
                // Jika menggunakan POST, ganti dengan sendPostRequest tanpa payload
                // this.sendPostRequest(`/order/session/${this.sessionToken}/place-order`, {});
            },
        }
    }
</script>
