@props([
    'title' => 'Auth Terminal',
    'subtitle' => 'Masukkan kredensial Anda',
])

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>

<body>
    <div x-data x-cloak>

        {{-- Notification Stack (Tetap Utuh) --}}
        <x-notification />


        <div class="flex h-dvh flex-col bg-slate-950 font-sans lg:flex-row">

            {{-- LEFT: BRANDING (Tetap Utuh) --}}
            <div class="relative hidden overflow-hidden bg-cover bg-center lg:block lg:w-3/5"
                style="background-image: url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=2070&auto=format&fit=crop')">

                <div class="absolute inset-0 bg-gradient-to-tr from-slate-950 via-slate-950/40 to-transparent"></div>

                <div class="absolute inset-0 flex flex-col justify-between p-16">
                    <div class="flex items-center gap-3">
                        <div class="rounded-2xl bg-amber-500 p-3 shadow-xl shadow-amber-500/20">
                            <i data-lucide="utensils-crossed" class="h-7 w-7 text-slate-950" stroke-width="2.5"></i>
                        </div>
                        <span class="text-xl font-black uppercase tracking-[0.3em] text-white">
                            Ano<span class="text-amber-500">Pos</span>
                        </span>
                    </div>

                    <div class="max-w-xl">
                        <div
                            class="mb-6 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/10 px-3 py-1 backdrop-blur-md">
                            <i data-lucide="shield-check" class="h-3.5 w-3.5 text-amber-500"></i>
                            <span class="text-[10px] font-black uppercase tracking-widest text-white/80">Enterprise
                                Grade Security</span>
                        </div>

                        <h1 class="mb-6 text-6xl font-black uppercase leading-[0.9] tracking-tighter text-white">
                            Elevate Your <br>
                            <span class="text-amber-500">Culinary</span> Business.
                        </h1>

                        <div class="mb-6 h-1 w-24 rounded-full bg-amber-500"></div>

                        <p class="max-w-md text-lg font-medium leading-relaxed text-gray-300 opacity-80">
                            Satu platform terintegrasi untuk mengelola pesanan, ketersediaan meja, cashier dan laporan
                            finansial secara real-time.
                        </p>
                    </div>

                    <div class="flex items-center gap-8 opacity-40">
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-white">© 2026 ANOPOS SYSTEMS
                        </p>
                        <div class="h-px w-12 bg-white/30"></div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-white">V.0.5.alpha</p>
                    </div>
                </div>
            </div>

            {{-- RIGHT: FORM (Bagian Konten Dinamis) --}}
            <div
                class="no-scrollbar relative flex w-full flex-col items-center overflow-y-auto bg-slate-950 px-8 py-12 sm:px-16 lg:w-2/5">
                <div
                    class="pointer-events-none absolute left-1/2 top-1/2 h-64 w-64 -translate-x-1/2 -translate-y-1/2 rounded-full bg-amber-500/10 blur-[100px]">
                </div>

                <div class="relative z-10 my-auto w-full max-w-sm">
                    {{-- Mobile Logo --}}
                    <div class="mb-12 flex items-center justify-center gap-3 lg:hidden">
                        <div class="rounded-xl bg-amber-500 p-2">
                            <i data-lucide="layout-grid" class="h-5 w-5 text-slate-950"></i>
                        </div>
                        <span class="text-lg font-black uppercase tracking-[0.2em] text-white">
                            Ano<span class="text-amber-500">Pos</span>
                        </span>
                    </div>

                    <div class="mb-10 space-y-2 text-center lg:text-left">
                        <h3 class="text-3xl font-black uppercase tracking-tighter text-white">{{ $title }}</h3>
                        <p class="text-sm font-medium uppercase tracking-wider text-gray-500">{{ $subtitle }}</p>
                    </div>

                    <div class="rounded-3xl border border-white/5 bg-white/5 p-2 backdrop-blur-sm">
                        <div class="rounded-[1.8rem] bg-slate-900/50 p-8">
                            {{-- Tempat Utama Form Berada --}}
                            {{ $slot }}
                        </div>
                    </div>

                    {{-- Tempat Link Footer Dinamis --}}
                    @if (isset($footerLink))
                        <div class="mt-8 text-center">
                            {{ $footerLink }}
                        </div>
                    @endif
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
                    this.items.push({
                        id,
                        type,
                        title,
                        message,
                        visible: true
                    })
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

            Alpine.store('notif', {
                set(type, title, message = '') {
                    Alpine.store('notifs').set(type, title, message)
                }
            })
        })
    </script>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>
