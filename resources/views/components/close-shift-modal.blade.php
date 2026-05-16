@props([
    'isDark' => true
])

{{-- Menggunakan x-show agar sinkron dengan state openCloseModal dan mendukung animasi transition --}}
<div class="fixed inset-0 bg-slate-950/80 backdrop-blur-md flex justify-center items-center z-[100] p-4 lg:p-6 overflow-hidden select-none"
    x-show="openCloseModal"
    x-transition.opacity.duration.300ms
    x-data="closeShiftModal({ isDark: {{ json_encode($isDark) }} })">

    <div class="flex flex-col lg:flex-row w-full h-full max-h-[850px] gap-4 transition-all duration-500 ease-in-out"
        :class="step === 2 ? 'max-w-md' : 'max-w-5xl'">

        {{-- PANEL KIRI: Form Pecahan, Review, & Summary Variance --}}
        <div class="flex-[1.5] flex flex-col rounded-[2.5rem] border overflow-hidden shadow-2xl"
            :class="isDark ? 'bg-slate-950 border-white/10 text-white' : 'bg-white border-gray-200 text-slate-900'">

            {{-- Header --}}
            <div class="px-8 py-6 border-b border-white/5 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" :class="isDark ? 'text-amber-500' : 'text-orange-600'"><path d="M3 3v5h5"/><path d="M3.05 13A9 9 0 1 0 6 5.3L3 8"/><path d="M12 7v5l4 2"/></svg>
                    <h2 class="text-xs font-black uppercase tracking-widest" x-text="step === 3 ? 'Shift Selesai' : 'Penghitungan Kas'"></h2>
                </div>
                <button @click="openCloseModal = false" class="p-2 opacity-40 hover:opacity-100 hover:text-red-500 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Body Content Berdasarkan Step --}}
            <div class="flex-1 overflow-y-auto p-4 space-y-3 min-h-0 scrollbar-hide">

                {{-- STEP 1: Daftar Pecahan Rupiah --}}
                <template x-if="step === 1">
                    <div class="space-y-3">
                        <template x-for="item in currencies" :key="item.value">
                            <button @click="activeValue = item.value"
                                class="w-full flex items-center p-3 rounded-[1.5rem] border-2 transition-all"
                                :class="activeValue === item.value
                                    ? (isDark ? 'bg-white/10 border-amber-500 shadow-lg scale-[1.02]' : 'bg-gray-50 border-orange-600 shadow-md scale-[1.02]')
                                    : 'border-transparent opacity-60'">

                                {{-- Visual Koin/Kertas --}}
                                <template x-if="item.isCoin">
                                    <div class="relative w-12 h-12 rounded-full border-4 flex items-center justify-center shadow-inner transition-transform duration-500 border-white/20"
                                        :class="[item.gradient, activeValue === item.value ? 'scale-110 rotate-[10deg]' : 'scale-90 opacity-80']">
                                        <div class="absolute inset-1 rounded-full border border-white/10"></div>
                                        <span class="text-[10px] font-black text-white/80 drop-shadow-md" x-text="item.label.split('.')[0]"></span>
                                    </div>
                                </template>
                                <template x-if="!item.isCoin">
                                    <div class="relative w-24 h-12 rounded-sm overflow-hidden shadow-lg transition-all duration-500 bg-gradient-to-br"
                                        :class="[item.gradient, activeValue === item.value ? 'scale-105 -rotate-1 ring-2 ring-white/50' : 'opacity-80 scale-95']">
                                        <div class="absolute right-2 top-1 text-[10px] font-black text-white/40 italic">IDR</div>
                                        <div class="absolute right-2 bottom-1 text-xs font-black text-white drop-shadow-md uppercase tracking-tighter" x-text="item.label"></div>
                                    </div>
                                </template>

                                <div class="ml-5 flex-1 text-left">
                                    <p class="text-lg font-black tracking-tighter leading-none" x-text="'Rp ' + item.label"></p>
                                    <p class="text-[8px] font-bold opacity-30 uppercase mt-1 tracking-widest" x-text="item.isCoin ? 'Koin Logam' : 'Uang Kertas'"></p>
                                </div>
                                <div class="text-2xl font-black px-4"
                                    :class="activeValue === item.value ? (isDark ? 'text-amber-500' : 'text-orange-600') : 'opacity-20'"
                                    x-text="counts[item.value] || 0"></div>
                            </button>
                        </template>
                    </div>
                </template>

                {{-- STEP 2: Review Kas & Catatan Selisih --}}
                <template x-if="step === 2">
                    <div class="p-4 space-y-6">
                        <div class="p-8 rounded-[2rem] border-2 border-dashed text-center"
                            :class="isDark ? 'bg-white/5 border-white/5' : 'bg-gray-50 border-gray-200'">
                            <span class="text-[10px] font-black opacity-40 uppercase tracking-widest block mb-2">Total Kas Fisik</span>
                            <div class="text-4xl font-black tracking-tighter"
                                :class="isDark ? 'text-amber-500' : 'text-orange-600'"
                                x-text="formatRp(totalPhysical)"></div>
                        </div>
                        <textarea x-model="notes" placeholder="Masukkan keterangan selisih jika ada..."
                            class="w-full p-6 rounded-[2rem] border-2 outline-none h-44 transition-all"
                            :class="isDark ? 'bg-black/20 border-white/5 focus:border-amber-500 text-white' : 'bg-gray-50 border-gray-100 focus:border-orange-600'"></textarea>
                    </div>
                </template>

                {{-- STEP 3: Ringkasan Hasil (Variance) --}}
                <template x-if="step === 3 && summary">
                    <div class="p-4 space-y-4">
                        <div class="w-20 h-20 mx-auto rounded-[2rem] flex items-center justify-center mb-4 text-white shadow-2xl"
                            :class="summary.selisih < 0 ? 'bg-red-500 shadow-red-500/20' : 'bg-emerald-500 shadow-emerald-500/20'">
                            <template x-if="summary.selisih < 0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                            </template>
                            <template x-if="summary.selisih >= 0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
                            </template>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center p-4 rounded-2xl border"
                                :class="isDark ? 'bg-white/5 border-white/10' : 'bg-white border-gray-100 shadow-sm'">
                                <span class="text-[10px] font-black uppercase opacity-40 tracking-widest">Tercatat Sistem</span>
                                <span class="font-bold text-sm tracking-tight" x-text="formatRp(summary.seharusnya_di_laci)"></span>
                            </div>
                            <div class="flex justify-between items-center p-4 rounded-2xl border"
                                :class="isDark ? 'bg-white/5 border-white/10' : 'bg-white border-gray-100 shadow-sm'">
                                <span class="text-[10px] font-black uppercase opacity-40 tracking-widest">Fisik Dilaporkan</span>
                                <span class="font-bold text-sm tracking-tight" x-text="formatRp(summary.fisik_di_laci)"></span>
                            </div>
                        </div>
                        <div class="p-6 rounded-[2rem] flex justify-between items-center text-white"
                            :class="summary.selisih < 0 ? 'bg-red-500' : 'bg-emerald-600'">
                            <span class="text-[10px] font-black uppercase tracking-widest text-white/80">Variance</span>
                            <span class="text-2xl font-black tracking-tighter" x-text="formatRp(summary.selisih)"></span>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Footer Action Buttons --}}
            <div class="p-6 bg-black/10 border-t border-white/5">
                <div class="flex gap-3">
                    <template x-if="step === 2">
                        <button @click="step = 1"
                            class="flex-1 py-5 rounded-2xl font-black uppercase text-[10px] border-2"
                            :class="isDark ? 'border-white/10 text-white' : 'border-gray-200 text-gray-500'">Kembali</button>
                    </template>
                    <button
                        @click="step === 1 ? step = 2 : step === 2 ? handleCloseShift() : (openCloseModal = false)"
                        :disabled="loading"
                        class="flex-[2] py-5 rounded-2xl font-black uppercase text-xs tracking-widest flex items-center justify-center gap-3 transition-all active:scale-95"
                        :class="isDark ? 'bg-amber-500 text-slate-950' : 'bg-orange-600 text-white'">
                        <span x-text="step === 1 ? 'Review Laporan' : step === 2 ? (loading ? 'Processing...' : 'Selesaikan Shift') : 'Keluar Sesi'"></span>
                        <template x-if="step < 3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                        </template>
                        <template x-if="step === 3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                        </template>
                    </button>
                </div>
            </div>
        </div>

        {{-- PANEL KANAN: Kalkulator Numpad (step 1) atau Identitas Kasir Bertugas (step 3) --}}
        <template x-if="step === 1 || step === 3">
            <div class="flex-1 flex flex-col gap-4">

                {{-- Numpad (Muncul hanya pada Step 1) --}}
                <template x-if="step === 1">
                    <div class="contents">
                        {{-- Viewer Jumlah Lembar/Koin --}}
                        <div class="h-40 rounded-[2.5rem] border-2 flex items-center px-8 gap-8"
                            :class="isDark ? 'bg-slate-900 border-white/5' : 'bg-gray-50 border-gray-100'">
                            <div class="flex-1 text-right">
                                <p class="text-[9px] font-black opacity-30 uppercase tracking-[0.2em] mb-1">Jumlah Input</p>
                                <p class="text-7xl font-black tracking-tighter"
                                    :class="isDark ? 'text-amber-500' : 'text-orange-600'"
                                    x-text="counts[activeValue] || '0'"></p>
                            </div>
                        </div>

                        {{-- Layout Grid Tombol --}}
                        <div class="flex-1 grid grid-cols-3 gap-3 p-6 rounded-[2.5rem] border"
                            :class="isDark ? 'bg-slate-900/50 border-white/5' : 'bg-white border-gray-100 shadow-xl'">
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

                {{-- Ringkasan Sesi (Muncul hanya pada Step 3) --}}
                <template x-if="step === 3">
                    <div class="flex-1 rounded-[2.5rem] border p-8 flex flex-col items-center justify-center text-center space-y-8"
                        :class="isDark ? 'bg-slate-900/50 border-white/5' : 'bg-gray-50 border-gray-200'">
                        <div class="w-24 h-24 rounded-full flex items-center justify-center shadow-xl"
                            :class="isDark ? 'bg-white/5 text-amber-500' : 'bg-white text-orange-600'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                        <div class="space-y-2">
                            <h4 class="text-xs font-black uppercase tracking-[0.3em] opacity-40">Kasir Bertugas</h4>
                            <p class="text-3xl font-black tracking-tight" x-text="closedUser?.name ?? 'Administrator'"></p>
                            <p class="text-xs opacity-50 font-bold uppercase tracking-widest" x-text="closedUser?.role ?? 'Shift Leader'"></p>
                        </div>
                        <div class="w-full p-6 rounded-3xl border-2 border-dashed space-y-4"
                            :class="isDark ? 'border-white/5 bg-black/20' : 'border-gray-200 bg-white'">
                            <div class="flex justify-between items-center text-left">
                                <div class="flex items-center gap-3 opacity-60">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    <span class="text-[10px] font-black uppercase">Waktu Tutup</span>
                                </div>
                                <span class="text-xs font-bold" x-text="new Date().toLocaleTimeString('id-ID')"></span>
                            </div>
                            <div class="flex justify-between items-center text-left">
                                <div class="flex items-center gap-3 opacity-60">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
                                    <span class="text-[10px] font-black uppercase">Status Shift</span>
                                </div>
                                <span class="text-[10px] font-black px-3 py-1 bg-emerald-500/20 text-emerald-500 rounded-full uppercase">Closed</span>
                            </div>
                        </div>
                        <p class="text-[10px] opacity-30 italic font-medium">Data penutupan telah sinkron dengan server pusat.</p>
                    </div>
                </template>
            </div>
        </template>
    </div>
</div>
