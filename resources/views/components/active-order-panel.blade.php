{{-- Active Order Panel --}}
<div class="flex h-full overflow-hidden">

    {{-- MAIN CONTENT: Menu --}}
    <main class="flex-1 overflow-hidden scrollbar-hide">

        {{-- Search Header --}}
        <div class="sticky top-0 z-10 p-4 pb-2 backdrop-blur-md"
            :class="isDark ? 'bg-slate-950/40' : 'bg-[#FDFBF7]/40'">
            <div class="flex w-full items-center gap-3">
                <div class="flex items-center gap-3 flex-1 p-3 px-5 border rounded-full transition-all shadow-sm"
                    :class="isDark ? 'bg-slate-900 border-white/10 focus-within:border-amber-500/50' : 'bg-white border-gray-200 focus-within:border-orange-500'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" :class="isDark ? 'text-slate-500' : 'text-gray-400'"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <input type="text" placeholder="Search menu items..."
                        class="bg-transparent border-none outline-none w-full text-sm font-medium"
                        x-model="search">
                    <button @click="window.dispatchEvent(new Event('toggle-theme'))" class="hover:scale-110 transition-transform active:rotate-90">
                        <template x-if="isDark">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-400"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/></svg>
                        </template>
                        <template x-if="!isDark">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-600"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
                        </template>
                    </button>
                </div>

                <button @click="newOrder()"
                    class="flex items-center p-3 gap-2 rounded-full text-[11px] font-black uppercase bg-orange-600 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 12h8M12 8v8"/></svg>
                    <span class="hidden md:inline">New Order</span>
                </button>
                <button @click="openCloseModal = true"
                    class="flex items-center p-3 gap-2 rounded-full text-[11px] font-black uppercase bg-amber-500 text-slate-900">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2"/><path d="M9 12h14"/><path d="m15 8 4 4-4 4"/></svg>
                    <span class="hidden md:inline">Close Shift</span>
                </button>
            </div>
        </div>

        {{-- Category Tabs --}}
        <div class="sticky top-[72px] z-10 px-4 py-2 overflow-x-auto scrollbar-hide">
            <div class="flex gap-2 w-max">
                <button @click="categorySelect = -1"
                    class="px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest transition-all"
                    :class="categorySelect === -1
                        ? (isDark ? 'bg-amber-500 text-slate-900' : 'bg-orange-600 text-white')
                        : (isDark ? 'bg-slate-800 text-gray-400 hover:bg-slate-700' : 'bg-white text-gray-500 border border-gray-200')">
                    All
                </button>
                <template x-for="cat in categories" :key="cat.id">
                    <button @click="categorySelect = cat.id"
                        class="px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest transition-all"
                        :class="categorySelect === cat.id
                            ? (isDark ? 'bg-amber-500 text-slate-900' : 'bg-orange-600 text-white')
                            : (isDark ? 'bg-slate-800 text-gray-400 hover:bg-slate-700' : 'bg-white text-gray-500 border border-gray-200')"
                        x-text="cat.name">
                    </button>
                </template>
            </div>
        </div>

        {{-- Menu Grid --}}
        <div class="flex-1 h-full overflow-y-auto px-4 pb-36 scrollbar-hide">
            <template x-for="cat in filteredMenu" :key="cat.id">
                <div class="mb-8">
                    <h2 class="text-sm uppercase font-black mb-4 flex items-center gap-2"
                        :class="isDark ? 'text-amber-500' : 'text-orange-600'">
                        <span class="h-1 w-4 rounded-full" :class="isDark ? 'bg-amber-500' : 'bg-orange-600'"></span>
                        <span x-text="cat.name"></span>
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                        <template x-for="item in cat.items" :key="item.id">
                            <div
                                @click="!((!selectedOrderId || selectedOrder?.payment)) && handleAddOrUpdate(item)"
                                class="group relative rounded-3xl p-4 border transition-all duration-300 cursor-pointer"
                                :class="isDark ? 'bg-slate-900 border-white/5 hover:border-amber-500/30' : 'bg-white border-gray-100 hover:border-orange-200'">

                                <div class="flex gap-4">
                                    <template x-if="atLeastPro">
                                        <div class="relative w-24 h-24 shrink-0">
                                            <img loading="lazy"
                                                :src="item.image_url ?? '/images/placeholder-food.jpg'"
                                                class="w-full h-full object-cover rounded-2xl"
                                                :alt="item.name">
                                            <template x-if="(cartInfoMap[item.id]?.totalQty ?? 0) > 0">
                                                <div class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] font-bold h-6 w-6 rounded-full flex items-center justify-center border-2 border-white animate-bounce"
                                                    x-text="cartInfoMap[item.id]?.totalQty ?? 0">
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                    <div class="flex flex-col justify-between py-1">
                                        <div>
                                            <h3 class="text-sm font-bold line-clamp-1"
                                                :class="isDark ? 'text-white' : 'text-slate-800'"
                                                x-text="item.name"></h3>
                                            <p class="text-[10px] text-gray-500 line-clamp-2" x-text="item.description"></p>
                                        </div>
                                        <p class="text-sm font-black" x-text="'Rp ' + Number(item.price).toLocaleString('id-ID')"></p>
                                    </div>
                                </div>

                                <div class="mt-4 pt-4 border-t flex items-center justify-between border-gray-50/10">
                                    <span class="text-[10px] font-bold uppercase opacity-40">Qty</span>
                                    <div class="flex items-center gap-1 p-1 rounded-full bg-black/10">
                                        <button
                                            :disabled="!selectedOrderId || !!selectedOrder?.payment || !(cartInfoMap[item.id]?.cartItem)"
                                            @click.stop="cartInfoMap[item.id]?.cartItem && updateItemQty(cartInfoMap[item.id].cartItem.id, cartInfoMap[item.id].cartItem.quantity - 1)"
                                            class="p-1.5 rounded-full hover:bg-red-500 hover:text-white transition-colors disabled:opacity-20">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
                                        </button>
                                        <span class="w-8 text-center text-sm font-black" x-text="cartInfoMap[item.id]?.totalQty ?? 0"></span>
                                        <button
                                            :disabled="!selectedOrderId || !!selectedOrder?.payment"
                                            @click.stop="handleAddOrUpdate(item)"
                                            class="p-1.5 rounded-full bg-orange-600 text-white disabled:bg-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5v14"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </main>

    {{-- SIDEBAR: Order Detail or Active Orders --}}
    <template x-if="selectedOrderId && selectedOrder">
        @include('components.order-detail-panel')
    </template>

    <template x-if="!(selectedOrderId && selectedOrder)">
        <aside class="w-60 border-l transition-all duration-500 flex flex-col shrink-0"
            :class="isDark ? 'bg-slate-900/50 border-white/10 backdrop-blur-xl' : 'bg-white border-gray-200 shadow-xl'">

            <div class="p-6 border-b" :class="isDark ? 'border-white/5' : 'border-gray-100'">
                <div class="flex items-center gap-2 mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" :class="isDark ? 'text-amber-500' : 'text-orange-600'"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
                    <h2 class="font-black text-sm uppercase tracking-wider" :class="isDark ? 'text-gray-100' : 'text-gray-800'">Active Orders</h2>
                </div>
                <p class="text-[10px] text-gray-500 font-medium">Monitoring meja yang sedang aktif</p>
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-4 scrollbar-hide">
                <template x-if="orders.length === 0">
                    <div class="flex flex-col items-center justify-center h-40 opacity-40">
                        <p class="text-xs font-bold italic">No active orders</p>
                    </div>
                </template>

                <template x-for="order in [...orders].reverse()" :key="order.id">
                    <div @click="selectOrder(order.id)"
                        class="group relative p-4 rounded-3xl border transition-all duration-300 cursor-pointer"
                        :class="isDark ? 'bg-slate-800/40 border-white/5 hover:bg-slate-800 hover:border-amber-500/50' : 'bg-gray-50 border-gray-100 hover:bg-white hover:shadow-lg hover:border-orange-200'">

                        <template x-if="atLeastPro">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black uppercase tracking-widest" :class="isDark ? 'text-amber-500' : 'text-orange-600'">Table</span>
                                    <p class="text-xl font-black" :class="isDark ? 'text-white' : 'text-slate-800'" x-text="order.table?.table_number ?? '??'"></p>
                                </div>
                                <div>
                                    <div class="flex flex-col items-end">
                                        <span class="flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter"
                                            :class="isDark ? 'bg-slate-950 text-amber-400 border border-white/10' : 'bg-orange-100 text-orange-700'">
                                            <span class="relative flex h-2 w-2">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-orange-500"></span>
                                            </span>
                                            <span x-text="order.status"></span>
                                        </span>
                                    </div>
                                    <div class="flex flex-col items-end">
                                        <span class="flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter"
                                            :class="[order.payment ? 'text-green-400' : 'text-red-400', isDark ? 'bg-slate-950 border border-white/10' : 'bg-orange-100']">
                                            <span class="relative flex h-2 w-2">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-2 w-2" :class="order.payment ? 'bg-green-500' : 'bg-red-500'"></span>
                                            </span>
                                            <span x-text="order.payment ? 'Bill Paid' : 'Not Paid'"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div class="pt-3 border-t mt-1 flex flex-col gap-1.5" :class="isDark ? 'border-white/5' : 'border-gray-200/60'">
                            <div class="flex items-center gap-2 opacity-60">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                <span class="text-[10px] font-medium" x-text="new Date(order.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })"></span>
                            </div>
                            <div class="flex items-center gap-2 opacity-60">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="9" y2="9"/><line x1="4" x2="20" y1="15" y2="15"/><line x1="10" x2="8" y1="3" y2="21"/><line x1="16" x2="14" y1="3" y2="21"/></svg>
                                <span class="text-[10px] font-mono tracking-tight uppercase" x-text="order.order_code"></span>
                            </div>
                        </div>

                        <div class="absolute right-4 bottom-4 opacity-0 group-hover:opacity-100 transition-opacity"
                            :class="isDark ? 'text-amber-500' : 'text-orange-600'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
                        </div>
                    </div>
                </template>
            </div>

            <div class="p-4 text-center text-[9px] font-bold tracking-widest uppercase opacity-30" :class="isDark ? 'text-white' : 'text-black'">
                AnoPos v0.5.alpha
            </div>
        </aside>
    </template>
</div>
