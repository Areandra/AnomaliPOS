@extends('layouts.cashier-base')

@section('title', 'Select Table')

@section('content')
<div x-data="selectTableApp({{ json_encode($data) }}, {{ json_encode($currentShift) }})"
    class="h-[100dvh] transition-colors duration-500"
    :class="isDark ? 'bg-slate-950 text-gray-100' : 'bg-[#FDFBF7] text-gray-900'">

    {{-- Dark mode bg blobs --}}
    <template x-if="isDark">
        <div class="fixed inset-0 pointer-events-none opacity-20">
            <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-purple-900 rounded-full blur-[120px] mix-blend-screen"></div>
            <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-blue-900 rounded-full blur-[120px] mix-blend-screen"></div>
        </div>
    </template>

    {{-- HEADER --}}
    <header class="fixed top-0 left-0 w-full h-16 z-50 flex items-center justify-between px-6 backdrop-blur-xl border-b duration-300"
        :class="isDark ? 'bg-slate-900/70 border-white/5' : 'bg-white/70 border-gray-200/50'">
        <div class="flex items-center gap-2">
            <div class="-ml-5">
                <button onclick="window.history.back()" class="p-2 rounded-xl duration-200 group"
                    :class="isDark ? 'hover:bg-white/5 text-gray-400 hover:text-white' : 'hover:bg-gray-100 text-gray-600 hover:text-gray-900'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="transition-transform group-hover:-translate-x-1"><path d="m15 18-6-6 6-6"/></svg>
                </button>
            </div>
            <div class="flex flex-col">
                <span class="font-black text-xl tracking-tighter uppercase" :class="isDark ? 'text-white' : 'text-slate-800'">
                    Ano<span :class="isDark ? 'text-amber-500' : 'text-orange-600'">Pos</span>
                </span>
                <span class="text-[9px] font-black uppercase tracking-[0.2em] opacity-50">Point Of Sale &gt; New Order</span>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="/menu" class="flex items-center gap-2 px-4 py-2 rounded-full duration-300 font-black text-[10px] uppercase tracking-widest shadow-sm"
                :class="isDark ? 'bg-slate-800 text-amber-500 hover:bg-slate-700 border border-white/5' : 'bg-white text-orange-600 hover:bg-orange-50 border border-gray-200'">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                <span class="hidden md:block">Manajemen</span>
            </a>
            <a href="/kitchen/kot" class="flex items-center gap-2 px-4 py-2 rounded-full duration-300 font-black text-[10px] uppercase tracking-widest shadow-sm"
                :class="isDark ? 'bg-slate-800 text-amber-500 hover:bg-slate-700 border border-white/5' : 'bg-white text-orange-600 hover:bg-orange-50 border border-gray-200'">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/></svg>
                <span class="hidden md:block">Kitchen</span>
            </a>
            <a href="/cashier" class="flex items-center gap-2 px-4 py-2 rounded-full duration-300 font-black text-[10px] uppercase tracking-widest shadow-sm"
                :class="isDark ? 'bg-slate-800 text-amber-500 hover:bg-slate-700 border border-white/5' : 'bg-white text-orange-600 hover:bg-orange-50 border border-gray-200'">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="16" height="16" x="4" y="4" rx="2"/><rect width="6" height="6" x="9" y="9" rx="1"/><path d="M15 2v2M15 20v2M2 15h2M2 9h2M20 15h2M20 9h2M9 2v2M9 20v2"/></svg>
                <span class="hidden md:block">Cashier</span>
            </a>
        </div>
    </header>

    <div class="h-full overflow-hidden flex flex-col pt-16">
        <div class="h-full flex flex-col relative">

            {{-- Sub Header --}}
            <header class="sticky top-0 z-40 w-full px-4 py-4 backdrop-blur-xl border-b transition-all duration-300"
                :class="isDark ? 'bg-slate-900/70 border-white/5' : 'bg-white/70 border-gray-200/50'">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4 mx-auto">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 rounded-xl"
                            :class="isDark ? 'bg-slate-800 text-amber-500 shadow-lg shadow-black/20' : 'bg-orange-100 text-orange-600 shadow-sm'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.106 5.553a2 2 0 0 0 1.788 0l3.659-1.83A1 1 0 0 1 21 4.619v12.764a1 1 0 0 1-.553.894l-4 2a2 2 0 0 1-1.788 0l-4.212-2.106a2 2 0 0 0-1.788 0l-3.659 1.83A1 1 0 0 1 3 19.381V6.618a1 1 0 0 1 .553-.894l4-2a2 2 0 0 1 1.788 0z"/><path d="M15 5.764v15M9 3.236v15"/></svg>
                        </div>
                        <div>
                            <h1 class="font-bold text-lg leading-none tracking-tight">Select Table</h1>
                            <p class="text-[10px] mt-1 uppercase font-bold tracking-widest"
                                :class="isDark ? 'text-gray-400' : 'text-gray-500'">Pilih Meja Untuk Order</p>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Floor Map --}}
            <div class="flex-1 relative overflow-hidden transition-colors duration-500"
                :class="isDark ? 'bg-slate-950' : 'bg-slate-100'">

                {{-- Status Legend --}}
                <div class="absolute bottom-6 left-8 z-10 px-4 py-2.5 rounded-2xl border backdrop-blur-xl shadow-2xl transition-all"
                    :class="isDark ? 'bg-slate-900/80 border-white/5 text-gray-400' : 'bg-white/80 border-gray-100 text-gray-500'">
                    <div class="flex flex-col gap-1">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                            <span class="text-[9px] font-bold opacity-50 uppercase tracking-tighter">Available</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-red-500"></div>
                            <span class="text-[9px] font-bold opacity-50 uppercase tracking-tighter">Occupied</span>
                        </div>
                        <div class="w-full h-px mx-1" :class="isDark ? 'bg-white/10' : 'bg-gray-300'"></div>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full animate-pulse" :class="isDark ? 'bg-amber-500' : 'bg-orange-500'"></div>
                            <span class="text-[9px] font-bold opacity-50 uppercase tracking-tighter" x-text="tables.length + ' Registered Tables'"></span>
                        </div>
                    </div>
                </div>

                {{-- Table Grid (simplified drag layout replacement) --}}
                <div class="h-full overflow-auto p-8">
                    <div class="flex flex-wrap gap-4">
                        <template x-for="table in tables" :key="table.id">
                            <button @click="selectTable(table)"
                                class="relative w-28 h-28 rounded-3xl border-2 flex flex-col items-center justify-center gap-1 font-black transition-all duration-300 hover:scale-105 active:scale-95"
                                :class="isOccupied(table)
                                    ? (isDark ? 'bg-red-500/10 border-red-500/40 text-red-400' : 'bg-red-50 border-red-200 text-red-600')
                                    : (isDark ? 'bg-green-500/10 border-green-500/40 text-green-400' : 'bg-green-50 border-green-200 text-green-600')">
                                <span class="text-2xl font-black" x-text="table.table_number"></span>
                                <span class="text-[9px] uppercase tracking-widest opacity-60" x-text="isOccupied(table) ? 'Occupied' : 'Available'"></span>
                                <span class="text-[9px] opacity-40" x-text="table.capacity + ' pax'"></span>
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Modal --}}
        <template x-if="selectedTable">
            <div class="fixed h-[100dvh] inset-0 z-50 flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-md" @click="selectedTable = null"></div>

                <div class="relative w-full max-w-lg rounded-[3rem] border shadow-[0_30px_100px_rgba(0,0,0,0.5)] overflow-hidden"
                    :class="isDark ? 'bg-slate-900 border-white/10' : 'bg-white border-gray-100'">

                    <div class="p-8 pb-0 flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-amber-500 mb-1">Unit Assignment</p>
                            <h3 class="text-6xl font-black tracking-tighter"
                                :class="isDark ? 'text-white' : 'text-slate-950'"
                                x-text="selectedTable.table_number"></h3>
                        </div>
                        <button @click="selectedTable = null"
                            class="p-3 rounded-full transition-colors"
                            :class="isDark ? 'bg-white/5 text-gray-400 hover:text-white' : 'bg-gray-100 text-gray-500'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="p-8 space-y-8">
                        {{-- Active Session Mode --}}
                        <template x-if="activeSession">
                            <div class="space-y-6">
                                <div class="p-6 rounded-3xl border-2"
                                    :class="isDark ? 'bg-amber-500/5 border-amber-500/20' : 'bg-orange-50 border-orange-100'">
                                    <div class="flex items-center gap-3 mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-500 animate-pulse"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                                        <span class="text-[11px] font-black uppercase tracking-widest text-amber-500"
                                            x-text="'Active Session: ' + activeSession.session_token"></span>
                                    </div>
                                    <div class="flex gap-10">
                                        <div>
                                            <p class="text-[9px] font-black uppercase opacity-40">Guests</p>
                                            <p class="text-xl font-black" :class="isDark ? 'text-white' : 'text-slate-900'"
                                                x-text="(selectedTable?.orders?.[0]?.guest ?? 'N/A') + ' Pax'"></p>
                                        </div>
                                        <div>
                                            <p class="text-[9px] font-black uppercase opacity-40">Status</p>
                                            <p class="text-xl font-black uppercase" :class="isDark ? 'text-white' : 'text-slate-900'">Occupied</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <button @click="handleSelect(selectedTable.id)"
                                        class="py-5 rounded-2xl flex items-center justify-center gap-3 font-black uppercase text-xs tracking-widest transition-all active:scale-95"
                                        :class="isDark ? 'bg-white text-slate-900 hover:bg-amber-500' : 'bg-slate-900 text-white hover:bg-slate-800'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5v14"/></svg>
                                        New Bill
                                    </button>
                                    <button @click="handleEndSession(activeSession.session_token)"
                                        class="py-5 rounded-2xl border-2 flex items-center justify-center gap-3 font-black uppercase text-xs tracking-widest transition-all active:scale-95"
                                        :class="!isLastOrderPaid()
                                            ? 'border-gray-500/20 text-gray-500 hover:bg-gray-500/10'
                                            : (isDark ? 'border-red-500/20 text-red-500 hover:bg-red-500/10' : 'border-red-200 text-red-600 hover:bg-red-50')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                        Kill Session
                                    </button>
                                </div>
                            </div>
                        </template>

                        {{-- Empty Table Mode --}}
                        <template x-if="!activeSession">
                            <div class="space-y-8">
                                <div class="space-y-4">
                                    <div class="flex justify-between items-end">
                                        <label class="text-[11px] font-black uppercase tracking-widest text-gray-500">Total Guests</label>
                                        <span class="text-xs font-bold text-amber-500" x-text="'Max Cap: ' + selectedTable.capacity"></span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <template x-for="num in [1,2,4,6,8,10,12]" :key="num">
                                            <button @click="guestCount = num"
                                                :disabled="num > selectedTable.capacity"
                                                class="flex-1 py-4 rounded-2xl border-2 font-black transition-all duration-300 disabled:opacity-10"
                                                :class="guestCount === num
                                                    ? 'bg-amber-500 border-amber-500 text-slate-900'
                                                    : (isDark ? 'bg-white/5 border-transparent text-gray-500 hover:border-white/10' : 'bg-gray-50 border-transparent text-gray-400')"
                                                x-text="num">
                                            </button>
                                        </template>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <label class="text-[11px] font-black uppercase tracking-widest text-gray-500">Service Type</label>
                                    <div class="grid grid-cols-2 gap-4">
                                        <button @click="orderType = 'dine_in'"
                                            class="flex items-center justify-center gap-3 py-5 rounded-3xl border-2 transition-all duration-300 font-black uppercase text-[10px] tracking-[0.2em]"
                                            :class="orderType === 'dine_in'
                                                ? (isDark ? 'bg-white text-slate-900 border-white' : 'bg-amber-500 text-slate-900 border-amber-500')
                                                : (isDark ? 'bg-white/5 border-transparent text-gray-500' : 'bg-gray-50 border-transparent text-gray-400')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/></svg>
                                            Dine In
                                        </button>
                                        <button @click="orderType = 'takeaway'"
                                            class="flex items-center justify-center gap-3 py-5 rounded-3xl border-2 transition-all duration-300 font-black uppercase text-[10px] tracking-[0.2em]"
                                            :class="orderType === 'takeaway'
                                                ? (isDark ? 'bg-white text-slate-900 border-white' : 'bg-amber-500 text-slate-900 border-amber-500')
                                                : (isDark ? 'bg-white/5 border-transparent text-gray-500' : 'bg-gray-50 border-transparent text-gray-400')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 4h3a2 2 0 0 1 2 2v4"/><path d="M2 20h3"/><path d="M13 20h9"/><path d="M10 20 7 4"/><path d="m6 20 3-16"/><path d="M11.5 4h1"/></svg>
                                            Takeaway
                                        </button>
                                    </div>
                                </div>

                                <button @click="handleOpenSession()"
                                    class="w-full py-6 rounded-3xl bg-amber-500 text-slate-950 font-black uppercase text-xs tracking-[0.3em] flex items-center justify-center gap-3 transition-all active:scale-95 shadow-2xl shadow-amber-500/30">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                                    Initialize Session
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                </button>
                            </div>
                        </template>

                        <div class="flex items-center justify-center gap-2 opacity-20">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" :class="isDark ? 'text-white' : 'text-slate-900'"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                            <p class="text-[9px] font-black uppercase tracking-widest" :class="isDark ? 'text-white' : 'text-slate-900'"
                                x-text="'Terminal ID: ' + terminalId"></p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>

