<x-app-layout title="Reset PIN Restoran">

    <x-auth-page glow-from="bg-blue-900" glow-to="bg-indigo-900">
        <div x-data="resetPin({{ json_encode(['restaurantId' => $restaurantId]) }})" x-init="init()" class="w-full text-center">

            {{-- ICON STATUS --}}
            <div class="flex justify-center mb-8">
                <div :class="$store.theme.isDark ? 'bg-blue-500/10 border-blue-500/20 text-blue-500' :
                    'bg-blue-50 border-blue-100 text-blue-600'"
                    class="relative h-24 w-24 rounded-[2.5rem] flex items-center justify-center shadow-2xl border">
                    <i data-lucide="key-round" class="w-12 h-12 animate-bounce"></i>
                    <div :class="$store.theme.isDark ? 'bg-slate-800 text-blue-500' : 'bg-white text-blue-600'"
                        class="absolute -bottom-2 -right-2 h-10 w-10 rounded-2xl flex items-center justify-center shadow-lg rotate-12">
                        <i data-lucide="shield-check" class="w-5 h-5"></i>
                    </div>
                </div>
            </div>

            {{-- HEADER --}}
            <div class="text-center mb-8">
                <h1 class="text-4xl font-black uppercase tracking-tighter italic leading-none mb-4">
                    Reset <span :class="$store.theme.isDark ? 'text-blue-500' : 'text-blue-600'">PIN</span>
                </h1>
                <div class="h-1 w-12 mx-auto rounded-full mb-6 bg-blue-500/20"></div>
                <p class="text-[10px] font-black uppercase tracking-[0.2em] mb-2 opacity-80">
                    Outlet: {{ $restaurantName }}
                </p>
                <p class="text-xs font-medium leading-relaxed opacity-50 italic px-4">
                    Masukkan 6 digit PIN baru untuk keamanan transaksi kasir Anda.
                </p>
            </div>

            {{-- FORM --}}
            <div class="space-y-4 text-left">

                {{-- PIN Field --}}
                <div class="space-y-2">
                    <div class="relative">
                        <input type="password" maxlength="6" x-model="form.pin"
                            @input="form.pin = $event.target.value.replace(/\D/g, '')" placeholder="PIN BARU"
                            :class="errors.pin ? 'border-red-500' : 'border-slate-500/20 focus:border-blue-500'"
                            class="w-full bg-transparent border-2 px-4 py-4 rounded-2xl outline-none transition-all font-mono text-center text-2xl tracking-[0.5em] placeholder:tracking-normal placeholder:text-xs" />
                        <i data-lucide="lock"
                            class="absolute left-4 top-1/2 -translate-y-1/2 opacity-20 w-[18px] h-[18px]"></i>
                    </div>
                    <template x-if="errors.pin">
                        <p class="text-red-500 text-[10px] font-bold uppercase text-center" x-text="errors.pin"></p>
                    </template>
                </div>

                {{-- Confirm PIN Field --}}
                <div class="space-y-2">
                    <div class="relative">
                        <input type="password" maxlength="6" x-model="form.pin_confirmation"
                            @input="form.pin_confirmation = $event.target.value.replace(/\D/g, '')"
                            placeholder="KONFIRMASI PIN"
                            :class="errors.pin_confirmation ? 'border-red-500' : 'border-slate-500/20 focus:border-blue-500'"
                            class="w-full bg-transparent border-2 px-4 py-4 rounded-2xl outline-none transition-all font-mono text-center text-2xl tracking-[0.5em] placeholder:tracking-normal placeholder:text-xs" />
                        <i data-lucide="check-circle-2"
                            class="absolute left-4 top-1/2 -translate-y-1/2 opacity-20 w-[18px] h-[18px]"></i>
                    </div>
                    <template x-if="errors.pin_confirmation">
                        <p class="text-red-500 text-[10px] font-bold uppercase text-center"
                            x-text="errors.pin_confirmation"></p>
                    </template>
                </div>

                {{-- Submit --}}
                <button type="button" @click="submit" :disabled="processing"
                    :class="processing
                        ?
                        'opacity-50 cursor-not-allowed bg-blue-600 text-white' :
                        'bg-blue-600 hover:bg-blue-500 text-white shadow-blue-500/40 hover:shadow-blue-500/60'"
                    class="w-full py-4 rounded-2xl font-black uppercase tracking-widest text-sm transition-all active:scale-95 shadow-xl text-center">
                    <span x-text="processing ? 'Processing...' : 'Update PIN'"></span>
                </button>

            </div>
        </div>
    </x-auth-page>

</x-app-layout>

<script>
    function resetPin(initialData) {
        return {
            processing: false,
            errors: {},
            form: {
                restaurantId: initialData.restaurantId,
                pin: '',
                pin_confirmation: '',
            },

            init() {
                // Re-inisialisasi Lucide Icons jika diperlukan di dalam komponen
                lucide.createIcons()
            },

            async submit() {
                this.processing = true
                this.errors = {}

                try {
                    const res = await fetch('/reset-pin', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify(this.form)
                    })

                    const data = await res.json()

                    if (!res.ok) {
                        if (data.errors) {
                            Object.keys(data.errors).forEach(key => {
                                this.errors[key] = data.errors[key][0]
                            })
                        }
                        return
                    }

                    this.$store.notif.set('success', 'Berhasil', 'PIN berhasil diperbarui')
                    setTimeout(() => window.location.href = '/login', 1500)

                } catch (e) {
                    this.$store.notif.set('error', 'Error', 'Ada kesalahan, coba lagi nanti')
                } finally {
                    this.processing = false
                }
            }
        }
    }
</script>
