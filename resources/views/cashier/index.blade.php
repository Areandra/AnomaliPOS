<x-cashier-layout :currentShift="$currentShift" pageTitle="Point of Sale">

    @slot('headerLinks')
        <a href="/menu"
            class="flex items-center gap-2 rounded-full px-4 py-2 text-[10px] font-black uppercase tracking-widest shadow-sm duration-300"
            :class="isDark ? 'bg-slate-800 text-amber-500 hover:bg-slate-700 border border-white/5' :
                'bg-white text-orange-600 hover:bg-orange-50 border border-gray-200'">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z" />
                <circle cx="12" cy="12" r="3" />
            </svg>
            <span class="hidden md:block">Manajemen</span>
        </a>
    @endslot

    <div x-data="cashierApp(
        {{ json_encode($categoriesData->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'sort_order' => $c->sort_order])) }},
        {{ json_encode($menuItemsData->map(fn($m) => ['id' => $m->id, 'name' => $m->name, 'description' => $m->description, 'price' => $m->price, 'is_available' => $m->is_available, 'category_id' => $m->category_id, 'image_url' => $m->image_url])) }},
        {{ json_encode($ordersData->map(fn($o) => ['id' => $o->id, 'order_code' => $o->order_code, 'status' => $o->status, 'created_at' => $o->created_at, 'table' => $o->table ? ['table_number' => $o->table->table_number] : null, 'payment' => $o->payment])) }},
        {{ json_encode($currentShift) }}
    )" class="h-full">

        {{-- Open Shift Panel --}}
        <template x-if="!shift">
            <x-open-shift-panel />
        </template>

        {{-- Close Shift Modal --}}
        <template x-if="openCloseModal">
            <x-close-shift-modal />
        </template>

        {{-- Active Order Panel --}}
        <x-active-order-panel :categories="$categoriesData" />

        <x-receipt-modal />
        <x-qr-modal />

    </div>

</x-cashier-layout>

