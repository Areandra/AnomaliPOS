@extends('layouts.cashier')

@section('title', 'Kitchen Display')
@section('cashier_title', 'Kitchen Display System')

@section('header_links')
    @php
        $links = [
            ['label' => 'MANAGEMENT', 'href' => '/menu', 'icon' => 'settings'],
            ['label' => 'CASHIER', 'href' => '/cashier/order', 'icon' => 'calculator'],
        ];
    @endphp

    <div class="flex items-center gap-4" x-data="{ 
        isFullscreen: false,
        toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
                this.isFullscreen = true;
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                    this.isFullscreen = false;
                }
            }
        }
    }" @fullscreenchange.window="isFullscreen = !!document.fullscreenElement">
        
        {{-- Navigasi Links --}}
        @foreach($links as $link)
            <a href="{{ $link['href'] }}"
               class="flex items-center gap-3 px-6 py-2.5 rounded-full duration-300 font-black text-[11px] uppercase tracking-wider transition-all"
               :class="$store.theme.isDark 
                    ? 'bg-slate-800/40 text-amber-500 hover:bg-slate-800/60 border border-white/5' 
                    : 'bg-white text-orange-600 hover:bg-orange-50 border border-gray-200'">
                <i data-lucide="{{ $link['icon'] }}" class="w-4 h-4 text-amber-500"></i>
                <span class="hidden md:block">{{ $link['label'] }}</span>
            </a>
        @endforeach

        {{-- Fullscreen Button --}}
        <button @click="toggleFullscreen()"
                class="flex items-center gap-3 px-6 py-2.5 rounded-full duration-300 font-black text-[11px] uppercase tracking-wider transition-all"
                :class="$store.theme.isDark 
                    ? 'bg-slate-800/40 text-amber-500 hover:bg-slate-800/60 border border-white/5' 
                    : 'bg-white text-orange-600 hover:bg-orange-50 border border-gray-200'">
            <i data-lucide="maximize" class="w-4 h-4 text-amber-500"></i>
            <span class="hidden md:block" x-text="isFullscreen ? 'EXIT FULLSCREEN' : 'FULLSCREEN'"></span>
        </button>

        {{-- Profile Section (Sesuai Gambar) --}}
        <div class="flex items-center gap-4 ml-2 border-l border-white/10 pl-6">
            <div class="flex flex-col items-end leading-none gap-1">
                <span class="text-[12px] font-black uppercase text-white tracking-tight">
                    {{ Auth::user()->name ?? 'ADNAN' }}
                </span>
                <span class="text-[10px] font-black uppercase text-amber-500 tracking-tighter">
                    {{ Auth::user()->role ?? 'ADMIN' }}
                </span>
            </div>

            <a href="/me"
               class="w-12 h-12 rounded-2xl flex items-center justify-center border-2 transition-all duration-300"
               :class="$store.theme.isDark 
                    ? 'bg-slate-800/40 border-white/10 text-gray-400 hover:border-amber-500/50 hover:text-white' 
                    : 'bg-gray-100 border-white shadow-sm text-gray-500 hover:bg-white'">
                <i data-lucide="user" class="w-5 h-5"></i>
            </a>
        </div>
    </div>
@endsection

@section('content')
<div
    x-data="kotBoard({{ json_encode($kotsData ?? []) }}, {{ json_encode($categoriesData ?? []) }})"
    x-init="$nextTick(() => lucide.createIcons())"
    class="h-full flex flex-col"
