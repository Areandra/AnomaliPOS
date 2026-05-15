<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Auth Terminal')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        [x-cloak] { display: none !important; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</head>

<body>
    <div x-data x-cloak>

        {{-- Notification Stack --}}
        <div class="fixed top-5 right-5 z-[100] flex flex-col gap-2 pointer-events-none" style="min-width:280px">
            <template x-for="notif in $store.notifs.items" :key="notif.id">
                <div
                    x-show="notif.visible"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-x-4"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 translate-x-4"
                    @click="$store.notifs.dismiss(notif.id)"
                    :class="notif.type === 'success' ? 'bg-emerald-500 shadow-emerald-500/20' : 'bg-red-500 shadow-red-500/20'"
                    class="px-5 py-4 rounded-2xl text-white shadow-xl cursor-pointer pointer-events-auto w-full"
                >
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs font-black uppercase tracking-widest" x-text="notif.title"></p>
                            <p class="text-[10px] opacity-80 mt-0.5 normal-case" x-text="notif.message"></p>
                        </div>
                        <button class="opacity-60 hover:opacity-100 shrink-0 mt-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <div class="h-dvh flex flex-col lg:flex-row bg-slate-950 font-sans">

            {{-- LEFT: BRANDING --}}
            <div class="hidden lg:block lg:w-3/5 relative bg-cover bg-center overflow-hidden"
                style="background-image: url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=2070&auto=format&fit=crop')">

                <div class="absolute inset-0 bg-gradient-to-tr from-slate-950 via-slate-950/40 to-transparent"></div>

                <div class="absolute inset-0 flex flex-col justify-between p-16">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-amber-500 rounded-2xl shadow-xl shadow-amber-500/20">
                            <i data-lucide="utensils-crossed" class="text-slate-950 w-7 h-7" stroke-width="2.5"></i>
                        </div>
                        <span class="text-xl font-black uppercase tracking-[0.3em] text-white">
                            Ano<span class="text-amber-500">Pos</span>
                        </span>
                    </div>

                    <div class="max-w-xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/10 backdrop-blur-md mb-6">
                            <i data-lucide="shield-check" class="text-amber-500 w-3.5 h-3.5"></i>
                            <span class="text-[10px] font-black uppercase tracking-widest text-white/80">Enterprise Grade Security</span>
                        </div>

                        <h1 class="text-6xl font-black text-white mb-6 leading-[0.9] tracking-tighter uppercase">
                            Elevate Your <br>
                            <span class="text-amber-500">Culinary</span> Business.
                        </h1>

                        <div class="h-1 w-24 bg-amber-500 mb-6 rounded-full"></div>

                        <p class="text-gray-300 text-lg font-medium leading-relaxed max-w-md opacity-80">
                            Satu platform terintegrasi untuk mengelola pesanan, ketersediaan meja, cashier dan laporan finansial secara real-time.
                        </p>
                    </div>

                    <div class="flex items-center gap-8 opacity-40">
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-white">© 2026 ANOPOS SYSTEMS</p>
                        <div class="h-px w-12 bg-white/30"></div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-white">V.0.5.alpha</p>
                    </div>
                </div>
            </div>

            {{-- RIGHT: FORM --}}
            <div class="w-full lg:w-2/5 flex flex-col items-center bg-slate-950 relative overflow-y-auto no-scrollbar py-12 px-8 sm:px-16">
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-amber-500/10 blur-[100px] rounded-full pointer-events-none"></div>

                <div class="w-full max-w-sm relative z-10 my-auto">
                    {{-- Mobile Logo --}}
                    <div class="lg:hidden flex items-center gap-3 mb-12 justify-center">
                        <div class="p-2 bg-amber-500 rounded-xl">
                            <i data-lucide="layout-grid" class="text-slate-950 w-5 h-5"></i>
                        </div>
                        <span class="text-lg font-black uppercase tracking-[0.2em] text-white">
                            Ano<span class="text-amber-500">Pos</span>
                        </span>
                    </div>

                    <div class="space-y-2 mb-10 text-center lg:text-left">
                        <h3 class="text-3xl font-black text-white tracking-tighter uppercase">@yield('title', 'Auth Terminal')</h3>
                        <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">@yield('subtitle', 'Masukkan kredensial Anda')</p>
                    </div>

                    <div class="bg-white/5 border border-white/5 p-2 rounded-3xl backdrop-blur-sm">
                        <div class="bg-slate-900/50 p-8 rounded-[1.8rem]">
                            @yield('form')
                        </div>
                    </div>

                    <div class="mt-8 text-center">
                        @yield('footer_link')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('notifs', {
                items: [],
                _counter: 0,

                set(type, title, message = '') {
                    const id = ++this._counter
                    this.items.push({ id, type, title, message, visible: true })
                    setTimeout(() => this.dismiss(id), 4000)
                },

                dismiss(id) {
                    const item = this.items.find(n => n.id === id)
                    if (item) item.visible = false
                    setTimeout(() => {
                        this.items = this.items.filter(n => n.id !== id)
                    }, 300)
                }
            })

            // backward compat — komponen lain masih pakai $store.notif.set(...)
            Alpine.store('notif', {
                set(type, title, message = '') {
                    Alpine.store('notifs').set(type, title, message)
                }
            })
        })
    </script>

    <script>lucide.createIcons();</script>
</body>
</html>