<script>
    function cashierApp(categoriesData, menuItemsData, ordersData, currentShift) {
        return {
            categories: categoriesData,
            menuItems: menuItemsData,
            orders: ordersData,
            shift: currentShift,
            selectedOrder: null,
            selectedOrderId: 0,
            categorySelect: -1,
            search: '',
            openCloseModal: false,
            plan: '{{ session('restaurant_plan', 'starter') }}',

            askAI(module = 'cashier', customPayload = null) {
                // Auto-generate payload dari data cashier yang aktif
                const payload = customPayload || {
                    // Data orders aktif
                    meta: {
                        source: 'cashier_pos',
                        module,
                        generated_at: new Date().toISOString(),
                        timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
                        restaurant_plan: this.plan,
                        ai_rules: {
                            no_assumption: true,
                            no_hallucination: true,
                            require_real_data: true,
                            fallback_if_missing: true
                        }
                    },

                    shift: this.shift ? {
                        id: this.shift.id,
                        start_time: this.shift.start_time,
                        duration_hours: this.shift.start_time ?
                            Math.round(
                                ((new Date() - new Date(this.shift.start_time)) / 3600000) * 10
                            ) / 10 : 0,
                        cashier_name: this.shift.user?.name ?? null,
                        starting_cash: this.shift.starting_cash ?? null,
                        current_cash: this.shift.current_cash ?? null,
                        total_transactions: this.shift.total_transactions ?? null,
                        total_sales: this.shift.total_sales ?? null,
                        status: this.shift.status ?? 'active'
                    } : null,

                    ui_state: {
                        selected_order_id: this.selectedOrderId,
                        selected_category: this.categorySelect,
                        selected_category_name: this.categorySelect === -1 ?
                            'all' : this.categories.find(c => c.id === this.categorySelect)?.name ?? null,
                        search_query: this.search || null
                    },

                    summary: {
                        total_categories: this.categories.length,
                        total_menu_items: this.menuItems.length,
                        total_active_orders: this.orders.length,
                        unpaid_orders: this.orders.filter(o => !o.payment).length,
                        paid_orders: this.orders.filter(o => o.payment).length,
                        occupied_tables: [
                            ...new Set(
                                this.orders
                                .map(o => o.table?.table_number)
                                .filter(Boolean)
                            )
                        ],
                        occupied_tables_count: [
                            ...new Set(
                                this.orders
                                .map(o => o.table?.table_number)
                                .filter(Boolean)
                            )
                        ].length,
                        average_wait_time_minutes: this.calculateAverageWaitTime()
                    },

                    categories: this.categories.map(cat => ({
                        id: cat.id,
                        name: cat.name,
                        sort_order: cat.sort_order,
                        total_items: this.menuItems.filter(
                            m => m.category_id === cat.id
                        ).length
                    })),

                    menu_catalog: this.menuItems.map(menu => ({
                        id: menu.id,
                        name: menu.name,
                        description: menu.description,
                        price: Number(menu.price),
                        category_id: menu.category_id,
                        category_name: this.categories.find(c => c.id === menu.category_id)?.name ??
                            null,
                        is_available: menu.is_available,
                        image_url: menu.image_url ?? null
                    })),

                    active_orders: this.orders.map(order => ({
                        id: order.id,
                        order_code: order.order_code,
                        status: order.status,
                        created_at: order.created_at,

                        duration_minutes: Math.round(
                            (new Date() - new Date(order.created_at)) / 60000
                        ),

                        table: order.table ? {
                            table_number: order.table.table_number
                        } : null,

                        payment: order.payment ? {
                            status: 'paid',
                            method: order.payment.payment_method ?? null,
                            amount: order.payment.amount ?? null,
                            paid_at: order.payment.created_at ?? null
                        } : {
                            status: 'unpaid'
                        },

                        items: (order.items || []).map(item => ({
                            id: item.id,

                            menu_item_id: item.menu_item?.id ??
                                item.menuItem?.id ??
                                null,

                            name: item.name ??
                                item.menu_item?.name ??
                                item.menuItem?.name ??
                                null,

                            qty: Number(item.quantity || 0),

                            price: Number(item.price || 0),

                            subtotal: Number(item.quantity || 0) *
                                Number(item.price || 0),

                            notes: item.notes ?? null,

                            status: item.status ?? null,

                            category: item.menu_item?.category?.name ??
                                item.menuItem?.category?.name ??
                                null
                        })),

                        totals: {
                            total_items: (order.items || []).reduce(
                                (sum, item) => sum + Number(item.quantity || 0),
                                0
                            ),

                            total_unique_items: (order.items || []).length,

                            subtotal: (order.items || []).reduce(
                                (sum, item) =>
                                sum +
                                (Number(item.quantity || 0) *
                                    Number(item.price || 0)),
                                0
                            ),

                            estimated_total: order.total ??
                                order.total_price ??
                                null
                        }
                    })),

                    selected_order: this.selectedOrder ? {
                        id: this.selectedOrder.id,
                        order_code: this.selectedOrder.order_code,
                        status: this.selectedOrder.status,
                        created_at: this.selectedOrder.created_at,

                        table: this.selectedOrder.table ? {
                            id: this.selectedOrder.table.id ?? null,
                            table_number: this.selectedOrder.table.table_number
                        } : null,

                        notes: this.selectedOrder.notes ?? null,

                        total: this.selectedOrder.total ??
                            this.selectedOrder.total_price ??
                            null,

                        subtotal: this.selectedOrder.subtotal ?? null,

                        tax: this.selectedOrder.tax ?? null,

                        discount: this.selectedOrder.discount ?? null,

                        items: (this.selectedOrder.items || []).map(item => ({
                            id: item.id,

                            menu_item_id: item.menu_item?.id ??
                                item.menuItem?.id ??
                                null,

                            name: item.name ??
                                item.menu_item?.name ??
                                item.menuItem?.name ??
                                null,

                            quantity: item.quantity,

                            price: item.price,

                            subtotal: Number(item.quantity || 0) *
                                Number(item.price || 0),

                            notes: item.notes ?? null,

                            status: item.status ?? null,

                            category: item.menu_item?.category?.name ??
                                item.menuItem?.category?.name ??
                                null
                        })),

                        payment: this.selectedOrder.payment ? {
                            method: this.selectedOrder.payment.payment_method ?? null,
                            amount: this.selectedOrder.payment.amount ?? null,
                            paid_at: this.selectedOrder.payment.created_at ?? null
                        } : null
                    } : null,
                };

                // Panggil function global openAiModal
                window.dispatchEvent(new CustomEvent('open-ai-modal', {
                    detail: {
                        module,
                        payload
                    }
                }));
            },

            // Helper method untuk hitung rata-rata waktu tunggu
            calculateAverageWaitTime() {
                const activeOrders = this.orders.filter(o => o.status !== 'completed');
                if (activeOrders.length === 0) return 0;

                const totalWait = activeOrders.reduce((sum, order) => {
                    return sum + (new Date() - new Date(order.created_at)) / 60000;
                }, 0);

                return Math.round(totalWait / activeOrders.length);
            },

            get atLeastPro() {
                return this.plan !== 'starter';
            },

            get groupedMenu() {
                const term = this.search.toLowerCase();
                return [...this.categories]
                    .sort((a, b) => (a.sort_order ?? 0) - (b.sort_order ?? 0))
                    .map(cat => ({
                        ...cat,
                        items: this.menuItems.filter(m =>
                            m.is_available &&
                            m.category_id === cat.id &&
                            (!term || m.name.toLowerCase().includes(term))
                        )
                    }))
                    .filter(cat => cat.items.length > 0);
            },

            get filteredMenu() {
                return this.groupedMenu.filter(cat =>
                    this.categorySelect > 0 ? cat.id === this.categorySelect : true
                );
            },

            get cartInfoMap() {
                const map = {};
                (this.selectedOrder?.items || []).forEach(item => {
                    const menuId = item.menu_item?.id ?? item.menuItem?.id;
                    if (!map[menuId]) map[menuId] = {
                        totalQty: 0,
                        cartItem: null
                    };
                    map[menuId].totalQty += item.quantity;
                    if (item.status === 'cart' && !item.notes) {
                        map[menuId].cartItem = item;
                    }
                });
                return map;
            },

            async fetchOrder(id) {
                if (!id) {
                    this.selectedOrder = null;
                    return;
                }
                try {
                    const res = await fetch(`/order/${id}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    this.selectedOrder = await res.json();
                } catch (e) {
                    console.error('Gagal fetch order');
                }
            },

            async handleAddOrUpdate(item) {
                if (!this.selectedOrderId) return alert('Silahkan pilih nomor meja/order terlebih dahulu!');
                const info = this.cartInfoMap[item.id] || {
                    totalQty: 0,
                    cartItem: null
                };
                try {
                    let res;
                    if (info.cartItem) {
                        res = await this.post('/order/update-qty', {
                            itemId: info.cartItem.id,
                            qty: info.cartItem.quantity + 1
                        });
                    } else {
                        res = await this.post('/order/add-item', {
                            orderId: this.selectedOrderId,
                            menuItemId: item.id,
                            qty: 1
                        });
                    }
                    this.selectedOrder = await res.json();
                } catch (e) {
                    alert('Gagal memproses item');
                }
            },

            async updateItemQty(itemId, qty) {
                try {
                    let res;
                    if (qty < 1) {
                        res = await this.post('/order/delete-item', {
                            itemId
                        });
                    } else {
                        res = await this.post('/order/update-qty', {
                            itemId,
                            qty
                        });
                    }
                    this.selectedOrder = await res.json();
                } catch (e) {
                    alert('Gagal update quantity');
                }
            },

            async orderAll(orderId) {
                try {
                    const res = await this.post(`/order/place-order/${orderId}`, {});
                    this.selectedOrder = await res.json();
                } catch (e) {
                    alert('Gagal kirim order ke dapur');
                }
            },

            async newOrder() {
                if (this.atLeastPro) {
                    window.location.href = '/cashier/order/start';
                } else {
                    const res = await this.post('/order', {});
                    if (res.ok || res.redirected) window.location.reload();
                }
            },

            post(url, data) {
                return fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify(data),
                });
            },

            selectOrder(id) {
                this.selectedOrderId = id;
                this.fetchOrder(id);
            },

            init() {
                this.$watch('selectedOrderId', id => this.fetchOrder(id));
                document.addEventListener('keydown', (e) => {
                    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                        e.preventDefault();
                        this.askAI('cashier'); // atau 'general'
                    }
                });

                window.addEventListener('floating-btn-click', () => {
                    this.askAI('cashier'); // atau 'general'
                });
            }
        }
    }

    function openShiftPanel() {
        return {
            startingCash: '',
            loading: false,

            handleNumpad(num) {
                if (this.startingCash.length >= 12) return;
                this.startingCash = (this.startingCash === '0' ? '' : this.startingCash) + num;
            },
            handleClear() {
                this.startingCash = '';
            },
            handleBackspace() {
                this.startingCash = this.startingCash.length > 1 ? this.startingCash.slice(0, -1) : '';
            },

            async handleOpenShift() {
                if (!this.startingCash || Number(this.startingCash) <= 0) {
                    alert('Silahkan masukkan jumlah modal awal.');
                    return;
                }
                this.loading = true;
                try {
                    const res = await fetch('/shifts/open', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({
                            startingCash: Number(this.startingCash)
                        })
                    });
                    const data = await res.json();
                    if (!res.ok) throw new Error(data.message);
                    this.shift = data;
                } catch (e) {
                    alert(e.message || 'Terjadi kesalahan.');
                } finally {
                    this.loading = false;
                }
            }
        }
    }

    function closeShiftModal() {
        return {
            step: 1,
            counts: {},
            activeValue: 100000,
            notes: '',
            loading: false,
            summary: null,
            closedUser: null,
            currencies: [{
                    value: 100000,
                    label: '100.000',
                    gradient: 'from-red-600 via-red-500 to-red-700',
                    isCoin: false
                },
                {
                    value: 75000,
                    label: '75.000',
                    gradient: 'from-red-400 via-pink-400 to-red-500',
                    isCoin: false
                },
                {
                    value: 50000,
                    label: '50.000',
                    gradient: 'from-blue-600 via-blue-500 to-blue-700',
                    isCoin: false
                },
                {
                    value: 20000,
                    label: '20.000',
                    gradient: 'from-green-600 via-green-500 to-green-700',
                    isCoin: false
                },
                {
                    value: 10000,
                    label: '10.000',
                    gradient: 'from-purple-600 via-purple-500 to-purple-700',
                    isCoin: false
                },
                {
                    value: 5000,
                    label: '5.000',
                    gradient: 'from-yellow-600 via-yellow-500 to-yellow-700',
                    isCoin: false
                },
                {
                    value: 2000,
                    label: '2.000',
                    gradient: 'from-slate-500 via-slate-400 to-slate-600',
                    isCoin: false
                },
                {
                    value: 1000,
                    label: '1.000',
                    gradient: 'from-lime-600 via-lime-500 to-lime-700',
                    isCoin: false
                },
                {
                    value: 500,
                    label: '500',
                    gradient: 'from-zinc-400 via-zinc-300 to-zinc-500',
                    isCoin: true
                },
                {
                    value: 200,
                    label: '200',
                    gradient: 'from-zinc-300 via-zinc-200 to-zinc-400',
                    isCoin: true
                },
            ],

            get totalPhysical() {
                return Object.entries(this.counts).reduce((acc, [val, count]) => acc + Number(val) * (Number(
                    count) || 0), 0);
            },

            handleNumpad(num) {
                const current = this.counts[this.activeValue] || '';
                if (current.length >= 4) return;
                this.counts = {
                    ...this.counts,
                    [this.activeValue]: (current === '0' ? '' : current) + num
                };
            },
            handleClear() {
                this.counts = {
                    ...this.counts,
                    [this.activeValue]: '0'
                };
            },
            handleBackspace() {
                const current = this.counts[this.activeValue] || '';
                this.counts = {
                    ...this.counts,
                    [this.activeValue]: current.length > 1 ? current.slice(0, -1) : '0'
                };
            },

            formatRp(v) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(v);
            },

            async handleCloseShift() {
                this.loading = true;
                try {
                    const res = await fetch('/shifts/close', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({
                            cashPhysical: this.totalPhysical,
                            notes: this.notes
                        })
                    });
                    const data = await res.json();
                    if (!res.ok) throw new Error(data.message);
                    this.summary = data.summary;
                    this.closedUser = data.data?.user;
                    this.shift = null;
                    this.step = 3;
                } catch (e) {
                    alert(e.message || 'Gagal menutup shift.');
                } finally {
                    this.loading = false;
                }
            }
        }
    }

    function orderDetailPanel() {
        return {
            showPayment: false,
            showNotesModal: false,
            orderNoteItemId: null,
            noteText: '',
            endSessionToken: '',

            async handleEndSession(sessionToken) {
                if (this.endSessionToken === sessionToken) {
                    const res = await fetch(`/session/${sessionToken}/end`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    });
                    if (res.ok) {
                        this.selectedOrderId = 0;
                        this.selectedOrder = null;
                        this.endSessionToken = '';
                    }
                } else {
                    this.endSessionToken = sessionToken;
                    alert('Klik tombol sekali lagi untuk konfirmasi');
                }
            },

            async saveNote() {
                if (!this.orderNoteItemId) return;
                const res = await fetch(`/order/${this.orderNoteItemId}/notes`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({
                        notes: this.noteText
                    })
                });
                this.selectedOrder = await res.json();
                this.showNotesModal = false;
                this.noteText = '';
                this.orderNoteItemId = null;
            },

            init() {
                this.$watch('orderNoteItemId', (id) => {
                    if (id) {
                        const item = (this.selectedOrder?.items ?? []).find(i => i.id === id);
                        this.noteText = item?.notes ?? '';
                    }
                });
            }
        }
    }

    function paymentPanel() {
        return {
            paymentMethod: 'cash',
            amountPaid: '0',
            loading: false,

            paymentMethods: [{
                    type: 'cash',
                    label: 'Tunai',
                    icon: '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/></svg>'
                },
                {
                    type: 'qris',
                    label: 'QRIS',
                    icon: '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="5" height="5" x="3" y="3" rx="1"/><rect width="5" height="5" x="16" y="3" rx="1"/><rect width="5" height="5" x="3" y="16" rx="1"/><path d="M21 16h-3a2 2 0 0 0-2 2v3"/><path d="M21 21v.01"/><path d="M12 7v3a2 2 0 0 1-2 2H7"/><path d="M3 12h.01"/><path d="M12 3h.01"/><path d="M12 16v.01"/><path d="M16 12h1"/><path d="M21 12v.01"/><path d="M12 21v-1"/></svg>'
                },
                {
                    type: 'transfer',
                    label: 'E-Wallet/Bank',
                    icon: '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/></svg>'
                },
                {
                    type: 'debit',
                    label: 'Debit',
                    icon: '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>'
                },
            ],

            get quickCashOptions() {
                const total = Number(this.selectedOrder?.total ?? 0);
                return [
                    total,
                    Math.ceil(total / 10000) * 10000,
                    Math.ceil(total / 50000) * 50000,
                    100000,
                ].filter((v, i, a) => a.indexOf(v) === i && v >= total);
            },

            get changeAmount() {
                const total = Number(this.selectedOrder?.total ?? 0);
                return Math.max(Number(this.amountPaid) - total, 0);
            },

            selectMethod(type) {
                this.paymentMethod = type;
                if (type !== 'cash') {
                    this.amountPaid = (this.selectedOrder?.total ?? 0).toString();
                }
            },

            handleNumpad(num) {
                this.amountPaid = this.amountPaid === '0' ? num : this.amountPaid + num;
            },
            handleClear() {
                this.amountPaid = '0';
            },
            handleBackspace() {
                this.amountPaid = this.amountPaid.length > 1 ? this.amountPaid.slice(0, -1) : '0';
            },

            formatRp(value) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(Number(value));
            },

            async handlePay() {
                const total = Number(this.selectedOrder?.total ?? 0);
                if (this.paymentMethod === 'cash' && Number(this.amountPaid) < total) {
                    alert('Uang tunai tidak mencukupi.');
                    return;
                }
                this.loading = true;
                try {
                    const res = await fetch('/payments', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({
                            orderId: this.selectedOrder.id,
                            paymentMethod: this.paymentMethod,
                            amount: Number(this.amountPaid),
                        })
                    });
                    const data = await res.json();
                    console.log(data)
                    if (!res.ok) throw new Error(data.message);

                    // Simpan data order terbaru dari response backend
                    this.selectedOrder = data.order;
                    this.showPayment = false;

                    // === TRIGGER STRUK / RECEIPT DI SINI ===
                    if (window.Alpine && Alpine.store('receipt')) {
                        Alpine.store('receipt').title = 'Payment Successfuly';
                        Alpine.store('receipt').restaurant = data.restaurant_name;
                        // Masukkan data transaksi/order ke store Alpine agar bisa dibaca receipt-modal
                        Alpine.store('receipt').order = this.selectedOrder;
                        Alpine.store('receipt').payment = data.payment;
                        // Ubah state menjadi true agar modal struk langsung menyembul muncul
                        Alpine.store('receipt').visible = true;
                    }
                    // =======================================

                } catch (e) {
                    alert(e.message || 'Gagal memproses pembayaran');
                } finally {
                    this.loading = false;
                }
            },

            init() {
                this.amountPaid = (this.selectedOrder?.total ?? 0).toString();
            }
        }
    }
</script>
