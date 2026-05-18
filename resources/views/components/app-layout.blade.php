@props([
    'title' => 'AnoPos',
])

<!DOCTYPE html>
<html lang="id" x-data="globalAppManager()" :class="{ 'dark': isDark }" :data-theme="isDark ? 'dark' : 'light'">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    {{ $head ?? '' }}

    <style>
        [x-cloak] { display: none !important; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body class="antialiased transition-colors duration-500"
    :class="isDark ? 'bg-slate-950 text-gray-100' : 'bg-[#FDFBF7] text-gray-900'">

    <div x-cloak>
        {{-- Notification Stack Global — satu-satunya instance, tidak perlu duplikasi di child layout --}}
        <x-notification />

        {{-- Slot tempat layout admin, cashier, atau auth merender strukturnya --}}
        {{ $slot }}
    </div>

    <script>
        // =========================================================
        // SATU-SATUNYA TEMPAT Alpine store & globalAppManager didefinisikan.
        // auth-layout, admin-layout, dan cashier-layout TIDAK perlu
        // menduplikasi ini karena mereka selalu dibungkus x-app-layout.
        // =========================================================
        document.addEventListener('alpine:init', () => {

            // --- Store: notifikasi stack (multi-notif) ---
            Alpine.store('notifs', {
                items: [],
                _counter: 0,

                set(type, title, message = '') {
                    const id = ++this._counter;
                    this.items.push({ id, type, title, message, visible: true });
                    setTimeout(() => this.dismiss(id), 4000);
                },

                dismiss(id) {
                    const item = this.items.find(n => n.id === id);
                    if (item) item.visible = false;
                    setTimeout(() => {
                        this.items = this.items.filter(n => n.id !== id);
                    }, 300);
                }
            });

            // --- Store: alias pendek agar kode lama ($store.notif.set) tidak error ---
            Alpine.store('notif', {
                set(type, title, message = '') {
                    Alpine.store('notifs').set(type, title, message);
                }
            });

            // --- Store: tema & fullscreen — bisa diakses dari mana saja via $store.app ---
            Alpine.store('app', {
                isDark: false,
                isFullscreen: false,

                init() {
                    const saved = localStorage.getItem('theme');
                    this.isDark = saved !== null
                        ? saved === 'dark'
                        : window.matchMedia('(prefers-color-scheme: dark)').matches;
                },

                toggleTheme() {
                    this.isDark = !this.isDark;
                    localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
                },

                toggleFullscreen() {
                    if (!document.fullscreenElement) {
                        document.documentElement.requestFullscreen().catch(err => {
                            console.error(`Gagal Fullscreen: ${err.message}`);
                        });
                    } else {
                        document.exitFullscreen();
                    }
                }
            });

            Alpine.store('app').init();
        });

        // --- Komponen root: membaca dari $store.app agar sinkron ---
        function globalAppManager() {
            return {
                get isDark() { return Alpine.store('app').isDark; },
                get isFullscreen() { return Alpine.store('app').isFullscreen; },

                init() {
                    // Sync isDark ke html class setiap store berubah
                    this.$watch(() => Alpine.store('app').isDark, () => {});

                    // Listen event legacy (window toggle-theme) agar komponen lama tetap berfungsi
                    window.addEventListener('toggle-theme', () => Alpine.store('app').toggleTheme());

                    // Sync isFullscreen dari browser
                    document.addEventListener('fullscreenchange', () => {
                        Alpine.store('app').isFullscreen = !!document.fullscreenElement;
                    });

                    this.$nextTick(() => lucide.createIcons());
                },

                toggleFullscreen() {
                    Alpine.store('app').toggleFullscreen();
                }
            }
        }

        document.addEventListener('alpine:initialized', () => lucide.createIcons());
        document.addEventListener('alpine:mutated', () => lucide.createIcons());
    </script>

    {{ $scripts ?? '' }}
</body>

</html>
