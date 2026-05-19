<div x-data x-show="$store.receipt.visible" x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 p-4 backdrop-blur-sm">

    <!-- 1. CONTAINER UTAMA: Set maksimal tinggi 85% dari layar (max-h-[85vh]) dan overflow-hidden -->
    <div :class="isDark ? 'bg-slate-900 border-white/10' : 'bg-white border-gray-100'"
        class="relative flex max-h-[85vh] w-full max-w-sm flex-col overflow-hidden rounded-[2.5rem] border shadow-2xl">

        {{-- Header --}}
        <!-- Diperkecil sedikit padding & marginnya agar hemat ruang saat item banyak -->
        <div class="flex-shrink-0 p-4 pb-2 text-center">
            <div class="mb-2 inline-flex">
                <div class="flex h-20 w-20 items-center justify-center">
                    <i data-lucide="check-circle-2" class="h-14 w-14 text-emerald-500"></i>
                </div>
            </div>
            <h2 :class="isDark ? 'text-white' : 'text-slate-900'" class="text-xl font-black uppercase tracking-tight"
                x-text="$store.receipt.title || 'Payment Success'"></h2>
            <p class="mt-1 text-[10px] font-bold uppercase tracking-widest text-gray-500">Transaction has been finalized
            </p>
        </div>

        {{-- Receipt --}}
        <!-- 2. WRAPPER STRUK: Menggunakan flex-1 dan overflow-y-auto -->
        <!-- Jika item sedikit, dia akan pas menyesuaikan isi. Jika item sangat banyak hingga modal menyentuh tinggi 85vh, bagian ini akan otomatis bisa di-scroll -->
        <div class="flex-1 overflow-y-auto px-8 py-2">
            <div id="receipt-print-area" class="relative rounded-sm bg-white p-6 text-slate-950 shadow-inner"
                style="font-family: 'Courier New', Courier, monospace">

                <div class="mb-4 text-center">
                    <h3 class="mb-1 text-lg font-bold leading-none"
                        x-text="($store.receipt.restaurant || '').toUpperCase()"></h3>
                    <p class="text-[10px] uppercase" x-text="$store.receipt.order?.order_code"></p>
                </div>

                <div class="my-3 border-t border-dashed border-slate-300"></div>

                <div class="space-y-1 text-[11px]">
                    <div class="flex justify-between">
                        <span>TABLE</span>
                        <span class="font-bold" x-text="$store.receipt.order?.table?.tableNumber || 'TA'"></span>
                    </div>
                    <div class="flex justify-between">
                        <span>CASHIER</span>
                        <span class="font-bold uppercase" x-text="$store.receipt.payment?.created_by?.name"></span>
                    </div>
                    <div class="flex justify-between">
                        <span>DATE</span>
                        <span
                            x-text="$store.receipt.payment ? new Intl.DateTimeFormat('id-ID', { dateStyle: 'medium', timeStyle: 'short' }).format(new Date($store.receipt.payment.created_at)) : ''"></span>
                    </div>
                </div>

                <div class="my-3 border-t border-dashed border-slate-300"></div>

                <div class="space-y-2">
                    <template x-for="item in ($store.receipt.order?.items || [])" :key="item.id">
                        <div class="text-[11px]">
                            <div class="flex justify-between font-bold">
                                <span class="uppercase" x-text="item.menu_item.name"></span>
                                <span x-text="formatRp(item.quantity * item.price)"></span>
                            </div>
                            <div class="text-[10px] opacity-60">
                                <span x-text="item.quantity"></span> x <span x-text="formatRp(item.price)"></span>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="my-3 border-t border-dashed border-slate-300"></div>

                <div class="space-y-1 text-[11px]">
                    <div class="flex justify-between">
                        <span>SUBTOTAL</span>
                        <span x-text="formatRp($store.receipt.order?.subtotal)"></span>
                    </div>
                    <div class="flex justify-between">
                        <span>TAX (10%)</span>
                        <span x-text="formatRp($store.receipt.order?.tax)"></span>
                    </div>
                    <div class="flex justify-between pt-2 text-sm font-bold">
                        <span>TOTAL</span>
                        <span x-text="formatRp($store.receipt.order?.total)"></span>
                    </div>
                    <div class="my-3 border-t border-dashed border-slate-300"></div>
                    <div class="flex justify-between pt-2">
                        <span>CASH</span>
                        <span x-text="formatRp($store.receipt.payment?.amount)"></span>
                    </div>
                    <div class="flex justify-between">
                        <span>CHANGE</span>
                        <span x-text="formatRp($store.receipt.payment?.change)"></span>
                    </div>
                </div>

                <div class="my-3 border-t border-dashed border-slate-300"></div>
                <p class="text-center text-[10px] font-bold uppercase tracking-tighter">Thank you for dining with us!
                </p>
                <p class="text-center text-[10px] uppercase">Powered by AnoPos</p>
            </div>
        </div>

        {{-- Actions --}}
        <!-- 3. Ditambahkan flex-shrink-0 agar tombol aksi tidak ikut mengecil atau terpotong saat scroll terjadi -->
        <div :class="isDark ? 'bg-slate-900' : 'bg-white'" class="flex flex-shrink-0 gap-4 p-8 pt-4">
            <button @click="printReceipt()"
                :class="isDark ? 'bg-amber-500 text-slate-950 hover:bg-amber-400' : 'bg-slate-900 text-white hover:bg-slate-800'"
                class="flex flex-1 items-center justify-center gap-2 rounded-2xl py-4 text-[10px] font-black uppercase tracking-widest transition-all active:scale-95">
                <i data-lucide="printer" class="h-4 w-4"></i>
                Print Struk
            </button>
            <button @click="$store.receipt.close()"
                :class="isDark ? 'bg-white/5 text-gray-400 hover:bg-white/10' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                class="rounded-2xl px-6 py-4 text-[10px] font-black uppercase tracking-widest transition-all">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('receipt', {
            visible: false,
            title: '',
            payment: null,
            restaurant: '',
            order: null,

            open(pay, restaurant, shift) {
                this.payment = pay
                this.restaurant = restaurant?.name || restaurant || ''
                this.order = pay.order
                this.title = 'Receipt Information'
                this.visible = true
            },

            close() {
                this.visible = false
            }
        })
    })

    function formatRp(v) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(v || 0)
    }

    function printReceipt() {
        let iframe = document.getElementById('print-iframe')

        if (!iframe) {
            iframe = document.createElement('iframe')

            Object.assign(iframe.style, {
                position: 'fixed',
                right: '0',
                bottom: '0',
                width: '0',
                height: '0',
                border: '0'
            })

            iframe.id = 'print-iframe'
            document.body.appendChild(iframe)
        }

        const receipt = document.getElementById('receipt-print-area')

        if (!receipt) return

        const doc = iframe.contentWindow.document

        doc.open()

        doc.write(`
        <html>
        <head>
            <title>Receipt</title>

            <script src="https://cdn.tailwindcss.com"><\/script>

            <style>
                @page {
                    size: 80mm auto;
                    margin: 0;
                }

                body {
                    margin: 0;
                    padding: 0;
                    background: white;
                }

                #receipt-print-area {
                    width: 80mm;
                    padding: 12px;
                    color: black;
                }
            </style>
        </head>

        <body>
            ${receipt.outerHTML}
        </body>
        </html>
    `)

        doc.close()

        iframe.onload = () => {
            iframe.contentWindow.focus()
            iframe.contentWindow.print()
        }
    }
</script>
