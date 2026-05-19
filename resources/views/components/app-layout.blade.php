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
            Alpine.data('qrModalData', (orderData) => ({
                isOpen: true, // Anda bisa set false jika ingin dibuka lewat event browser
                order: orderData,
                qrDataUrl: '',

                init() {
                    // Otomatis men-generate QR Code saat modal muncul/di-init
                    if (!this.order?.session?.sessionToken) return;

                    const token = this.order.session.sessionToken;
                    const url =
                        `${window.location.protocol}//${window.location.host}/order/session/${token}`;

                    QRCode.toDataURL(url, {
                        errorCorrectionLevel: 'M',
                        margin: 4,
                        width: 220,
                        color: {
                            dark: '#000000',
                            light: '#ffffff',
                        }
                    }, (err, dataUrl) => {
                        if (!err) {
                            this.qrDataUrl = dataUrl;
                        } else {
                            console.error('QR Generation Error:', err);
                        }
                    });
                },

                formatDate(dateString) {
                    if (!dateString) return '';
                    return new Intl.DateTimeFormat('id-ID', {
                        dateStyle: 'medium',
                        timeStyle: 'short',
                    }).format(new Date(dateString));
                },

                printQR() {
                    let iframe = document.getElementById('print-iframe');
                    if (!iframe) {
                        iframe = document.createElement('iframe');
                        iframe.id = 'print-iframe';
                        Object.assign(iframe.style, {
                            position: 'absolute',
                            width: '0px',
                            height: '0px',
                            border: 'none',
                            visibility: 'hidden',
                        });
                        document.body.appendChild(iframe);
                    }

                    const doc = iframe.contentWindow?.document;
                    if (!doc) return;

                    const printContent = this.$refs.printArea.innerHTML;

                    doc.open();
                    doc.write(`
                        <html>
                          <head>
                            <title>Print Receipt</title>
                            <style>
                              * { box-sizing: border-box; margin: 0; padding: 0; }
                              @page { size: auto; margin: 0; }
                              body {
                                font-family: 'Courier New', Courier, monospace;
                                width: 80mm;
                                padding: 10mm;
                                font-size: 12px;
                                color: #000;
                              }
                              .text-center { text-align: center; }
                              .flex { display: flex; justify-content: space-between; }
                              .font-bold { font-weight: bold; }
                              .uppercase { text-transform: uppercase; }
                              .dashed { border-top: 1px dashed #000; margin: 10px 0; }
                              .w-full { width: 100%; }
                              .absolute, .opacity-10, .bg-\\[url\\(.*?\\)\\] { display: none; }
                            </style>
                          </head>
                          <body>${printContent}</body>
                        </html>
                    `);
                    doc.close();

                    setTimeout(() => {
                        iframe.contentWindow?.focus();
                        iframe.contentWindow?.print();
                    }, 500);
                },

                closeModal() {
                    this.isOpen = false;
                    // Memicu event custom jika ada element luar yang mendengarkan close modal ini
                    this.$dispatch('close-qr-modal');
                }
            }));

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
