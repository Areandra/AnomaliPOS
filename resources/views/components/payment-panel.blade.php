{{-- Payment Panel --}}
<div class="fixed inset-0 bg-slate-950/75 flex justify-center items-start lg:items-center z-[200] p-4 lg:p-6 overflow-y-auto"
    x-data="paymentPanel()">

    <div class="flex flex-col lg:flex-row w-full max-w-6xl min-h-0 lg:h-full lg:max-h-[900px] gap-4 my-auto">

        {{-- KOLOM KIRI: BILL & METHOD --}}
        <div class="flex-[1.5] p-1 flex flex-col rounded-[2.5rem] border overflow-hidden shadow-2xl min-h-0"
            :class="isDark ? 'bg-slate-950 border-white/10 text-white' : 'bg-[#FDFBF7] border-gray-200 text-gray-900'">

            {{-- Header --}}
            <div class="p-6 border-b flex justify-between items-center"
                :class="isDark ? 'border-white/5' : 'border-gray-100'">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-xl" :class="isDark ? 'bg-amber-500 text-slate-950' : 'bg-orange-600 text-white'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/><path d="M14 8H8"/><path d="M16 12H8"/><path d="M13 16H8"/></svg>
                    </div>
                    <h2 class="text-xs font-black uppercase tracking-widest">Billing Checkout</h2>
                </div>
                <button @click="showPayment = false"
                    class="p-2 rounded-full transition-colors"
                    :class="isDark ? 'hover:bg-white/5 text-gray-500' : 'hover:bg-gray-100 text-gray-400'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Content --}}
            <div class="flex-1 overflow-y-auto p-6 space-y-6 min-h-0 scrollbar-hide">

                {{-- Summary Card --}}
                <div class="p-6 rounded-[1.5rem] border-2 border-dashed transition-all"
                    :class="isDark ? 'bg-white/5 border-white/5' : 'bg-white border-gray-200'">
                    <div class="space-y-2">
                        <div class="flex justify-between text-[10px] font-bold opacity-40 uppercase tracking-widest">
                            <span>Subtotal + Tax</span>
                            <span x-text="formatRp(selectedOrder?.subtotal ?? 0) + ' + ' + formatRp((selectedOrder?.subtotal ?? 0) * 0.1)"></span>
                        </div>
                        <div class="flex justify-between items-end">
                            <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded-full"
                                :class="isDark ? 'bg-amber-500/20 text-amber-500' : 'bg-orange-100 text-orange-600'">
                                Total Payable
                            </span>
                            <span class="text-4xl font-black tracking-tighter leading-none"
                                x-text="formatRp(selectedOrder?.total ?? 0)"></span>
                        </div>
                    </div>
                </div>

                {{-- Payment Method --}}
                <template x-if="atLeastPro">
                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest mb-3 opacity-40 ml-2">
                            Metode Pembayaran
                        </label>
                        <div class="grid grid-cols-4 gap-2">
                            <template x-for="method in paymentMethods" :key="method.type">
                                <button @click="selectMethod(method.type)"
                                    class="flex flex-col items-center justify-center py-3 rounded-2xl border-2 transition-all duration-300"
                                    :class="paymentMethod === method.type
                                        ? (isDark ? 'bg-amber-500 border-amber-500 text-slate-950 shadow-lg shadow-amber-500/20' : 'bg-orange-600 border-orange-600 text-white shadow-lg shadow-orange-600/20')
                                        : (isDark ? 'bg-slate-900 border-white/5 text-gray-500 hover:border-white/10' : 'bg-white border-gray-100 text-gray-400 hover:border-gray-200')">
                                    <span x-html="method.icon"></span>
                                    <span class="text-[8px] font-black uppercase mt-1 tracking-widest" x-text="method.label"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </template>

                {{-- Quick Cash --}}
                <template x-if="paymentMethod === 'cash'">
                    <div class="space-y-3">
                        <p class="text-[9px] font-black opacity-30 uppercase tracking-widest text-center">Nominal Cepat</p>
                        <div class="grid grid-cols-2 gap-2">
                            <template x-for="val in quickCashOptions" :key="val">
                                <button @click="amountPaid = val.toString()"
                                    class="py-2.5 rounded-xl border-2 font-black text-[10px] transition-all"
                                    :class="isDark ? 'bg-white/5 border-white/5 text-white hover:bg-white/10' : 'bg-gray-50 border-gray-100 text-slate-800 hover:bg-gray-100'"
                                    x-text="formatRp(val)">
                                </button>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Footer --}}
            <div class="p-6 border-t border-white/5">
                <button @click="handlePay()"
                    :disabled="loading || (paymentMethod === 'cash' && Number(amountPaid) < (selectedOrder?.total ?? 0))"
                    class="w-full py-5 rounded-[1.5rem] flex items-center justify-center gap-3 transition-all active:scale-95 disabled:opacity-20 shadow-xl"
                    :class="isDark ? 'bg-white text-slate-950 hover:bg-amber-500' : 'bg-slate-900 text-white hover:bg-slate-800'">
                    <span class="text-xs font-black uppercase tracking-widest"
                        x-text="loading ? 'Proses...' : 'Proses Pembayaran'"></span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                </button>
            </div>
        </div>

        {{-- KOLOM KANAN: NUMPAD (cash) atau DIGITAL PAYMENT --}}
        <template x-if="paymentMethod === 'cash'">
            <div class="flex-1 rounded flex flex-col gap-4">

                {{-- Amount Display --}}
                <div class="p-6 space-y-4 rounded-[2.5rem] border-2 transition-all"
                    :class="isDark ? 'bg-slate-900 border-white/5' : 'bg-white border-gray-100 shadow-inner'">
                    <div class="flex justify-between items-center">
                        <span class="text-xl font-black opacity-20 italic">IDR</span>
                        <span class="text-4xl font-black tracking-tighter"
                            :class="isDark ? 'text-amber-500' : 'text-orange-600'"
                            x-text="Number(amountPaid).toLocaleString('id-ID')"></span>
                    </div>
                    <div class="p-2 px-4 rounded-[1.2rem] flex justify-between items-center border-2 border-dashed"
                        :class="changeAmount > 0
                            ? (isDark ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-500' : 'bg-emerald-50 border-emerald-200 text-emerald-600')
                            : 'opacity-30'">
                        <span class="text-[9px] font-black uppercase tracking-widest">Kembalian</span>
                        <span class="text-2xl font-black tracking-tighter" x-text="formatRp(changeAmount)"></span>
                    </div>
                </div>

                {{-- Numpad --}}
                <div class="flex-1 grid grid-cols-3 gap-3 p-6 rounded-[2.5rem] border"
                    :class="isDark ? 'bg-slate-900/50 border-white/5' : 'bg-white border-gray-200 shadow-xl'">
                    <template x-for="btn in [1,2,3,4,5,6,7,8,9,'C',0,'DEL']" :key="btn">
                        <button
                            @click="btn === 'C' ? handleClear() : btn === 'DEL' ? handleBackspace() : handleNumpad(btn.toString())"
                            class="rounded-[1.5rem] text-2xl font-black transition-all active:scale-90"
                            :class="typeof btn === 'number'
                                ? (isDark ? 'bg-white/5 hover:bg-white/10 text-white' : 'bg-gray-100 hover:bg-gray-200 text-slate-800')
                                : (isDark ? 'bg-amber-500 text-slate-950 shadow-lg' : 'bg-orange-600 text-white shadow-md')">
                            <template x-if="btn === 'DEL'">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto"><path d="M20 5H9l-7 7 7 7h11a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2Z"/><line x1="18" x2="12" y1="9" y2="15"/><line x1="12" x2="18" y1="9" y2="15"/></svg>
                            </template>
                            <template x-if="btn !== 'DEL'">
                                <span x-text="btn"></span>
                            </template>
                        </button>
                    </template>
                </div>
            </div>
        </template>

        <template x-if="paymentMethod !== 'cash'">
            <div class="flex-1 space-y-3 rounded-[2rem] border flex flex-col shadow-2xl transition-all min-h-0"
                :class="isDark ? 'bg-slate-900/40 border-white/10' : 'bg-white border-gray-200'">
                <div class="flex-1 flex flex-col items-center justify-center text-center p-4 space-y-4">
                    <div class="w-24 h-24 rounded-[2.5rem] flex items-center justify-center border-4 border-dashed animate-pulse"
                        :class="isDark ? 'bg-amber-500/10 border-amber-500/20 text-amber-500' : 'bg-orange-50 border-orange-100 text-orange-600'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="5" height="5" x="3" y="3" rx="1"/><rect width="5" height="5" x="16" y="3" rx="1"/><rect width="5" height="5" x="3" y="16" rx="1"/><path d="M21 16h-3a2 2 0 0 0-2 2v3"/><path d="M21 21v.01"/><path d="M12 7v3a2 2 0 0 1-2 2H7"/><path d="M3 12h.01"/><path d="M12 3h.01"/><path d="M12 16v.01"/><path d="M16 12h1"/><path d="M21 12v.01"/><path d="M12 21v-1"/></svg>
                    </div>
                    <h4 class="text-md font-black uppercase tracking-widest">Digital Payment</h4>
                    <p class="text-[10px] font-bold opacity-40 leading-relaxed max-w-[200px]">
                        Scan QR atau transfer sesuai nominal tagihan.
                    </p>
                </div>
            </div>
        </template>

    </div>
</div>


