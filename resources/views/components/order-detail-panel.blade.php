{{-- Order Detail Panel --}}
<aside class="relative w-80 h-full flex flex-col shrink-0 border-l transition-colors duration-500"
    :class="isDark ? 'bg-slate-950 border-white/10 text-gray-200' : 'bg-white border-gray-200 text-gray-800'"
    x-data="orderDetailPanel()"
    x-init="init()">

    {{-- Close Button --}}
    <button @click="selectedOrderId = 0; selectedOrder = null"
        class="absolute top-1/2 -left-10 -translate-y-1/2 p-2 rounded-l-2xl shadow-xl transition-all"
        :class="isDark ? 'bg-slate-900 text-amber-500 hover:text-amber-400' : 'bg-white text-orange-600 hover:bg-orange-50'">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>

    {{-- Header --}}
    <div class="p-4 border-b" :class="isDark ? 'bg-slate-900/50 border-white/5' : 'bg-gray-50 border-gray-100'">
        <div class="flex justify-between items-center mb-2">
            <h2 class="font-black text-xs uppercase tracking-widest opacity-60">Bill Detail</h2>
            <span class="text-[10px] font-mono px-2 py-0.5 rounded" :class="isDark ? 'bg-white/5' : 'bg-white shadow-sm'"
                x-text="selectedOrder?.order_code"></span>
        </div>

        <template x-if="atLeastPro">
            <div class="space-y-1">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-xl flex items-center justify-center"
                        :class="isDark ? 'bg-amber-500/10 text-amber-500' : 'bg-orange-100 text-orange-600'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold leading-none" x-text="'Table ' + (selectedOrder?.table?.table_number ?? '-')"></p>
                        <p class="text-[10px] opacity-60 mt-1 uppercase"
                            x-text="selectedOrder?.type === 'dine_in' ? (selectedOrder?.guest + ' Guests') : 'Take Away'"></p>
                    </div>
                </div>
                <div class="text-[10px] opacity-50 flex items-center gap-1.5 ml-1">
                    <span class="h-1 w-1 rounded-full bg-current"></span>
                    <span x-text="'Cashier: ' + (selectedOrder?.session?.created_by_user?.name ?? '-')"></span>
                </div>
            </div>
        </template>
    </div>

    {{-- Items --}}
    <div class="flex-1 overflow-y-auto p-4 scrollbar-hide">
        <template x-if="(selectedOrder?.items ?? []).length === 0">
            <div class="flex flex-col items-center justify-center h-40 opacity-30">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                <p class="text-[10px] font-bold mt-2 uppercase">Empty Cart</p>
            </div>
        </template>

        <template x-for="status in ['cart','ordered','cooking','ready','delivered']" :key="status">
            <div x-show="(selectedOrder?.items ?? []).filter(i => i.status === status).length > 0" class="mb-6">
                <div class="flex items-center gap-2 mb-3">
                    <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider"
                        :class="{
                            'text-amber-500 bg-amber-500/10': status === 'cart',
                            'text-blue-500 bg-blue-500/10': status === 'ordered',
                            'text-orange-500 bg-orange-500/10': status === 'cooking',
                            'text-emerald-500 bg-emerald-500/10': status === 'ready',
                            'text-slate-500 bg-slate-500/10': status === 'delivered',
                        }"
                        x-text="{ cart: 'In Cart', ordered: 'Ordered', cooking: 'Cooking', ready: 'Ready', delivered: 'Served' }[status]">
                    </span>
                    <div class="h-px flex-1" :class="isDark ? 'bg-white/5' : 'bg-gray-100'"></div>
                </div>

                <div class="space-y-3">
                    <template x-for="item in (selectedOrder?.items ?? []).filter(i => i.status === status)" :key="item.id">
                        <div class="p-3 rounded-2xl border transition-all"
                            :class="isDark ? 'bg-slate-900/40 border-white/5 hover:bg-slate-800/60' : 'bg-white border-gray-100 shadow-sm'">
                            <div class="flex justify-between items-start gap-2 mb-2">
                                <div class="flex-1">
                                    <p class="text-sm font-bold leading-tight"
                                        :class="isDark ? 'text-gray-100' : 'text-gray-800'"
                                        x-text="item.menu_item?.name ?? item.menuItem?.name"></p>
                                    <p class="text-[10px] mt-0.5" :class="isDark ? 'text-gray-400' : 'text-gray-500'"
                                        x-text="item.quantity + ' × Rp ' + Number(item.price).toLocaleString('id-ID')"></p>
                                </div>
                                <span class="text-sm font-black" :class="isDark ? 'text-amber-500' : 'text-orange-600'"
                                    x-text="'Rp ' + Number(item.quantity * item.price).toLocaleString('id-ID')"></span>
                            </div>

                            <template x-if="atLeastPro && item.notes">
                                <div class="flex justify-between items-start gap-2 mb-2">
                                    <p class="text-[10px] mt-0.5" :class="isDark ? 'text-gray-400' : 'text-gray-500'" x-text="item.notes"></p>
                                </div>
                            </template>

                            <template x-if="status === 'cart' && !selectedOrder?.payment">
                                <div class="flex items-center gap-2 mt-3 pt-3 border-t border-dashed border-gray-500/20">
                                    <div class="flex flex-row space-x-4 items-center">
                                        <div class="flex items-center gap-1 rounded-full p-1" :class="isDark ? 'bg-black/20' : 'bg-gray-50'">
                                            <button @click="item.quantity > 1 && updateItemQty(item.id, item.quantity - 1)"
                                                class="h-7 w-7 flex items-center justify-center rounded-full transition-colors"
                                                :class="isDark ? 'bg-slate-700 hover:bg-slate-600' : 'bg-white shadow-sm hover:bg-gray-100'">-</button>
                                            <span class="w-6 text-center text-xs font-bold" x-text="item.quantity"></span>
                                            <button @click="updateItemQty(item.id, item.quantity + 1)"
                                                class="h-7 w-7 flex items-center justify-center rounded-full transition-colors"
                                                :class="isDark ? 'bg-slate-700 hover:bg-slate-600' : 'bg-white shadow-sm hover:bg-gray-100'">+</button>
                                        </div>
                                        <template x-if="atLeastPro">
                                            <button @click="orderNoteItemId = item.id; showNotesModal = true"
                                                class="ml-auto h-8 w-8 flex items-center justify-center text-amber-500 hover:bg-amber-500/10 rounded-full transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                            </button>
                                        </template>
                                    </div>
                                    <button @click="updateItemQty(item.id, 0)"
                                        class="ml-auto h-8 w-8 flex items-center justify-center text-red-500 hover:bg-red-500/10 rounded-full transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </div>

    {{-- Footer Billing --}}
    <div class="p-4 border-t" :class="isDark ? 'bg-slate-900/50 border-white/5' : 'bg-white border-gray-100 shadow-[0_-4px_20px_rgba(0,0,0,0.05)]'">
        <div class="space-y-2 mb-4">
            <div class="flex justify-between text-xs font-medium opacity-70">
                <span>Subtotal</span>
                <span x-text="'Rp ' + Number(selectedOrder?.subtotal ?? 0).toLocaleString('id-ID')"></span>
            </div>
            <div class="flex justify-between text-xs font-medium opacity-70">
                <span>Tax (PPN)</span>
                <span x-text="'Rp ' + Number(selectedOrder?.tax ?? 0).toLocaleString('id-ID')"></span>
            </div>
            <template x-if="Number(selectedOrder?.discount) > 0">
                <div class="flex justify-between text-xs font-bold text-red-500">
                    <span>Discount</span>
                    <span x-text="'-Rp ' + Number(selectedOrder?.discount ?? 0).toLocaleString('id-ID')"></span>
                </div>
            </template>
            <div class="flex justify-between items-end pt-2 border-t border-dashed border-gray-500/30">
                <span class="text-xs font-black uppercase opacity-60">Total Bill</span>
                <span class="text-lg font-black" :class="isDark ? 'text-amber-500' : 'text-orange-600'"
                    x-text="'Rp ' + Number(selectedOrder?.total ?? 0).toLocaleString('id-ID')"></span>
            </div>
        </div>

        <div class="grid grid-cols-5 gap-2">
            <template x-if="!selectedOrder?.payment">
                <div class="contents">
                    <button
                        :disabled="!(selectedOrder?.items ?? []).find(i => i.status === 'cart')"
                        @click="orderAll(selectedOrder.id)"
                        class="col-span-3 py-3 flex items-center justify-center rounded-2xl font-bold text-xs uppercase tracking-widest transition-all border-2"
                        :class="!(selectedOrder?.items ?? []).find(i => i.status === 'cart')
                            ? (isDark ? 'bg-slate-800 text-slate-700 border-0' : 'bg-gray-100 text-gray-400 border-0')
                            : (isDark ? 'active:scale-95 border-amber-500/50 text-amber-500 hover:bg-amber-500/10' : 'active:scale-95 border-orange-600 text-orange-600 hover:bg-orange-50')">
                        Confirm Order
                    </button>
                    <template x-if="atLeastPro">
                        <button @click="$store.qrModal.open(selectedOrder, '{{ session('restaurant_name') }}')"
                            class="col-span-1 flex items-center justify-center rounded-2xl transition-colors"
                            :class="isDark ? 'bg-slate-800 text-amber-500 hover:bg-slate-700' : 'bg-slate-900 text-white hover:bg-gray-200'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="5" height="5" x="3" y="3" rx="1"/><rect width="5" height="5" x="16" y="3" rx="1"/><rect width="5" height="5" x="3" y="16" rx="1"/><path d="M21 16h-3a2 2 0 0 0-2 2v3"/><path d="M21 21v.01"/><path d="M12 7v3a2 2 0 0 1-2 2H7"/><path d="M3 12h.01"/><path d="M12 3h.01"/><path d="M12 16v.01"/><path d="M16 12h1"/><path d="M21 12v.01"/><path d="M12 21v-1"/></svg>
                        </button>
                    </template>
                    <button @click="showPayment = true"
                        class="flex items-center justify-center gap-2 py-3 rounded-2xl font-black text-xs uppercase tracking-widest transition-all active:scale-95 shadow-lg"
                        :class="[atLeastPro ? 'col-span-1' : 'col-span-2', isDark ? 'bg-amber-500 text-slate-950 hover:bg-amber-400 shadow-amber-500/10' : 'bg-orange-600 text-white hover:bg-orange-700 shadow-orange-500/20']">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/></svg>
                    </button>
                </div>
            </template>
            <template x-if="selectedOrder?.payment">
                <button :disabled="!atLeastPro"
                    @click="handleEndSession(selectedOrder.session?.session_token)"
                    class="col-span-5 flex-col flex items-center justify-center py-2 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 font-black text-xs tracking-widest">
                    <span class="w-full justify-center flex flex-row gap-2 uppercase">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
                        Order Paid
                    </span>
                    <template x-if="atLeastPro">
                        <div class="text-[10px] mt-0.5" :class="isDark ? 'text-gray-400' : 'text-gray-500'">Tutup Session</div>
                    </template>
                </button>
            </template>
        </div>
    </div>

    {{-- Payment Panel --}}
    <template x-if="showPayment">
        @include('components.payment-panel')
    </template>

    {{-- Notes Modal --}}
    <template x-if="showNotesModal && atLeastPro">
        <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-md flex items-center justify-center z-[200] p-4">
            <div class="w-full max-w-sm rounded-3xl border p-6 space-y-4"
                :class="isDark ? 'bg-slate-900 border-white/10 text-white' : 'bg-white border-gray-200 text-slate-900'">
                <h3 class="font-black text-sm uppercase tracking-widest">Catatan Item</h3>
                <textarea x-model="noteText" rows="3" placeholder="Tambahkan catatan..."
                    class="w-full p-4 rounded-2xl border-2 outline-none transition-all text-sm"
                    :class="isDark ? 'bg-black/20 border-white/5 focus:border-amber-500 text-white' : 'bg-gray-50 border-gray-100 focus:border-orange-600'"></textarea>
                <div class="flex gap-3">
                    <button @click="showNotesModal = false"
                        class="flex-1 py-3 rounded-2xl font-black uppercase text-xs border-2"
                        :class="isDark ? 'border-white/10 text-white' : 'border-gray-200 text-gray-500'">Batal</button>
                    <button @click="saveNote()"
                        class="flex-1 py-3 rounded-2xl font-black uppercase text-xs"
                        :class="isDark ? 'bg-amber-500 text-slate-950' : 'bg-orange-600 text-white'">Simpan</button>
                </div>
            </div>
        </div>
    </template>

</aside>


