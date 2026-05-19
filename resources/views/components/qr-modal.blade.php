<!-- 1. Hapus h-[100dvh] agar flex terpusat secara natural -->
<div x-data x-show="$store.qrModal.visible" x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 p-4 backdrop-blur-sm">

    <!-- 2. CONTAINER UTAMA: Ubah h-full & pb-64 menjadi max-h-[85vh] dan overflow-hidden -->
    <div :class="isDark ? 'bg-slate-900 border-white/10' : 'bg-white border-gray-100'"
        class="relative flex max-h-[85vh] w-full max-w-sm flex-col overflow-hidden rounded-[2.5rem] border shadow-2xl transition-all duration-300">

        {{-- Header (Tetap di atas) --}}
        <!-- 3. Tambahkan flex-shrink-0 agar tidak mengecil saat konten di-scroll -->
        <div class="flex-shrink-0 p-4 pb-2 text-center">
            <div class="mb-2 inline-flex">
                <div :class="isDark ? 'bg-amber-500/20 text-amber-500' : 'bg-orange-100 text-orange-600'"
                    class="flex h-12 w-12 items-center justify-center rounded-full">
                    <i data-lucide="qr-code" class="h-6 w-6"></i>
                </div>
            </div>
            <h2 :class="isDark ? 'text-white' : 'text-slate-900'" class="text-lg font-black uppercase tracking-tight">
                Table QR Session</h2>
            <p class="mt-1 text-[10px] font-bold uppercase tracking-widest text-gray-500">Scan to self-order</p>
        </div>

        {{-- Receipt Area (Scrollable) --}}
        <!-- 4. Ganti h-[85%] menjadi flex-1 agar tingginya dinamis mengikuti isi, namun bisa di-scroll ketika menyentuh batas 85vh -->
        <div class="custom-scrollbar flex-1 overflow-y-auto px-8 py-2">
            <div id="qr-print-area" class="relative mb-4 rounded-sm bg-white p-6 text-slate-950 shadow-inner"
                style="font-family: 'Courier New', Courier, monospace">

                <div
                    class="absolute left-0 right-0 top-0 h-1 bg-[url('https://www.transparenttextures.com/patterns/zigzag.png')] opacity-10">
                </div>

                <div class="mb-4 text-center">
                    <h3 class="mb-1 text-lg font-bold leading-none"
                        x-text="($store.qrModal.restaurant || '').toUpperCase()"></h3>
                    <p class="text-[10px] uppercase"
                        x-text="$store.qrModal.order?.order_code || $store.qrModal.order?.orderCode"></p>
                </div>

                <div class="my-3 border-t border-dashed border-slate-300"></div>

                <div class="space-y-1 text-[11px]">
                    <div class="flex justify-between">
                        <span>TABLE</span>
                        <span class="font-bold"
                            x-text="$store.qrModal.order?.table?.table_number || $store.qrModal.order?.table?.tableNumber || 'TA'"></span>
                    </div>
                    <div class="flex justify-between">
                        <span>CASHIER</span>
                        <span class="font-bold uppercase"
                            x-text="$store.qrModal.order?.session?.created_by_user?.name || $store.qrModal.order?.session?.createdByUser?.name || 'CASHIER'"></span>
                    </div>
                    <div class="flex justify-between">
                        <span>DATE</span>
                        <span class="font-bold"
                            x-text="$store.qrModal.order?.session?.started_at ? new Intl.DateTimeFormat('id-ID', { dateStyle: 'medium', timeStyle: 'short' }).format(new Date($store.qrModal.order.session.started_at)) : ''"></span>
                    </div>
                </div>

                <div class="my-3 border-t border-dashed border-slate-300"></div>

                {{-- QR Code Target Render --}}
                <div class="mx-auto -mt-2 inline-block w-full rounded-3xl bg-white text-center">
                    <template x-if="$store.qrModal.qrDataUrl">
                        <img class="mx-auto w-48 transition-all duration-300" :src="$store.qrModal.qrDataUrl"
                            alt="QR Code Session" />
                    </template>
                    <template x-if="!$store.qrModal.qrDataUrl">
                        <div class="animate-pulse py-12 text-xs text-gray-400">Generating Table QR...</div>
                    </template>
                </div>

                <div class="my-3 -mt-2 border-t border-dashed border-slate-300"></div>
                <p class="text-center text-[10px] font-bold uppercase tracking-tighter">Thank you for choosing us!</p>
                <p class="text-center text-[10px] uppercase">Powered by AnoPos</p>

                {{-- Decorative Scissors --}}
                <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 bg-white px-1 text-slate-300">
                    <i data-lucide="scissors" class="h-3.5 w-3.5"></i>
                </div>
            </div>
        </div>

        {{-- Actions Buttons (Tetap di bawah) --}}
        <!-- 5. Hapus absolute bottom-0, ubah posisi menjadi layouting flex normal dengan flex-shrink-0 -->
        <div :class="isDark ? 'bg-slate-900' : 'bg-white'"
            class="flex flex-shrink-0 gap-4 border-t border-transparent p-8 pt-4">
            <button @click="printQR()"
                :class="isDark ? 'bg-emerald-600 text-white hover:bg-emerald-500' : 'bg-green-600 text-white hover:bg-green-500'"
                class="flex flex-1 items-center justify-center gap-2 rounded-2xl py-4 text-[10px] font-black uppercase tracking-widest transition-all active:scale-95">
                <i data-lucide="printer" class="h-4 w-4"></i>
                Print QR
            </button>
            <button @click="$store.qrModal.close()"
                :class="isDark ? 'bg-white/5 text-gray-400 hover:bg-white/10' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                class="rounded-2xl px-6 py-4 text-[10px] font-black uppercase tracking-widest transition-all">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('qrModal', {
            visible: false,
            restaurant: '',
            order: null,
            qrDataUrl: '',

            open(order, restaurantName) {
                this.order = order;
                this.restaurant = restaurantName || order?.restaurant || 'AnomaliPOS';
                this.qrDataUrl = ''; // Reset data url lama
                this.visible = true;
            },

            close() {
                this.visible = false;
            }
        });

        // Reaktif meng-generate QR Code setiap kali store 'qrModal.order' berubah/terisi
        Alpine.effect(() => {
            const order = Alpine.store('qrModal').order;
            const isVisible = Alpine.store('qrModal').visible;

            // Ambil data token session (antisipasi camelCase / snake_case dari backend)
            const token = order?.session?.session_token || order?.session?.sessionToken;

            if (isVisible && token) {
                const hostUrl =
                    `${window.location.protocol}//${window.location.host}/order/session/${token}`;

                // Generate QR Code ke base64 url string
                window.QRCode.toDataURL(hostUrl, {
                    errorCorrectionLevel: 'M',
                    margin: 2,
                    width: 240,
                    color: {
                        dark: '#000000',
                        light: '#ffffff',
                    }
                }, (err, url) => {
                    if (!err) {
                        Alpine.store('qrModal').qrDataUrl = url;
                    } else {
                        console.error('Gagal generate QR:', err);
                    }
                });
            }
        });
    });

    function printQR() {
        let iframe = document.getElementById('print-iframe');
        if (!iframe) {
            iframe = document.createElement('iframe');
            iframe.id = 'print-iframe';
            Object.assign(iframe.style, {
                position: 'absolute',
                width: '0px',
                height: '0px',
                border: 'none',
                visibility: 'hidden'
            });
            document.body.appendChild(iframe);
        }

        const doc = iframe.contentWindow?.document;
        if (!doc) return;


        // Mengambil data teks langsung dari elemen aktif agar akurat tanpa membawa logic Alpine.js
        const store = Alpine.store('qrModal');
        console.log(store.order)
        const restaurantName = (store.restaurant || '').toUpperCase();
        const orderCode = store.order?.order_code || store.order?.orderCode || '';
        const tableNumber = store.order?.table?.table_number || store.order?.table?.tableNumber || 'TA';
        const cashierName = (store.order?.session?.created_by_user?.name || store.order?.session?.createdByUser?.name ||
            'CASHIER').toUpperCase();

        let dateStr = '';
        if (store.order?.session?.started_at) {
            dateStr = new Intl.DateTimeFormat('id-ID', {
                dateStyle: 'medium',
                timeStyle: 'short'
            }).format(new Date(store.order.session.started_at));
        }

        const qrImageSrc = store.qrDataUrl || '';

        doc.open();
        doc.write(`
        <html>
        <head>
            <title>Print QR Session</title>
            <style>
                /* Reset & Thermal Paper Page Setup */
                * { box-sizing: border-box; margin: 0; padding: 0; }
                @page { size: 80mm auto; margin: 0; }

                body {
                    font-family: 'Courier New', Courier, monospace;
                    width: 72mm; /* Area cetak bersih untuk printer 80mm */
                    padding: 4mm 2mm;
                    font-size: 12px;
                    color: #000;
                    line-height: 1.4;
                }

                /* Utility Classes */
                .text-center { text-align: center; }
                .text-lg { font-size: 16px; }
                .text-sm { font-size: 10px; }
                .font-bold { font-weight: bold; }
                .mb-1 { margin-bottom: 2px; }
                .mb-2 { margin-bottom: 4px; }
                .mb-4 { margin-bottom: 12px; }
                .mt-2 { margin-top: 6px; }

                /* Layouting Row */
                .flex-row {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 3px;
                }

                /* Divider Garis Putus-putus khas Struk */
                .dashed-line {
                    border-top: 1px dashed #000;
                    margin: 8px 0;
                    width: 100%;
                }

                /* QR Code Wrapper */
                .qr-container {
                    text-align: center;
                    margin: 12px 0;
                }
                .qr-container img {
                    width: 45mm; /* Ukuran ideal QR Code di kertas thermal */
                    height: 45mm;
                    display: inline-block;
                }

                .tracking-widest { letter-spacing: 1px; }
                .tracking-tighter { letter-spacing: -0.5px; }
            </style>
        </head>
        <body>
            <!-- Header Struk -->
            <div class="text-center mb-4">
                <h3 class="text-lg font-bold mb-1">${restaurantName}</h3>
                <p class="text-sm">${orderCode}</p>
            </div>

            <div class="dashed-line"></div>

            <!-- Metadata / Info Sesi -->
            <div class="mb-2">
                <div class="flex-row">
                    <span>TABLE</span>
                    <span class="font-bold">${tableNumber}</span>
                </div>
                <div class="flex-row">
                    <span>CASHIER</span>
                    <span class="font-bold">${cashierName}</span>
                </div>
                <div class="flex-row">
                    <span>DATE</span>
                    <span class="font-bold">${dateStr}</span>
                </div>
            </div>

            <div class="dashed-line"></div>

            <!-- Area QR Code -->
            <div class="qr-container">
                ${qrImageSrc ? `<img src="${qrImageSrc}" alt="QR" />` : '<p style="font-size:10px;">Gagal memuat QR Code</p>'}
            </div>

            <div class="dashed-line"></div>

            <!-- Footer Struk -->
            <div class="text-center mt-2">
                <p class="font-bold text-sm tracking-tighter" style="text-transform: uppercase;">Thank you for choosing us!</p>
                <p class="text-sm" style="font-size: 9px; margin-top: 2px;">Powered by AnoPos</p>
            </div>
        </body>
        </html>
    `);
        doc.close();

        setTimeout(() => {
            iframe.contentWindow?.focus();
            iframe.contentWindow?.print();
        }, 500);
    }
</script>
