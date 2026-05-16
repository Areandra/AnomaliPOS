<x-auth-layout title="Reset Password" subtitle="Masukkan email untuk tautan pemulihan">

    <div x-data="resetForm()">
        {{-- Email Field --}}
        <div class="space-y-2 mb-6">
            <label for="email" class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 ml-1">
                System Identifier / Email
            </label>
            <div class="relative group">
                <div
                    class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-600 group-focus-within:text-amber-500 transition-colors">
                    <i data-lucide="mail" class="w-[18px] h-[18px]"></i>
                </div>
                <input id="email" type="email" x-model="form.email" placeholder="example@posqir.sys"
                    class="w-full bg-slate-950/50 border border-white/5 py-4 pl-12 pr-4 rounded-2xl text-white font-medium placeholder:text-gray-700 outline-none focus:border-amber-500/50 focus:ring-4 focus:ring-amber-500/5 transition-all"
                    required />
            </div>
            <template x-if="errors.email">
                <p class="text-[10px] font-black uppercase text-red-500 ml-1 tracking-tighter" x-text="errors.email">
                </p>
            </template>
        </div>

        {{-- Submit Button --}}
        <button type="button" @click="submit" :disabled="processing"
            class="w-full py-5 rounded-2xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black uppercase text-xs tracking-[0.2em] flex items-center justify-center gap-3 transition-all active:scale-95 disabled:opacity-50 shadow-xl shadow-amber-500/20">
            <template x-if="processing">
                <i data-lucide="loader-2" class="w-[18px] h-[18px] animate-spin"></i>
            </template>
            <template x-if="!processing">
                <span class="flex items-center gap-3">
                    Reset Password
                    <i data-lucide="arrow-right" class="w-[18px] h-[18px]"></i>
                </span>
            </template>
        </button>
    </div>

    <script>
        function resetForm() {
            return {
                form: {
                    email: ''
                },
                errors: {},
                processing: false,

                async submit() {
                    this.processing = true
                    this.errors = {}

                    try {
                        const res = await fetch('/request-change-password', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify(this.form)
                        })

                        if (!res.ok) {
                            const data = await res.json()
                            this.handleError(data.code)
                            return
                        }

                        this.$store.notif.set('success', 'Berhasil', 'Request Terkirim, Cek Email anda')

                    } catch (e) {
                        this.$store.notif.set('error', 'Error', 'Ada Kesalahan, Coba lagi nanti')
                    } finally {
                        this.processing = false
                        lucide.createIcons()
                    }
                },

                handleError(code) {
                    const messages = {
                        'invalid_credential': 'Email tidak ditemukan',
                    }

                    const message = messages[code] ?? 'Ada Kesalahan, Coba lagi nanti'

                    this.$store.notif.set('error', 'Error', message)
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
