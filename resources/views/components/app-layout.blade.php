@props([
    'title' => 'AnomaliPOS',
])

<!DOCTYPE html>
<html lang="id" x-data="globalAppManager()" :class="{ 'dark': isDark }" :data-theme="isDark ? 'dark' : 'light'">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>

    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    {{ $head ?? '' }}

    <style>
        [x-cloak] {
            display: none !important;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body x-cloak class="antialiased transition-colors duration-500"
    :class="isDark ? 'bg-slate-950 text-gray-100' : 'bg-[#FDFBF7] text-gray-900'">

    {{-- Notification Stack Global --}}
    <x-notification />

    {{-- Slot langsung merujuk ke dimensi body tanpa terhalang div antara --}}
    {{ $slot }}

    <script>
        function globalAppManager() {
            return {
                isDark: false,
                isFullscreen: false,

                init() {
                    const savedTheme = localStorage.getItem('theme');
                    if (savedTheme !== null) {
                        this.isDark = savedTheme === 'dark';
                    } else {
                        this.isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    }

                    this.$watch('isDark', value => {
                        localStorage.setItem('theme', value ? 'dark' : 'light');
                    });

                    window.addEventListener('toggle-theme', () => {
                        this.isDark = !this.isDark;
                    });

                    document.addEventListener('fullscreenchange', () => {
                        this.isFullscreen = !!document.fullscreenElement;
                    });

                    this.$nextTick(() => lucide.createIcons());
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
            }
        }

        document.addEventListener('alpine:init', () => {
            Alpine.store('notifs', {
                items: [],
                _counter: 0,

                set(type, title, message = '') {
                    const id = ++this._counter;
                    this.items.push({
                        id,
                        type,
                        title,
                        message,
                        visible: true
                    });
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

            Alpine.store('notif', {
                set(type, title, message = '') {
                    Alpine.store('notifs').set(type, title, message);
                }
            });
        });

        document.addEventListener('alpine:initialized', () => lucide.createIcons());
        document.addEventListener('alpine:mutated', () => lucide.createIcons());
    </script>

    {{ $scripts ?? '' }}
</body>

</html>