>
    {{-- Glow Background (Dark Mode) --}}
    <template x-if="$store.theme.isDark">
        <div class="fixed inset-0 pointer-events-none opacity-20">
            <div class="absolute top-0 left-0 w-125 h-125 bg-purple-900 rounded-full blur-[120px] mix-blend-screen"></div>
            <div class="absolute bottom-0 right-0 w-125 h-125 bg-blue-900 rounded-full blur-[120px] mix-blend-screen"></div>
        </div>
    </template>

    {{-- Sub Header Toolbar --}}
    <div
        :class="$store.theme.isDark ? 'bg-slate-900/70 border-white/5' : 'bg-white/70 border-gray-200/50'"
        class="sticky top-0 z-40 w-full px-4 py-2 h-16 backdrop-blur-xl border-b transition-all duration-300 mb-4"
    >
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 h-full max-w-[1920px] mx-auto">
            {{-- Info Section --}}
            <div class="hidden md:flex items-center gap-3">
                <div :class="$store.theme.isDark ? 'bg-slate-800 text-amber-500' : 'bg-orange-100 text-orange-600'" class="p-2 rounded-lg">
                    <i data-lucide="utensils-crossed" class="w-5 h-5"></i>
                </div>
                <div>
                    <h1 class="font-bold text-lg leading-none dark:text-white">Kitchen Board</h1>
                    <p :class="$store.theme.isDark ? 'text-gray-400' : 'text-gray-500'" class="text-xs mt-1">
                        <span x-text="filteredKots.length"></span> Tickets <span x-text="viewMode"></span>
                    </p>
                </div>
            </div>

            {{-- Controls --}}
            <div :class="$store.theme.isDark ? 'bg-slate-950 border-white/5' : 'bg-gray-100 border-gray-200'" class="flex items-center gap-2 p-1.5 rounded-full border shadow-inner">
                <div class="flex relative bg-transparent rounded-full">
                    <template x-for="mode in ['active', 'completed']" :key="mode">
                        <button @click="viewMode = mode"
                            :class="viewMode === mode
                                ? ($store.theme.isDark ? 'text-white' : 'text-gray-800 shadow-sm bg-white')
                                : 'text-gray-500 hover:text-gray-700'"
                            class="relative px-5 py-2 rounded-full text-xs font-bold transition-all duration-300 capitalize z-10">
                            <span x-show="viewMode === mode && $store.theme.isDark" class="absolute inset-0 bg-slate-800 rounded-full -z-10 border border-white/5"></span>
                            <span x-text="mode"></span>
                        </button>
                    </template>
                </div>

                <div :class="$store.theme.isDark ? 'bg-white/10' : 'bg-gray-300'" class="w-px h-6 mx-1"></div>

                <button @click="groupMode = groupMode === 'flat' ? 'table' : 'flat'"
                    :class="groupMode === 'table'
                        ? ($store.theme.isDark ? 'bg-indigo-600 text-white' : 'bg-gray-800 text-white')
                        : ($store.theme.isDark ? 'text-gray-400 hover:bg-slate-800' : 'text-gray-600 hover:bg-white')"
                    class="px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap transition-colors">
                    <span x-text="groupMode === 'flat' ? 'Group: None' : 'Group: Table'"></span>
                </button>

                <div :class="$store.theme.isDark ? 'bg-white/10' : 'bg-gray-300'" class="w-px h-6 mx-1"></div>

                <button @click="$store.theme.toggle(); $nextTick(() => lucide.createIcons())"
                    :class="$store.theme.isDark ? 'text-amber-400 hover:bg-slate-800' : 'text-slate-600 hover:bg-white shadow-sm'"
                    class="p-2 rounded-full transition-transform active:rotate-90 hover:scale-110">
                    <i :data-lucide="$store.theme.isDark ? 'sun' : 'moon'" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Kategori Filter --}}
    <div class="px-4 mb-4">
        <div :class="$store.theme.isDark ? 'bg-slate-900 border-white/10' : 'bg-white/90 border-gray-200'" class="w-full border rounded-full shadow-sm backdrop-blur-md p-1.5 flex gap-2 overflow-x-auto scrollbar-hide">
            <template x-for="item in sortedCategories" :key="item.id">
                <button @click="categorySelect = item.id"
                    :class="categorySelect === item.id
                        ? ($store.theme.isDark ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-500/20' : 'bg-orange-600 text-white shadow-lg shadow-orange-600/20')
                        : ($store.theme.isDark ? 'text-gray-400 hover:bg-white/5 hover:text-white' : 'text-gray-500 hover:bg-gray-100 hover:text-slate-900')"
                    class="flex items-center px-6 p-2.5 rounded-full text-[11px] font-black uppercase tracking-widest transition-all duration-300 shrink-0"
                    x-text="item.name">
                </button>
            </template>
        </div>
    </div>

    {{-- Main Board Area --}}
    <div class="flex-1 h-full overflow-y-auto px-4 pb-44 scrollbar-hide">
        {{-- FLAT MODE --}}
        <div x-show="groupMode === 'flat'" class="grid gap-6 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 max-w-[1920px] mx-auto">
            <template x-for="kot in filteredKots" :key="kot.id">
                <div x-html="renderKotCard(kot)"></div>
            </template>
        </div>

        {{-- TABLE MODE --}}
        <div x-show="groupMode === 'table'" class="flex flex-col gap-6 max-w-[1920px] mx-auto">
            <template x-for="[orderId, items] in groupByOrder" :key="orderId">
                <div :class="$store.theme.isDark ? 'bg-slate-900/40 border-slate-700/50' : 'bg-white border-gray-200'" class="flex flex-col rounded-3xl p-4 border shadow-sm h-fit w-full">
                    <div class="mb-4 pb-2 border-b border-dashed border-gray-500/20 flex justify-between items-center">
                        <div>
                            <h2 :class="$store.theme.isDark ? 'text-gray-200' : 'text-gray-800'" class="font-bold text-sm uppercase">
                                Table <span x-text="items[0]?.order?.table?.tableNumber ?? 'N/A'"></span>
                            </h2>
                            <span class="text-[10px] text-gray-500">Order #<span x-text="orderId"></span></span>
                        </div>
                        <span :class="items[0]?.order?.status === 'served' ? 'bg-green-500/20 text-green-500' : 'bg-amber-500/20 text-amber-500'"
                              class="px-2 py-1 rounded text-[10px] font-bold uppercase" x-text="items[0]?.order?.status"></span>
                    </div>
                    <div class="overflow-x-auto pt-2 flex flex-row gap-6 scrollbar-hide">
                        <template x-for="kot in items" :key="kot.id">
                            <div class="w-[300px] shrink-0" x-html="renderKotCard(kot)"></div>
                        </template>
                    </div>
                </div>
            </template>
        </div>

        {{-- Empty State --}}
        <div x-show="filteredKots.length === 0" class="flex flex-col items-center justify-center h-64 w-full opacity-50">
            <div :class="$store.theme.isDark ? 'bg-slate-800' : 'bg-gray-100'" class="p-4 rounded-full mb-3 text-slate-500 dark:text-gray-400">
                <i data-lucide="utensils-crossed" class="w-8 h-8"></i>
            </div>
            <p class="text-sm font-medium dark:text-gray-300">No tickets found in this view.</p>
        </div>
    </div>
</div>

<script>
function kotBoard(kotsData, categoriesData) {
    return {
        kots: kotsData,
        categories: categoriesData,
        viewMode: 'active',
        groupMode: 'flat',
        categorySelect: -1,

        get sortedCategories() {
            return [{ id: -1, name: 'All', sortOrder: -1 }, ...this.categories].sort((a, b) => a.sortOrder - b.sortOrder)
        },

        get filteredKots() {
            return this.kots
                .filter(k => this.categorySelect > 0 ? k.orderItem?.menuItem?.category.id === this.categorySelect : true)
                .filter(k => this.viewMode === 'active' ? k.status !== 'done' : k.status === 'done')
        },

        get groupByOrder() {
            const map = new Map()
            this.filteredKots.forEach(k => {
                const id = k.order.id
                if (!map.has(id)) map.set(id, [])
                map.get(id).push(k)
            })
            return Array.from(map.entries())
        },

        async updateStatus(id, currentStatus) {
            const flow = ['cart', 'ordered', 'cooking', 'ready', 'delivered']
            const idx = flow.indexOf(currentStatus)
            const newStatus = flow[idx + 1] || currentStatus

            try {
                const res = await fetch(`/kitchen/order-item/${id}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                
                if (res.ok) {
                    // Update data lokal agar UI berubah tanpa reload
                    const itemIdx = this.kots.findIndex(k => k.orderItem?.id === id)
                    if (itemIdx !== -1) {
                        this.kots[itemIdx].orderItem.status = newStatus
                        if (newStatus === 'delivered') this.kots[itemIdx].status = 'done'
                    }
                    this.$nextTick(() => lucide.createIcons())
                }
            } catch (err) {
                console.error("Gagal update status", err)
            }
        },

        renderKotCard(kot) {
            if (!kot.orderItem) return ''
            const isDark = Alpine.store('theme').isDark
            const isDone = kot.status === 'done'
            const nextStatus = (s) => {
                const flow = ['cart', 'ordered', 'cooking', 'ready', 'delivered']
                return flow[flow.indexOf(s) + 1] || s
            }

            const cardBg = isDark ? 'bg-slate-800/60 border-slate-700/50 hover:bg-slate-800/80' : 'bg-white border-gray-100 hover:bg-gray-50'
            const textColor = isDark ? 'text-gray-200' : 'text-gray-800'
            const subTextColor = isDark ? 'text-gray-400' : 'text-gray-500'
            const titleColor = isDark ? 'text-amber-500' : 'text-[#5B4636]'
            const time = new Date(kot.createdAt).toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' })

            return `
                <div class="relative flex flex-col p-5 rounded-[2rem] border backdrop-blur-sm shadow-sm transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-xl ${cardBg}">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex flex-col">
                            <span class="font-bold text-lg tracking-tight ${titleColor}">#${kot.id}</span>
                            <span class="text-[10px] uppercase font-bold tracking-wider ${subTextColor}">
                                ${kot.order.table.tableNumber?.includes('A') ? '1st Floor' : 'G. Floor'}
                            </span>
                        </div>
                        <div class="px-2 py-1 rounded-lg text-xs font-mono ${isDark ? 'bg-slate-950 text-slate-400' : 'bg-gray-100 text-gray-500'}">
                            ${time}
                        </div>
                    </div>

                    <div class="mb-4 flex items-center gap-2">
                        <div class="h-8 w-8 rounded-full flex items-center justify-center text-xs font-bold ${isDark ? 'bg-indigo-500/20 text-indigo-300' : 'bg-orange-100 text-orange-700'}">
                            ${kot.order.table.tableNumber ?? '?'}
                        </div>
                        <span class="text-xs font-medium ${subTextColor}">Table Unit</span>
                    </div>

                    <div class="grow mb-4 pb-4 border-b border-dashed ${isDark ? 'border-slate-700' : 'border-gray-200'}">
                        <div class="flex justify-between items-baseline">
                            <h3 class="text-base font-black uppercase tracking-tight leading-snug ${textColor}">${kot.orderItem.menuItem.name}</h3>
                            <span class="ml-2 px-2 py-0.5 text-sm font-black rounded-md ${isDark ? 'bg-emerald-500/20 text-emerald-400' : 'bg-green-100 text-green-700'}">
                                x${kot.orderItem.quantity}
                            </span>
                        </div>
                        ${kot.orderItem.notes ? `
                            <div class="mt-3 space-y-1">
                                ${kot.orderItem.notes.split('. ').map(n => `
                                    <div class="text-xs flex items-start gap-1.5 ${isDark ? 'text-red-300' : 'text-red-600'} font-bold">
                                        <span class="mt-0.5 text-[8px]">●</span> ${n}
                                    </div>
                                `).join('')}
                            </div>
                        ` : ''}
                    </div>

                    ${!isDone ? `
                        <button onclick="Alpine.$data(this.closest('[x-data]')).updateStatus(${kot.orderItem.id}, '${kot.orderItem.status}')"
                                class="flex items-center w-full justify-center p-3 gap-2 rounded-full text-[10px] font-black uppercase tracking-widest transition-all duration-300 ${isDark ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-500/20' : 'bg-orange-600 text-white shadow-lg shadow-orange-600/20'}">
                            <span class="opacity-70">${kot.orderItem.status}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            <span>${nextStatus(kot.orderItem.status)}</span>
                        </button>
                    ` : `
                        <div class="mt-auto text-center py-2 rounded-xl text-sm font-bold flex items-center justify-center gap-2 ${isDark ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-green-50 text-green-600 border border-green-200'}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg> Completed
                        </div>
                    `}
                </div>
            `
        }
    }
}
</script>
@endsection