<script>
function selectTableApp(tablesData, currentShift) {
    return {
        tables: tablesData,
        currentShift: currentShift,
        selectedTable: null,
        guestCount: 1,
        orderType: 'dine_in',
        endSessionToken: '',
        terminalId: Math.random().toString(36).substring(7).toUpperCase(),

        get activeSession() {
            if (!this.selectedTable?.table_sessions?.length) return null;
            return this.selectedTable.table_sessions[0] ?? null;
        },

        isOccupied(table) {
            return table.table_sessions && table.table_sessions.length > 0;
        },

        isLastOrderPaid() {
            const orders = this.selectedTable?.orders ?? [];
            if (!orders.length) return false;
            return Boolean(orders[orders.length - 1]?.payment);
        },

        selectTable(table) {
            this.selectedTable = table;
            this.guestCount = 1;
        },

        async handleOpenSession() {
            if (!this.selectedTable) return;
            const res = await fetch('/api/order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    tableId: this.selectedTable.id,
                    guest: this.guestCount,
                    type: this.orderType,
                })
            });
            const data = await res.json();
            if (data.redirect) {
                window.location.href = data.redirect;
            } else if (res.ok) {
                window.location.href = '/cashier';
            }
        },

        async handleSelect(tableId) {
            const res = await fetch('/api/order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    tableId: tableId,
                    guest: this.selectedTable?.orders?.[0]?.guest,
                    type: this.orderType,
                })
            });
            const data = await res.json();
            if (data.redirect) {
                window.location.href = data.redirect;
            } else if (res.ok) {
                window.location.href = '/cashier';
            }
        },

        async handleEndSession(sessionToken) {
            if (this.endSessionToken === sessionToken) {
                const res = await fetch(`/api/session/${sessionToken}/end`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                });
                if (res.ok) {
                    this.selectedTable = null;
                    this.endSessionToken = '';
                    window.location.reload();
                }
            } else {
                if (!this.isLastOrderPaid()) {
                    alert('Table Belum Di Bayar');
                    return;
                }
                alert('Klik tombol sekali lagi untuk konfirmasi');
                this.endSessionToken = sessionToken;
            }
        }
    }
}
</script>
@endsection
