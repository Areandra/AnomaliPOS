<x-app-layout title="Status Pesanan - AnoPos">
    <x-customer-layout :sessionToken="$data['token']">

        @php
            $sessionToken = $data['token'];
            $tableNumber = $data['table']['table_number'];
            $createdAt = $data['created_at'];
        @endphp

        <div x-data="activeOrderApp({
            session: @js($data),
        })" class="relative z-10">

            {{-- ── Session Header ── --}}
            <div class="space-y-3 border-b border-dashed pb-6 pt-2"
                :class="isDark ? 'border-white/10' : 'border-gray-200'">

                {{-- Table Number + Waktu --}}
                <div class="flex items-center justify-between">
                    <h2 class="flex items-center gap-2 text-sm font-black uppercase tracking-widest"
                        :class="isDark ? 'text-amber-500' : 'text-orange-600'">
                        <span class="h-1 w-4 rounded-full" :class="isDark ? 'bg-amber-500' : 'bg-orange-600'"></span>
                        TABLE {{ $tableNumber }}
                    </h2>

                    <div class="flex items-center gap-2 rounded-2xl border px-3 py-2"
                        :class="isDark ? 'bg-white/5 border-white/5' : 'bg-gray-50 border-gray-100'">
                        <i data-lucide="calendar" class="h-3 w-3 opacity-40"></i>
                        <span class="text-[10px] font-black uppercase tracking-widest opacity-60">
                            {{ \Carbon\Carbon::parse($createdAt)->format('H:i') }}
                        </span>
                    </div>
                </div>

                {{-- Session Token --}}
                <div class="flex items-center gap-3 rounded-2xl border px-3 py-2"
                    :class="isDark ? 'bg-white/5 border-white/5' : 'bg-gray-50 border-gray-100'">
                    <i data-lucide="activity" class="h-3 w-3 shrink-0 opacity-40"></i>
                    <span class="shrink-0 text-[10px] font-black uppercase tracking-widest opacity-60">
                        Session :
                    </span>
                    <span class="truncate font-mono text-[10px] opacity-60">
                        {{ $sessionToken }}
                    </span>
                </div>
            </div>

            {{-- ── Empty State ── --}}
            <div x-show="orders.length === 0" class="flex flex-col items-center justify-center py-32 opacity-20">
                <i data-lucide="utensils-crossed" class="h-16 w-16" style="stroke-width: 1px"></i>
                <p class="mt-4 text-xs font-black uppercase tracking-widest">
                    Belum ada pesanan aktif
                </p>
            </div>

            {{-- ── Orders List ── --}}
            <div class="space-y-6 pb-72 pt-4">
                <template x-for="order in orders" :key="order.id">
                    <div>

                        {{-- Order Header --}}
                        <div class="mb-4 flex items-center justify-between px-1">
                            <div class="flex items-center gap-2">
                                <div class="h-2 w-2 animate-pulse rounded-full bg-amber-500"></div>
                                <h3 class="text-xs font-black uppercase tracking-widest">
                                    Order #<span x-text="order.order_code"></span>
                                </h3>
                            </div>
                            <span class="rounded-full border px-3 py-1 text-[9px] font-black uppercase tracking-widest"
                                :class="getStatusStyle(order.status)" x-text="order.status">
                            </span>
                        </div>

                        {{-- Items --}}
                        <div class="space-y-3">
                            <template x-for="item in nonCartItems(order)" :key="item.id">
                                <div class="group flex items-center gap-4 rounded-3xl border p-3 transition-all duration-300"
                                    :class="isDark
                                        ?
                                        'bg-slate-900 border-white/5 shadow-xl hover:border-amber-500/30' :
                                        'bg-white border-gray-100 shadow-sm hover:border-orange-200'">

                                    {{-- Image --}}
                                    <div class="h-16 w-16 shrink-0 overflow-hidden rounded-2xl shadow-inner">
                                        <img :src="item.menu_item?.image_url ??
                                            'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=400&h=300&auto=format&fit=crop'"
                                            :alt="item.menu_item?.name"
                                            class="h-full w-full object-cover transition-transform group-hover:scale-105" />
                                    </div>

                                    {{-- Info --}}
                                    <div class="min-w-0 flex-1">
                                        <div class="mb-2 flex items-start justify-between gap-2">
                                            <h4 class="line-clamp-1 text-xs font-bold"
                                                :class="isDark ? 'text-white' : 'text-slate-800'"
                                                x-text="item.menu_item?.name"></h4>
                                            <span class="shrink-0 text-xs font-black opacity-40"
                                                x-text="'x' + item.quantity"></span>
                                        </div>

                                        <div class="flex items-center justify-between">
                                            {{-- Status Badge --}}
                                            <span
                                                class="flex items-center gap-1.5 rounded-lg border px-2 py-0.5 text-[8px] font-black uppercase tracking-widest"
                                                :class="getStatusStyle(item.status)">
                                                <template x-if="item.status === 'cooking'">
                                                    <i data-lucide="flame" class="h-2.5 w-2.5"></i>
                                                </template>
                                                <template x-if="item.status === 'ready'">
                                                    <i data-lucide="chef-hat" class="h-2.5 w-2.5"></i>
                                                </template>
                                                <template x-if="item.status === 'delivered'">
                                                    <i data-lucide="check-circle-2" class="h-2.5 w-2.5"></i>
                                                </template>
                                                <span x-text="item.status"></span>
                                            </span>

                                            {{-- Price --}}
                                            <p class="text-xs font-black italic"
                                                :class="isDark ? 'text-amber-500' : 'text-orange-600'">
                                                <span class="mr-0.5 text-[9px]">Rp</span>
                                                <span x-text="formatRupiah(item.price)"></span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        {{-- Order Footer / Subtotal --}}
                        <div class="mt-4 flex items-center justify-between rounded-3xl border-t-4 border-amber-500/50 p-5"
                            :class="isDark ? 'bg-white/5 border-white/5' : 'bg-gray-50 border-gray-100'">
                            <div>
                                <p class="text-[9px] font-black uppercase tracking-widest opacity-40">
                                    Subtotal Order
                                </p>
                                <p class="mt-0.5 text-sm font-black italic">
                                    <span class="mr-0.5 text-[10px]"
                                        :class="isDark ? 'text-amber-500' : 'text-orange-600'">Rp</span>
                                    <span x-text="formatRupiah(order.total)"></span>
                                </p>
                            </div>

                            {{-- Payment Status --}}
                            <template x-if="order.payment">
                                <div class="flex items-center gap-2 text-emerald-500">
                                    <i data-lucide="check-circle-2" class="h-4 w-4"></i>
                                    <span class="text-[10px] font-black uppercase tracking-widest">Lunas</span>
                                </div>
                            </template>
                            <template x-if="!order.payment">
                                <div class="flex items-center gap-2 text-amber-500">
                                    <i data-lucide="clock" class="animate-spin-slow h-4 w-4"></i>
                                    <span class="text-[10px] font-black uppercase tracking-widest">Unpaid</span>
                                </div>
                            </template>
                        </div>

                    </div>
                </template>
            </div>

            {{-- ── Final Payment Info ── --}}
            <div x-show="hasPaidOrder()" class="pt-0">
                <div class="flex flex-col gap-4 rounded-[2.5rem] border-2 border-dashed border-emerald-500/30 p-6"
                    :class="isDark ? 'bg-emerald-500/5' : 'bg-emerald-50'">

                    <div class="flex items-center gap-3 text-emerald-500">
                        <i data-lucide="credit-card" class="h-5 w-5"></i>
                        <span class="text-xs font-black uppercase tracking-[0.2em]">
                            Informasi Pembayaran
                        </span>
                    </div>

                    <div class="space-y-1">
                        <p class="text-[10px] font-black uppercase tracking-widest opacity-50">
                            Total yang telah dibayar
                        </p>
                        <p class="text-3xl font-black italic tracking-tighter"
                            :class="isDark ? 'text-emerald-400' : 'text-emerald-600'">
                            <span class="mr-1 text-base">Rp</span>
                            <span x-text="formatRupiah(totalPaid())"></span>
                        </p>
                    </div>
                </div>
            </div>

        </div>

    </x-customer-layout>
