<x-auth-layout title="Auth Terminal" subtitle="Masukkan kredensial Anda">

    @include('components.device-detected-modal')

    <div x-data="loginForm()">
        {{-- Email --}}
        <div class="space-y-2 mb-5">
            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 ml-1">
                System Identifier / Email
            </label>
            <div class="relative group">
                <div
                    class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-600 group-focus-within:text-amber-500 transition-colors">
                    <i data-lucide="mail" class="w-[18px] h-[18px]"></i>
                </div>
                <input type="email" x-model="form.email" placeholder="example@anopos.sys"
                    class="w-full bg-slate-950/50 border border-white/5 py-4 pl-12 pr-4 rounded-2xl text-white font-medium placeholder:text-gray-700 outline-none focus:border-amber-500/50 focus:ring-4 focus:ring-amber-500/5 transition-all" />
            </div>
            <template x-if="errors.email">
                <p class="text-[10px] font-black uppercase text-red-500 ml-1 tracking-tighter" x-text="errors.email">
                </p>
            </template>
        </div>

        {{-- Password --}}
        <div class="space-y-2 mb-6">
            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 ml-1">
                Access Key / Password
            </label>
            <div class="relative group">
                <div
                    class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-600 group-focus-within:text-amber-500 transition-colors">
                    <i data-lucide="lock" class="w-[18px] h-[18px]"></i>
                </div>
                <input :type="showPassword ? 'text' : 'password'" x-model="form.password" placeholder="••••••••"
                    class="w-full bg-slate-950/50 border border-white/5 py-4 pl-12 pr-12 rounded-2xl text-white font-medium placeholder:text-gray-700 outline-none focus:border-amber-500/50 focus:ring-4 focus:ring-amber-500/5 transition-all" />
                <button type="button" @click="showPassword = !showPassword"
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-600 hover:text-amber-500 transition-colors">
                    <template x-if="showPassword">
                        <i data-lucide="eye-off" class="w-[18px] h-[18px]"></i>
                    </template>
                    <template x-if="!showPassword">
                        <i data-lucide="eye" class="w-[18px] h-[18px]"></i>
                    </template>
                </button>
            </div>
            <template x-if="errors.password">
                <p class="text-[10px] font-black uppercase text-red-500 ml-1 tracking-tighter" x-text="errors.password">
                </p>
            </template>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between px-1 mb-6">
            <label class="flex items-center gap-3 cursor-pointer group">
                <div class="relative flex items-center">
                    <input type="checkbox" x-model="form.remember"
                        class="peer h-5 w-5 opacity-0 absolute cursor-pointer" />
                    <div
                        class="h-5 w-5 rounded-md border border-white/10 bg-slate-950 peer-checked:bg-amber-500 peer-checked:border-amber-500 transition-all">
                    </div>
                    <div class="absolute text-slate-950 opacity-0 peer-checked:opacity-100 left-1 transition-opacity">
                        <svg width="12" height="10" viewBox="0 0 12 10" fill="none">
                            <path d="M1 5L4 8L11 1" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>
                <span
                    class="text-[10px] font-black uppercase tracking-widest text-gray-500 group-hover:text-gray-300 transition-colors">Keep
                    Active</span>
            </label>
            <a href="{{ route('request-change-password-form') }}"
                class="text-[10px] font-black uppercase tracking-widest text-amber-500/50 hover:text-amber-500 transition-colors">
                Forgot Key?
            </a>
        </div>

        {{-- Submit --}}
        <button type="button" @click="submit" :disabled="processing"
            class="w-full py-5 rounded-2xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black uppercase text-xs tracking-[0.2em] flex items-center justify-center gap-3 transition-all active:scale-95 disabled:opacity-50 shadow-xl shadow-amber-500/20">
            <template x-if="processing">
                <span class="flex items-center gap-3">
                    <svg class="animate-spin w-[18px] h-[18px]" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z">
                        </path>
                    </svg>
                    Processing...
                </span>
            </template>
            <template x-if="!processing">
                <span class="flex items-center gap-3">
                    Grant Access
                    <i data-lucide="arrow-right" class="w-[18px] h-[18px]"></i>
                </span>
            </template>
        </button>
    </div>

    <script>
        function loginForm() {
            return {
                form: {
                    email: '',
                    password: '',
                    remember: false
                },
                errors: {},
                processing: false,
                showPassword: false,

                async submit() {
                    if (this.processing) return
                    this.processing = true
                    this.errors = {}

                    try {
                        const res = await fetch('/login', {
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

                        window.location.href = '/login/restaurant-pin'

                    } catch (e) {
                        Alpine.store('notifs').set('error', 'Error', 'Ada Kesalahan, Coba lagi nanti')
                    } finally {
                        this.processing = false
                        this.form.password = ''
                        lucide.createIcons()
                    }
                },

                handleError(code) {
                    const messages = {
                        'invalid_credential': 'Email atau Password anda Salah',
                        'disabled': 'Akun Telah dinonaktifkan',
                        'invalid_fp': 'Device Baru Terdeteksi',
                        'not_trusted': 'Device Baru Terdeteksi',
                    }

                    const message = messages[code] ?? 'Ada Kesalahan, Coba lagi nanti'

                    if (code === 'invalid_fp' || code === 'not_trusted') {
                        setTimeout(() => Alpine.store('deviceModal').open = true, 500)
                    }

                    Alpine.store('notifs').set('error', 'Login Gagal', message)
                }
            }
        }
    </script>

    @slot('footerLink')
        <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest">
            Belum punya akun?
            <a href="/register" class="text-amber-500 hover:text-amber-400 transition-colors ml-1">Daftar Sekarang</a>
        </p>
    @endslot

</x-auth-layout>
