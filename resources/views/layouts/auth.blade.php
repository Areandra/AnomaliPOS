<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Auth Terminal')</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Lucide Icons (sama seperti lucide-react) --}}
    <script src="https://unpkg.com/lucide@latest"></script>

    {{-- FingerprintJS (sama seperti @fingerprintjs/fingerprintjs) --}}

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body>
    <div x-data x-cloak>

        {{-- Global Notification Store --}}
        <div x-data x-show="$store.notif.open" x-transition.opacity @click="$store.notif.open = false"
            :class="$store.notif.type === 'success' ? 'bg-green-500' : 'bg-red-500'"
            class="fixed top-5 right-5 z-[100] px-5 py-4 rounded-2xl text-white text-xs font-black uppercase tracking-widest shadow-xl cursor-pointer max-w-xs">
            <p x-text="$store.notif.title"></p>
            <p x-text="$store.notif.message" class="opacity-80 normal-case text-[10px] mt-0.5"></p>
        </div>

        <div class="h-dvh flex flex-col lg:flex-row bg-slate-950 font-sans">

            {{-- LEFT SECTION: BRANDING --}}
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
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/10 backdrop-blur-md mb-6">
                            <i data-lucide="shield-check" class="text-amber-500 w-3.5 h-3.5"></i>
                            <span class="text-[10px] font-black uppercase tracking-widest text-white/80">Enterprise
                                Grade Security</span>
                        </div>

                        <h1 class="text-6xl font-black text-white mb-6 leading-[0.9] tracking-tighter uppercase">
                            Elevate Your <br>
                            <span class="text-amber-500">Culinary</span> Business.
                        </h1>

                        <div class="h-1 w-24 bg-amber-500 mb-6 rounded-full"></div>

                        <p class="text-gray-300 text-lg font-medium leading-relaxed max-w-md opacity-80">
                            Satu platform terintegrasi untuk mengelola pesanan langsung dan daring menggunakan
                            qrcode, ketersediaan meja, cashier dan laporan finansial secara real-time.
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

            {{-- RIGHT SECTION: FORM --}}
            <div class="w-full lg:w-2/5 flex flex-col items-center justify-center p-8 sm:p-20 bg-slate-950 relative">
                <div
                    class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-amber-500/10 blur-[100px] rounded-full pointer-events-none">
                </div>

                <div class="w-full max-w-sm relative z-10">
                    {{-- Mobile Logo --}}
                    <div class="lg:hidden flex items-center gap-3 mb-12 justify-center">
                        <div class="p-2 bg-amber-500 rounded-xl">
                            <i data-lucide="layout-grid" class="text-slate-950 w-5 h-5"></i>
                        </div>
                        <span class="text-lg font-black uppercase tracking-[0.2em] text-white">
                            POS<span class="text-amber-500">PRO</span>
                        </span>
                    </div>

                    <div class="space-y-2 mb-10 text-center lg:text-left">
                        <h3 class="text-3xl font-black text-white tracking-tighter uppercase">Auth Terminal</h3>
                        <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Masukkan kredensial Anda
                        </p>
                    </div>

                    <div class="bg-white/5 border border-white/5 p-2 rounded-3xl backdrop-blur-sm">
                        <div class="bg-slate-900/50 p-8 rounded-[1.8rem]">
                            @yield('form')
                        </div>
                    </div>

                    <div class="mt-12 text-center">
                        <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest leading-loose">
                            Butuh bantuan akses? <br>
                            <a href="#" class="text-amber-500 hover:text-amber-400 transition-colors">Hubungi IT
                                Support Terminal</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        // Alpine.js Global Store — pengganti useNotification() hook
        document.addEventListener('alpine:init', () => {
            Alpine.store('notif', {
                open: false,
                type: 'success',
                title: '',
                message: '',
                set(type, title, message = '') {
                    this.type = type
                    this.title = title
                    this.message = message
                    this.open = true
                    setTimeout(() => this.open = false, 4000)
                }
            })
        })
    </script>

    <script>
        // Init Lucide icons setelah DOM ready
        lucide.createIcons();
    </script>

</body>

</html>