</x-app-layout>

<script>
    function activeOrderApp(props) {
        console.log(props)
        return {
            session: props.session ?? {},
            orders: props.session.orders ?? [],

            init() {
                this.$nextTick(() => lucide.createIcons());
                this.initSocket();
            },

            initSocket() {
                // Uncomment jika menggunakan Socket.IO
                // const socket = io('', { reconnectionAttempts: 3 });
                // this.orders.forEach(o => {
                //     socket.on(`NewOrdersData${o.id}`, (newData) => {
                //         this.orders = this.orders.map(order =>
                //             order.id === newData.id ? newData : order
                //         );
                //         this.$nextTick(() => lucide.createIcons());
                //     });
                // });
            },

            nonCartItems(order) {
                return (order.items ?? []).filter(i => i.status !== 'cart');
            },

            getStatusStyle(status) {
                switch (status) {
                    case 'ordered':
                        return 'bg-rose-500/10 text-rose-500 border-rose-500/20';
                    case 'cooking':
                        return 'bg-amber-500/10 text-amber-500 border-amber-500/20 animate-pulse';
                    case 'ready':
                    case 'delivered':
                    case 'served':
                        return 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20';
                    default:
                        return 'bg-slate-500/10 text-slate-500 border-slate-500/20';
                }
            },

            hasPaidOrder() {
                return this.orders.some(o => o.payment);
            },

            totalPaid() {
                return this.orders
                    .filter(o => o.payment)
                    .reduce((acc, curr) => acc + (curr.payment?.amount || 0), 0);
            },

            formatRupiah(amount) {
                return Number(amount).toLocaleString('id-ID');
            },
        }
    }
</script>
