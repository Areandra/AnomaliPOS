@props([
    'title' => 'AnomaliPOS',
])

<!DOCTYPE html>
<html lang="id" x-data="globalAppManager()" :class="{ 'dark': isDark }" :data-theme="isDark ? 'dark' : 'light'">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

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

        .floating-btn {
            position: fixed;
            z-index: 9999;
            cursor: grab;
            user-select: none;
            touch-action: none;
        }

        .floating-btn:active {
            cursor: grabbing;
        }

        .floating-btn.snapping {
            transition: left 0.3s ease-out;
        }
    </style>
</head>

<body x-cloak class="antialiased transition-colors duration-500"
    :class="isDark ? 'bg-slate-950 text-gray-100' : 'bg-[#FDFBF7] text-gray-900'">

    {{-- Notification Stack Global --}}
    <x-notification />

    <x-ai-assistant-panel />

    {{-- Floating Button --}}
    <div x-data="floatingBtn" x-init="init" class="floating-btn"
        :style="'left: ' + x + 'px; top: ' + y + 'px;'" @mousedown.prevent="startDrag" @touchstart.prevent="startDrag"
        @click="onClick">

        <button x-show="disableButton"
            class="flex h-14 w-14 items-center justify-center rounded-full shadow-lg transition-transform hover:scale-110"
            :class="isDark ? 'bg-amber-500 text-slate-950' : 'bg-amber-500 text-white'">
            <i data-lucide="sparkles" class="h-6 w-6"></i>
        </button>
    </div>

    {{ $slot }}

    <script>
        // ===== CARA MUDAH DAFTAR EVENT =====
        // Cukup pakai ini di komponen manapun:
        // window.addEventListener('floating-btn-click', (e) => { ... })

        function floatingBtn() {
            return {
                x: window.innerWidth - 80,
                y: window.innerHeight - 100,
                dragging: false,
                startX: 0,
                startY: 0,
                moved: false,
                disableButton: false,

                init() {
                    // Load posisi tersimpan
                    const saved = localStorage.getItem('fbPos');
                    if (saved) {
                        const pos = JSON.parse(saved);
                        this.x = pos.x;
                        this.y = pos.y;
                    }

                    const routesAllowedAi = ['/cashier', '/dashboard', '/shifts', '/kitchen'];

                    this.disableButton = routesAllowedAi.some(route =>
                        window.location.pathname === route
                    );

                    // Global event listeners
                    window.addEventListener('mousemove', (e) => this.drag(e));
                    window.addEventListener('mouseup', () => this.stopDrag());
                    window.addEventListener('touchmove', (e) => this.drag(e), {
                        passive: false
                    });
                    window.addEventListener('touchend', () => this.stopDrag());
                    window.addEventListener('open-ai-modal-closed', (e) => this.disableButton = e.detail);
                    window.addEventListener('open-ai-modal-opened', (e) => this.disableButton = e.detail);
                },

                startDrag(e) {
                    this.dragging = true;
                    this.moved = false;

                    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                    const clientY = e.touches ? e.touches[0].clientY : e.clientY;

                    this.startX = clientX - this.x;
                    this.startY = clientY - this.y;

                    this.$el.classList.remove('snapping');
                },

                drag(e) {
                    if (!this.dragging) return;

                    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                    const clientY = e.touches ? e.touches[0].clientY : e.clientY;

                    let newX = clientX - this.startX;
                    let newY = clientY - this.startY;

                    // Batasi dalam viewport
                    newX = Math.max(0, Math.min(newX, window.innerWidth - 56));
                    newY = Math.max(0, Math.min(newY, window.innerHeight - 56));

                    if (Math.abs(newX - this.x) > 3 || Math.abs(newY - this.y) > 3) {
                        this.moved = true;
                    }

                    this.x = newX;
                    this.y = newY;

                    if (e.touches) e.preventDefault();
                },

                stopDrag() {
                    if (!this.dragging) return;
                    this.dragging = false;

                    // SNAP KE POJOK HORIZONTAL TERDEKAT
                    const btnCenter = this.x + 28; // center button
                    const screenCenter = window.innerWidth / 2;

                    this.$el.classList.add('snapping');

                    if (btnCenter < screenCenter) {
                        this.x = 10; // snap kiri
                    } else {
                        this.x = window.innerWidth - 66; // snap kanan
                    }

                    // Simpan posisi
                    localStorage.setItem('fbPos', JSON.stringify({
                        x: this.x,
                        y: this.y
                    }));

                    // ==== EVENT SIMPLE ====
                    // Kirim event yang bisa didengar siapa saja
                    window.dispatchEvent(new CustomEvent('floating-btn-drag-end', {
                        detail: {
                            x: this.x,
                            y: this.y,
                            moved: this.moved
                        }
                    }));
                },

                onClick(e) {
                    if (this.moved) {
                        e.preventDefault();
                        e.stopPropagation();
                        return;
                    }

                    // ==== EVENT SIMPLE ====
                    // Event saat klik, tinggal pakai ini di komponen lain
                    window.dispatchEvent(new CustomEvent('floating-btn-click'));
                }
            }
        }

        function openAiModal(module, payload) {
            window.dispatchEvent(new CustomEvent('open-ai-modal', {
                detail: {
                    module,
                    payload
                }
            }));
        }

        function globalAppManager() {
            return {
                isDark: false,
                isFullscreen: false,

                init() {
                    const savedTheme = localStorage.getItem('theme');
                    this.isDark = savedTheme ? savedTheme === 'dark' :
                        window.matchMedia('(prefers-color-scheme: dark)').matches;

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
                        document.documentElement.requestFullscreen();
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
