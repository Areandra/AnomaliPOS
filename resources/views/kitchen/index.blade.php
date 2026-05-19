<x-cashier-layout title="Kitchen Display" pageTitle="Kitchen Display System">

    <x-slot:header_links>
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
        }"
            @fullscreenchange.window="isFullscreen = !!document.fullscreenElement">

            {{-- Navigasi Links --}}
            @foreach ($links as $link)
                <a href="{{ $link['href'] }}"
                    class="flex items-center gap-3 rounded-full px-6 py-2.5 text-[11px] font-black uppercase tracking-wider transition-all duration-300"
                    :class="isDark ?
                        'bg-slate-800/40 text-amber-500 hover:bg-slate-800/60 border border-white/5' :
                        'bg-white text-orange-600 hover:bg-orange-50 border border-gray-200'">
                    <i data-lucide="{{ $link['icon'] }}" class="h-4 w-4 text-amber-500"></i>
                    <span class="hidden md:block">{{ $link['label'] }}</span>
                </a>
            @endforeach

            {{-- Fullscreen Button --}}
            <button @click="toggleFullscreen()"
                class="flex items-center gap-3 rounded-full px-6 py-2.5 text-[11px] font-black uppercase tracking-wider transition-all duration-300"
                :class="isDark ?
                    'bg-slate-800/40 text-amber-500 hover:bg-slate-800/60 border border-white/5' :
                    'bg-white text-orange-600 hover:bg-orange-50 border border-gray-200'">
                <i data-lucide="maximize" class="h-4 w-4 text-amber-500"></i>
                <span class="hidden md:block" x-text="isFullscreen ? 'EXIT FULLSCREEN' : 'FULLSCREEN'"></span>
            </button>

            {{-- Profile Section --}}
            <div class="ml-2 flex items-center gap-4 border-l border-white/10 pl-6">
                <div class="flex flex-col items-end gap-1 leading-none">
                    <span class="text-[12px] font-black uppercase tracking-tight text-white">
                        {{ Auth::user()->name ?? 'ADNAN' }}
                    </span>
                    <span class="text-[10px] font-black uppercase tracking-tighter text-amber-500">
                        {{ Auth::user()->role ?? 'ADMIN' }}
                    </span>
                </div>

                <a href="/me"
                    class="flex h-12 w-12 items-center justify-center rounded-2xl border-2 transition-all duration-300"
                    :class="isDark ?
                        'bg-slate-800/40 border-white/10 text-gray-400 hover:border-amber-500/50 hover:text-white' :
                        'bg-gray-100 border-white shadow-sm text-gray-500 hover:bg-white'">
                    <i data-lucide="user" class="h-5 w-5"></i>
                </a>
            </div>
        </div>
    </x-slot:header_links>

    {{-- Main Content langsung di-render tanpa bungkus @section --}}
    <div x-data="kotBoard({{ json_encode($kotsData ?? []) }}, {{ json_encode($categoriesData ?? []) }})" x-init="$nextTick(() => lucide.createIcons())" class="flex h-full flex-col">
        {{-- Glow Background (Dark Mode) --}}
        <template x-if="isDark">
            <div class="pointer-events-none fixed inset-0 opacity-20">
                <div class="w-125 h-125 absolute left-0 top-0 rounded-full bg-purple-900 mix-blend-screen blur-[120px]">
                </div>
                <div
                    class="w-125 h-125 absolute bottom-0 right-0 rounded-full bg-blue-900 mix-blend-screen blur-[120px]">
                </div>
            </div>
        </template>

        {{-- Sub Header Toolbar --}}
        <div :class="isDark ? 'bg-slate-900/70 border-white/5' : 'bg-white/70 border-gray-200/50'"
            class="sticky top-0 z-40 mb-4 h-16 w-full border-b px-4 py-2 backdrop-blur-xl transition-all duration-300">
            <div class="mx-auto flex h-full max-w-[1920px] flex-col items-center justify-between gap-4 md:flex-row">
                {{-- Info Section --}}
                <div class="hidden items-center gap-3 md:flex">
                    <div :class="isDark ? 'bg-slate-800 text-amber-500' : 'bg-orange-100 text-orange-600'"
                        class="rounded-lg p-2">
                        <i data-lucide="utensils-crossed" class="h-5 w-5"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold leading-none dark:text-white">Kitchen Board</h1>
                        <p :class="isDark ? 'text-gray-400' : 'text-gray-500'" class="mt-1 text-xs">
                            <span x-text="filteredKots.length"></span> Tickets <span x-text="viewMode"></span>
                        </p>
                    </div>
                </div>

                {{-- Controls --}}
                <div :class="isDark ? 'bg-slate-950 border-white/5' : 'bg-gray-100 border-gray-200'"
                    class="flex items-center gap-2 rounded-full border p-1.5 shadow-inner">
                    <div class="relative flex rounded-full bg-transparent">
                        <template x-for="mode in ['active', 'completed']" :key="mode">
                            <button @click="viewMode = mode"
                                :class="viewMode === mode ?
                                    (isDark ? 'text-white' : 'text-gray-800 shadow-sm bg-white') :
                                    'text-gray-500 hover:text-gray-700'"
                                class="relative z-10 rounded-full px-5 py-2 text-xs font-bold capitalize transition-all duration-300">
                                <span x-show="viewMode === mode && isDark"
                                    class="absolute inset-0 -z-10 rounded-full border border-white/5 bg-slate-800"></span>
                                <span x-text="mode"></span>
                            </button>
                        </template>
                    </div>

                    <div :class="isDark ? 'bg-white/10' : 'bg-gray-300'" class="mx-1 h-6 w-px"></div>

                    <button @click="groupMode = groupMode === 'flat' ? 'table' : 'flat'"
                        :class="groupMode === 'table'
                            ?
                            (isDark ? 'bg-indigo-600 text-white' : 'bg-gray-800 text-white') :
                            (isDark ? 'text-gray-400 hover:bg-slate-800' :
                                'text-gray-600 hover:bg-white')"
                        class="whitespace-nowrap rounded-full px-4 py-2 text-xs font-bold transition-colors">
                        <span x-text="groupMode === 'flat' ? 'Group: None' : 'Group: Table'"></span>
                    </button>

                    <div :class="isDark ? 'bg-white/10' : 'bg-gray-300'" class="mx-1 h-6 w-px"></div>

                    <button @click="$dispatch('toggle-theme')"
                        class="rounded-full text-slate-600 shadow-sm transition-transform hover:scale-110 hover:bg-white active:rotate-90 dark:text-amber-400 dark:shadow-none dark:hover:bg-slate-800 p-2">
                        <span x-show="isDark"><x-lucide-sun class="h-[18px] w-[18px]" /></span>
                        <span x-show="!isDark"><x-lucide-moon class="h-[18px] w-[18px]" /></span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Kategori Filter --}}
        <div class="mb-4 px-4">
            <div :class="isDark ? 'bg-slate-900 border-white/10' : 'bg-white/90 border-gray-200'"
                class="scrollbar-hide flex w-full gap-2 overflow-x-auto rounded-full border p-1.5 shadow-sm backdrop-blur-md">
                <template x-for="item in sortedCategories" :key="item.id">
                    <button @click="categorySelect = item.id"
                        :class="categorySelect === item.id ?
                            (isDark ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-500/20' :
                                'bg-orange-600 text-white shadow-lg shadow-orange-600/20') :
                            (isDark ? 'text-gray-400 hover:bg-white/5 hover:text-white' :
                                'text-gray-500 hover:bg-gray-100 hover:text-slate-900')"
                        class="flex shrink-0 items-center rounded-full p-2.5 px-6 text-[11px] font-black uppercase tracking-widest transition-all duration-300"
                        x-text="item.name">
                    </button>
                </template>
            </div>
        </div>

        {{-- Main Board Area --}}
        <div class="scrollbar-hide h-full flex-1 overflow-y-auto px-4 pb-44">
            {{-- FLAT MODE --}}
            <div x-show="groupMode === 'flat'"
                class="mx-auto grid max-w-[1920px] grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
                <template x-for="kot in filteredKots" :key="kot.id">
                    <div x-html="renderKotCard(kot)"></div>
                </template>
            </div>

            {{-- TABLE MODE --}}
            <div x-show="groupMode === 'table'" class="mx-auto flex max-w-[1920px] flex-col gap-6">
                <template x-for="[orderId, items] in groupByOrder" :key="orderId">
                    <div :class="isDark ? 'bg-slate-900/40 border-slate-700/50' : 'bg-white border-gray-200'"
                        class="flex h-fit w-full flex-col rounded-3xl border p-4 shadow-sm">
                        <div
                            class="mb-4 flex items-center justify-between border-b border-dashed border-gray-500/20 pb-2">
                            <div>
                                <h2 :class="isDark ? 'text-gray-200' : 'text-gray-800'"
                                    class="text-sm font-bold uppercase">
                                    Table <span x-text="items[0]?.order?.table?.table_number ?? 'N/A'"></span>
                                </h2>
                                <span class="text-[10px] text-gray-500">Order #<span x-text="orderId"></span></span>
                            </div>
                            <span
                                :class="items[0]?.order?.status === 'served' ? 'bg-green-500/20 text-green-500' :
                                    'bg-amber-500/20 text-amber-500'"
                                class="rounded px-2 py-1 text-[10px] font-bold uppercase"
                                x-text="items[0]?.order?.status"></span>
                        </div>
                        <div class="scrollbar-hide flex flex-row gap-6 overflow-x-auto pt-2">
                            <template x-for="kot in items" :key="kot.id">
                                <div class="w-[300px] shrink-0" x-html="renderKotCard(kot)"></div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Empty State --}}
            <div x-show="filteredKots.length === 0"
                class="flex h-64 w-full flex-col items-center justify-center opacity-50">
                <div :class="isDark ? 'bg-slate-800' : 'bg-gray-100'"
                    class="mb-3 rounded-full p-4 text-slate-500 dark:text-gray-400">
                    <i data-lucide="utensils-crossed" class="h-8 w-8"></i>
                </div>
                <p class="text-sm font-medium dark:text-gray-300">No tickets found in this view.</p>
            </div>
        </div>
    </div>

    <script>
        function kotBoard(kotsData, categoriesData) {
            console.log(kotsData)
            return {
                kots: kotsData,
                categories: categoriesData,
                viewMode: 'active',
                groupMode: 'flat',
                categorySelect: -1,

                get sortedCategories() {
                    return [{
                        id: -1,
                        name: 'All',
                        sortOrder: -1
                    }, ...this.categories].sort((a, b) => a.sortOrder - b.sortOrder)
                },

                get filteredKots() {
                    return this.kots
                        .filter(k => this.categorySelect > 0 ? k.order_item?.menu_item?.category.id === this
                            .categorySelect : true)
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
                            body: JSON.stringify({
                                status: newStatus
                            })
                        })

                        if (res.ok) {
                            // Update data lokal agar UI berubah tanpa reload
                            const itemIdx = this.kots.findIndex(k => k.order_item?.id === id)
                            if (itemIdx !== -1) {
                                this.kots[itemIdx].order_item.status = newStatus
                                if (newStatus === 'delivered') this.kots[itemIdx].status = 'done'
                            }
                            this.$nextTick(() => lucide.createIcons())
                        }
                    } catch (err) {
                        console.error("Gagal update status", err)
                    }
                },

                renderKotCard(kot) {
                    if (!kot.order_item) return ''

                    // AMBIL DARI SCOPE ELEMENT YANG SEDANG AKTIF (Mewarisi globalAppManager)
                    // 'this' di sini merujuk ke object return dari kotBoard yang berada di bawah scope globalAppManager
                    const isDark = this.isDark;

                    const isDone = kot.status === 'done'
                    const nextStatus = (s) => {
                        const flow = ['cart', 'ordered', 'cooking', 'ready', 'delivered']
                        return flow[flow.indexOf(s) + 1] || s
                    }

                    const cardBg = isDark ? 'bg-slate-800/60 border-slate-700/50 hover:bg-slate-800/80' :
                        'bg-white border-gray-100 hover:bg-gray-50'
                    const textColor = isDark ? 'text-gray-200' : 'text-gray-800'
                    const subTextColor = isDark ? 'text-gray-400' : 'text-gray-500'
                    const titleColor = isDark ? 'text-amber-500' : 'text-[#5B4636]'

                    const timestamp = kot.created_at || kot.createdAt;
                    const time = new Date(timestamp).toLocaleTimeString('en-GB', {
                        hour: '2-digit',
                        minute: '2-digit'
                    })

                    const tableNum = kot.order.table.table_number || kot.order.table.tableNumber || '?';

                    return `
    <div class="relative flex flex-col p-5 rounded-[2rem] border backdrop-blur-sm shadow-sm transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-xl ${cardBg}">
        <div class="flex justify-between items-start mb-3">
            <div class="flex flex-col">
                <span class="font-bold text-lg tracking-tight ${titleColor}">#${kot.id}</span>
                <span class="text-[10px] uppercase font-bold tracking-wider ${subTextColor}">
                    ${tableNum.includes('A') ? '1st Floor' : 'G. Floor'}
                </span>
            </div>
            <div class="px-2 py-1 rounded-lg text-xs font-mono ${isDark ? 'bg-slate-950 text-slate-400' : 'bg-gray-100 text-gray-500'}">
                ${time}
            </div>
        </div>

        <div class="mb-4 flex items-center gap-2">
            <div class="h-8 w-8 rounded-full flex items-center justify-center text-xs font-bold ${isDark ? 'bg-indigo-500/20 text-indigo-300' : 'bg-orange-100 text-orange-700'}">
                ${tableNum}
            </div>
            <span class="text-xs font-medium ${subTextColor}">Table Unit</span>
        </div>

        <div class="grow mb-4 pb-4 border-b border-dashed ${isDark ? 'border-slate-700' : 'border-gray-200'}">
            <div class="flex justify-between items-baseline">
                <h3 class="text-base font-black uppercase tracking-tight leading-snug ${textColor}">${kot.order_item.menu_item?.name || kot.order_item.menuItem?.name}</h3>
                <span class="ml-2 px-2 py-0.5 text-sm font-black rounded-md ${isDark ? 'bg-emerald-500/20 text-emerald-400' : 'bg-green-100 text-green-700'}">
                    x${kot.order_item.quantity}
                </span>
            </div>
            ${kot.order_item.notes ? `
                        <div class="mt-3 space-y-1">
                            ${kot.order_item.notes.split('. ').map(n => `
                        <div class="text-xs flex items-start gap-1.5 ${isDark ? 'text-red-300' : 'text-red-600'} font-bold">
                            <span class="mt-0.5 text-[8px]">●</span> ${n}
                        </div>
                    `).join('')}
                        </div>
                    ` : ''}
        </div>

        ${!isDone ? `
                    <button onclick="Alpine.$data(this.closest('[x-data]')).updateStatus(${kot.order_item.id}, '${kot.order_item.status}')"
                            class="flex items-center w-full justify-center p-3 gap-2 rounded-full text-[10px] font-black uppercase tracking-widest transition-all duration-300 ${isDark ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-500/20' : 'bg-orange-600 text-white shadow-lg shadow-orange-600/20'}">
                        <span class="opacity-70">${kot.order_item.status}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        <span>${nextStatus(kot.order_item.status)}</span>
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
</x-cashier-layout>
