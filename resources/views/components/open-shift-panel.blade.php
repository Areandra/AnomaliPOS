{{-- Open Shift Panel --}}
<div class="fixed inset-0 bg-slate-950/80 backdrop-blur-md flex justify-center items-center z-[100] p-4 lg:p-6 overflow-hidden select-none"
    x-data="openShiftPanel()">

    <div class="flex flex-col lg:flex-row w-full max-w-5xl h-full max-h-[800px] gap-4 transition-all duration-500">

        {{-- PANEL KIRI: Info & Preview --}}
        <div class="flex-[1.7] flex flex-col rounded-[2.5rem] border overflow-hidden shadow-2xl"
            :class="isDark ? 'bg-slate-950 border-white/10 text-white' : 'bg-white border-gray-200 text-slate-900'">

            <div class="px-8 py-6 border-b border-white/5 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-2xl" :class="isDark ? 'bg-amber-500 text-slate-950' : 'bg-orange-600 text-white'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </div>
                    <div>
                        <h2 class="text-xs font-black uppercase tracking-widest leading-none">Open Session</h2>
                        <p class="text-[9px] font-bold opacity-30 uppercase mt-1 tracking-tighter">Inisialisasi Kas Awal</p>
                    </div>
                </div>
                <form method="POST" action="/sign-out">
                    @csrf
                    <button type="submit"
                        class="px-6 py-4 rounded-[1.5rem] flex items-center gap-3 font-black text-xs uppercase tracking-widest transition-all"
                        :class="isDark ? 'bg-rose-500/10 text-rose-500 border border-rose-500/20 hover:bg-rose-500 hover:text-white' : 'bg-rose-50 text-rose-600 border border-rose-100 hover:bg-rose-600 hover:text-white'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                        Sign Out
                    </button>
                </form>
            </div>

            <div class="flex-1 p-8 lg:p-12 flex flex-col justify-center space-y-10 min-h-0 overflow-y-auto scrollbar-hide">
                <div class="text-center space-y-4">
                    <div class="p-10 rounded-[3rem] border-2 border-dashed transition-all"
                        :class="isDark ? 'bg-white/5 border-white/10' : 'bg-gray-50 border-gray-200'">
                        <span class="text-[10px] font-black opacity-40 uppercase tracking-[0.2em]">Modal Terhitung di Laci</span>
                        <div class="flex items-baseline justify-center gap-2 mt-4">
                            <span class="text-xl font-black opacity-20 italic">Rp</span>
                            <h4 class="text-6xl lg:text-7xl font-black tracking-tighter"
                                :class="isDark ? 'text-amber-500' : 'text-orange-600'"
                                x-text="Number(startingCash || 0).toLocaleString('id-ID')"></h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-8 bg-black/20 border-t border-white/5">
                <button @click="handleOpenShift()" :disabled="loading"
                    class="w-full py-5 rounded-2xl font-black uppercase text-xs tracking-[0.15em] flex items-center justify-center gap-3 transition-all active:scale-95 shadow-xl"
                    :class="isDark ? 'bg-amber-500 text-slate-950' : 'bg-orange-600 text-white'">
                    <span x-text="loading ? 'Membuka Sesi...' : 'Konfirmasi & Buka Shift'"></span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                </button>
            </div>
        </div>

        {{-- PANEL KANAN: Numpad --}}
        <div class="flex-1 flex flex-col gap-4">
            <div class="p-8 rounded-[2.5rem] border-2 border-dashed flex items-start gap-5"
                :class="isDark ? 'bg-slate-950 border-white/10 text-white' : 'bg-white border-gray-200 text-slate-900'">
                <div class="p-3 rounded-xl shrink-0" :class="isDark ? 'bg-black/20 text-amber-500' : 'bg-white text-orange-600 shadow-sm'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><path d="M12 18V6"/></svg>
                </div>
                <div class="space-y-1">
                    <h5 class="text-[10px] font-black uppercase tracking-widest opacity-80">Prosedur Keamanan</h5>
                    <p class="text-[11px] leading-relaxed font-bold opacity-50">
                        Harap hitung fisik uang tunai di laci kasir dengan teliti sebelum memulai transaksi. Angka ini akan menjadi saldo awal sistem.
                    </p>
                </div>
            </div>

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
    </div>
</div>


