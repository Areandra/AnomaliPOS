<x-app-layout title="Reset Password Akun">

    <x-auth-page glow-from="bg-indigo-900" glow-to="bg-slate-900">
        <div x-data="resetPassword({{ json_encode(['userId' => $userId]) }})" x-init="init()" class="w-full text-center">

            {{-- ICON STATUS --}}
            <div class="flex justify-center mb-8">
                <div :class="$store.theme.isDark ?
                    'bg-indigo-500/10 border-indigo-500/20 text-indigo-500' :
                    'bg-indigo-50 border-indigo-100 text-indigo-600'"
                    class="relative h-24 w-24 rounded-[2.5rem] flex items-center justify-center shadow-2xl border">
                    <i data-lucide="lock-keyhole" class="w-12 h-12 animate-pulse"></i>

                    <div :class="$store.theme.isDark ? 'bg-slate-800 text-indigo-500' : 'bg-white text-indigo-600'"
                        class="absolute -bottom-2 -right-2 h-10 w-10 rounded-2xl flex items-center justify-center shadow-lg rotate-12">
                        <i data-lucide="shield-alert" class="w-5 h-5"></i>
                    </div>
                </div>
            </div>

            {{-- HEADER --}}
            <div class="text-center mb-8">
                <h1 class="text-4xl font-black uppercase tracking-tighter italic leading-none mb-4">
                    Reset
                    <span :class="$store.theme.isDark ? 'text-indigo-500' : 'text-indigo-600'">Pass</span>
                </h1>

                <div class="h-1 w-12 mx-auto rounded-full mb-6 bg-indigo-500/20"></div>

                <p class="text-[10px] font-black uppercase tracking-[0.2em] mb-2 opacity-80">
                    User: {{ $userName }}
                </p>
                <p class="text-xs font-medium leading-relaxed opacity-50 italic px-4">
                    Buat kata sandi baru yang kuat untuk mengamankan akun Anda.
                </p>
            </div>

            {{-- FORM --}}
            <div class="space-y-4 text-left">

                {{-- Password Field --}}
                <div class="space-y-2">
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" x-model="form.password"
                            placeholder="KATA SANDI BARU"
                            :class="errors.password ? 'border-red-500' : 'border-slate-500/20 focus:border-indigo-500'"
                            class="w-full bg-transparent border-2 px-10 py-4 rounded-2xl outline-none transition-all text-sm font-bold placeholder:text-[10px]" />
                        <i data-lucide="lock-keyhole"
                            class="absolute left-4 top-1/2 -translate-y-1/2 opacity-20 w-[18px] h-[18px]"></i>
                        <button type="button" @click="showPassword = !showPassword"
                            class="absolute right-4 top-1/2 -translate-y-1/2 opacity-40 hover:opacity-100 transition-opacity">
                            <template x-if="showPassword">
                                <i data-lucide="eye-off" class="w-[18px] h-[18px]"></i>
                            </template>
                            <template x-if="!showPassword">
                                <i data-lucide="eye" class="w-[18px] h-[18px]"></i>
                            </template>
                        </button>
                    </div>
                    <template x-if="errors.password">
                        <p class="text-red-500 text-[10px] font-bold uppercase text-center" x-text="errors.password">
                        </p>
                    </template>
                </div>

                {{-- Confirm Password Field --}}
                <div class="space-y-2">
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" x-model="form.password_confirmation"
                            placeholder="KONFIRMASI KATA SANDI"
                            :class="errors.password_confirmation ? 'border-red-500' :
                                'border-slate-500/20 focus:border-indigo-500'"
                            class="w-full bg-transparent border-2 px-10 py-4 rounded-2xl outline-none transition-all text-sm font-bold placeholder:text-[10px]" />
                        <i data-lucide="check-circle-2"
                            class="absolute left-4 top-1/2 -translate-y-1/2 opacity-20 w-[18px] h-[18px]"></i>
                    </div>
                    <template x-if="errors.password_confirmation">
                        <p class="text-red-500 text-[10px] font-bold uppercase text-center"
                            x-text="errors.password_confirmation"></p>
                    </template>
                </div>

                {{-- Submit Button --}}
                <button type="button" @click="submit" :disabled="processing"
                    :class="processing
                        ?
                        'opacity-50 cursor-not-allowed bg-indigo-600 text-white' :
                        'bg-indigo-600 hover:bg-indigo-500 text-white shadow-indigo-500/40 hover:shadow-indigo-500/60'"
                    class="w-full py-4 rounded-2xl font-black uppercase tracking-widest text-sm transition-all active:scale-95 shadow-xl text-center">
                    <span x-text="processing ? 'Saving...' : 'Simpan Password'"></span>
                </button>

            </div>
        </div>
    </x-auth-page>

</x-app-layout>

<script>
    function resetPassword(initialData) {
        return {
            showPassword: false,
            processing: false,
            errors: {},
            form: {
                userId: initialData.userId,
                password: '',
                password_confirmation: '',
            },

            init() {
                lucide.createIcons()
            },

            async submit() {
                this.processing = true
                this.errors = {}

                try {
                    // Endpoint disesuaikan menggunakan parameter userId yang dikirim dari initialData
                    const res = await fetch(`/users/${this.form.userId}/password`, {
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

                    this.$store.notif.set('success', 'Berhasil', 'Password berhasil diperbarui')
                    setTimeout(() => window.location.href = '/login', 1500)

                } catch (e) {
                    this.$store.notif.set('error', 'Error', 'Ada kesalahan, coba lagi nanti')
                } finally {
                    this.processing = false
                    this.$nextTick(() => lucide.createIcons())
                }
            }
        }
    }
</script>
