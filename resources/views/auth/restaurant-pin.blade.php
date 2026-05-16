<x-auth-layout title="Verify Restaurant Access" subtitle="Enter your restaurant PIN to continue">

    <div x-data="restaurantPin()">

        {{-- PIN Field --}}
        <div class="space-y-2 mb-6">
            <label for="pin" class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 ml-1">
                Restaurant PIN
            </label>

            <div class="relative group">
                <div
                    class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-600 group-focus-within:text-amber-500 transition-colors">
                    <i data-lucide="lock" class="w-[18px] h-[18px]"></i>
                </div>
                <input id="pin" type="password" inputmode="numeric" autofocus x-model="form.pin"
                    placeholder="••••" :class="errors.pin ? 'border-red-500/50' : ''"
                    class="w-full bg-slate-950/50 border border-white/5 py-4 pl-12 pr-4 rounded-2xl text-white font-medium placeholder:text-gray-700 outline-none focus:border-amber-500/50 focus:ring-4 focus:ring-amber-500/5 transition-all"
                    required />
            </div>

            <template x-if="errors.pin">
                <p class="text-[10px] font-black uppercase text-red-500 ml-1 tracking-tighter" x-text="errors.pin"></p>
            </template>
        </div>

        {{-- Submit --}}
        <button type="button" @click="submit" :disabled="processing"
            class="w-full py-5 rounded-2xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black uppercase text-xs tracking-[0.2em] flex items-center justify-center gap-3 transition-all active:scale-95 disabled:opacity-50 shadow-xl shadow-amber-500/20">
            <template x-if="processing">
                <i data-lucide="loader-2" class="w-[18px] h-[18px] animate-spin"></i>
            </template>
            <template x-if="!processing">
                <span class="flex items-center gap-3">
                    Continue
                    <i data-lucide="arrow-right" class="w-[18px] h-[18px]"></i>
                </span>
            </template>
        </button>

    </div>

    <script>
        function restaurantPin() {
            return {
                processing: false,
                errors: {},
                form: {
                    pin: ''
                },

                async submit() {
                    this.processing = true
                    this.errors = {}

                    try {
                        const res = await fetch('/login/restaurant-pin', {
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
                            // Redirect ke login jika device tidak trusted
                            if (data.code === 'not_trusted') {
                                this.$store.notif.set('error', 'Error', data.message)
                                setTimeout(() => window.location.href = '/login', 500)
                                return
                            }

                            this.$store.notif.set('error', 'Error', data.message ?? 'Terjadi kesalahan')

                            if (data.errors) {
                                Object.keys(data.errors).forEach(key => {
                                    this.errors[key] = data.errors[key][0]
                                })
                            }
                            return
                        }

                        // Pengganti router.visit(res.data.redirect)
                        window.location.href = data.redirect

                    } catch (e) {
                        this.$store.notif.set('error', 'Error', 'Ada kesalahan, coba lagi nanti')
                    } finally {
                        this.processing = false
                        lucide.createIcons()
                    }
                }
            }
        }
    </script>

    @slot('footerLink')
        <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest">
            Kembali ke
            <a href="/login" class="text-amber-500 hover:text-amber-400 transition-colors ml-1">Halaman Login</a>
        </p>
    @endslot

</x-auth-layout>
