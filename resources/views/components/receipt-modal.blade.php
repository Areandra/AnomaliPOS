<div
    x-data
    x-show="$store.receipt.visible"
    x-cloak
    class="fixed h-[100dvh] inset-0 bg-slate-950/80 backdrop-blur-sm flex justify-center items-center z-50 py-8"
>
    <div
        :class="isDark ? 'bg-slate-900 border-white/10' : 'bg-white border-gray-100'"
        class="relative w-full h-full pb-64 max-w-sm rounded-[2.5rem] shadow-2xl overflow-hidden border"
    >
        {{-- Header --}}
        <div class="p-4 pb-4 text-center">
            <div class="inline-flex mb-4">
                <div class="w-32 h-32 flex items-center justify-center">
                    <i data-lucide="check-circle-2" class="w-20 h-20 text-emerald-500"></i>
                </div>
            </div>
            <h2 :class="isDark ? 'text-white' : 'text-slate-900'"
                class="text-xl font-black uppercase tracking-tight"
                x-text="$store.receipt.title || 'Payment Success'"></h2>
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-1">Transaction has been finalized</p>
        </div>

        {{-- Receipt --}}
        <div class="h-[85%] px-8 overflow-y-auto">
            <div id="receipt-print-area"
                 class="bg-white text-slate-950 p-6 rounded-sm shadow-inner relative"
                 style="font-family: 'Courier New', Courier, monospace">

                <div class="text-center mb-4">
                    <h3 class="font-bold text-lg leading-none mb-1"
                        x-text="($store.receipt.restaurant || '').toUpperCase()"></h3>
                    <p class="text-[10px] uppercase" x-text="$store.receipt.order?.order_code"></p>
                </div>

                <div class="border-t border-dashed border-slate-300 my-3"></div>

                <div class="text-[11px] space-y-1">
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
                        <span x-text="$store.receipt.payment ? new Intl.DateTimeFormat('id-ID', { dateStyle: 'medium', timeStyle: 'short' }).format(new Date($store.receipt.payment.created_at)) : ''"></span>
                    </div>
                </div>

                <div class="border-t border-dashed border-slate-300 my-3"></div>

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

                <div class="border-t border-dashed border-slate-300 my-3"></div>

                <div class="text-[11px] space-y-1">
                    <div class="flex justify-between">
                        <span>SUBTOTAL</span>
                        <span x-text="formatRp($store.receipt.order?.subtotal)"></span>
                    </div>
                    <div class="flex justify-between">
                        <span>TAX (10%)</span>
                        <span x-text="formatRp($store.receipt.order?.tax)"></span>
                    </div>
                    <div class="flex justify-between font-bold text-sm pt-2">
                        <span>TOTAL</span>
                        <span x-text="formatRp($store.receipt.order?.total)"></span>
                    </div>
                    <div class="border-t border-dashed border-slate-300 my-3"></div>
                    <div class="flex justify-between pt-2">
                        <span>CASH</span>
                        <span x-text="formatRp($store.receipt.payment?.amount)"></span>
                    </div>
                    <div class="flex justify-between">
                        <span>CHANGE</span>
                        <span x-text="formatRp($store.receipt.payment?.change)"></span>
                    </div>
                </div>

                <div class="border-t border-dashed border-slate-300 my-3"></div>
                <p class="text-center text-[10px] font-bold uppercase tracking-tighter">Thank you for dining with us!</p>
                <p class="text-center text-[10px] uppercase">Powered by AnoPos</p>
            </div>
        </div>

        {{-- Actions --}}
        <div :class="isDark ? 'bg-slate-900' : 'bg-white'" class="p-8 pt-4 flex gap-4">
            <button @click="printReceipt()"
                :class="isDark ? 'bg-amber-500 text-slate-950 hover:bg-amber-400' : 'bg-slate-900 text-white hover:bg-slate-800'"
                class="flex-1 flex items-center justify-center gap-2 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all active:scale-95">
                <i data-lucide="printer" class="w-4 h-4"></i>
                Print Struk
            </button>
            <button @click="$store.receipt.close()"
                :class="isDark ? 'bg-white/5 text-gray-400 hover:bg-white/10' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                class="px-6 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all">
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
            this.payment    = pay
            this.restaurant = restaurant?.name || restaurant || ''
            this.order      = pay.order
            this.title      = 'Receipt Information'
            this.visible    = true
        },

        close() {
            this.visible = false
        }
    })
})

function formatRp(v) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency', currency: 'IDR', minimumFractionDigits: 0
    }).format(v || 0)
}

function printReceipt() {
    let iframe = document.getElementById('print-iframe')
    if (!iframe) {
        iframe = document.createElement('iframe')
        iframe.id = 'print-iframe'
        Object.assign(iframe.style, { position: 'absolute', width: '0px', height: '0px', border: 'none', visibility: 'hidden' })
        document.body.appendChild(iframe)
    }
    const doc = iframe.contentWindow?.document
    if (!doc) return
    doc.open()
    doc.write(`
        <html>
        <head>
            <title>Print Receipt</title>
            <style>
                * { box-sizing: border-box; margin: 0; padding: 0; }
                @page { size: auto; margin: 0; }
                body { font-family: 'Courier New', Courier, monospace; width: 80mm; padding: 10mm; font-size: 12px; color: #000; }
                .text-center { text-align: center; }
                .flex { display: flex; justify-content: space-between; }
                .bold { font-weight: bold; }
                .dashed { border-top: 1px dashed #000; margin: 10px 0; }
                .title { font-size: 16px; margin-bottom: 5px; }
            </style>
        </head>
        <body>${document.getElementById('receipt-print-area')?.innerHTML || ''}</body>
        </html>
    `)
    doc.close()
    setTimeout(() => { iframe.contentWindow?.focus(); iframe.contentWindow?.print() }, 500)
}
</script>
