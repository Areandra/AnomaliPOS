<x-auth-layout title="Auth Terminal" subtitle="Masukkan kredensial Anda">

    @include('components.device-detected-modal')

    <div x-data="loginForm()">
        {{-- SECTION: System Access --}}
        <div class="mb-4 flex items-center gap-2">
            <div class="h-px flex-1 bg-white/5"></div>
            <span class="px-2 text-[9px] font-black uppercase tracking-[0.3em] text-amber-500/70">System Access</span>
            <div class="h-px flex-1 bg-white/5"></div>
        </div>

        {{-- Email --}}
        <div class="mb-4 space-y-2">
            <label class="ml-1 block text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">
                System Identifier / Email
            </label>
            <div class="group relative flex items-center">
                <div
                    class="absolute left-4 z-10 flex items-center justify-center text-gray-600 transition-colors group-focus-within:text-amber-500">
                    <i data-lucide="mail" class="h-[18px] w-[18px]"></i>
                </div>
                <input type="email" x-model="form.email" placeholder="example@anopos.sys"
                    class="w-full rounded-2xl border border-white/5 bg-slate-950/50 py-4 pl-12 pr-4 font-medium text-white outline-none transition-all placeholder:text-gray-700 focus:border-amber-500/50 focus:ring-4 focus:ring-amber-500/5" />
            </div>
            <template x-if="errors.email">
                <p class="ml-1 text-[10px] font-black uppercase tracking-tighter text-red-500" x-text="errors.email">
                </p>
            </template>
        </div>

        {{-- Password --}}
        <div class="mb-6 space-y-2">
            <label class="ml-1 block text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">
                Access Key / Password
            </label>
            <div class="group relative flex items-center">
                <div
                    class="absolute left-4 z-10 flex items-center justify-center text-gray-600 transition-colors group-focus-within:text-amber-500">
                    <i data-lucide="lock" class="h-[18px] w-[18px]"></i>
                </div>
                <input :type="showPassword ? 'text' : 'password'" x-model="form.password" placeholder="••••••••"
                    class="w-full rounded-2xl border border-white/5 bg-slate-950/50 py-4 pl-12 pr-12 font-medium text-white outline-none transition-all placeholder:text-gray-700 focus:border-amber-500/50 focus:ring-4 focus:ring-amber-500/5" />
                <button type="button" @click="showPassword = !showPassword"
                    class="absolute right-4 z-10 flex items-center justify-center text-gray-600 transition-colors group-focus-within:text-amber-500">
                    <span x-show="showPassword" class="flex items-center">
                        <x-lucide-eye-off class="h-[18px] w-[18px]" />
                    </span>
                    <span x-show="!showPassword" class="flex items-center">
                        <x-lucide-eye class="h-[18px] w-[18px]" />
                    </span>
                </button>
            </div>
            <template x-if="errors.password">
                <p class="ml-1 text-[10px] font-black uppercase tracking-tighter text-red-500" x-text="errors.password">
                </p>
            </template>
        </div>

        {{-- Actions --}}
        <div class="mb-6 flex items-center justify-between px-1">
            <label class="group flex cursor-pointer items-center gap-3">
                <div class="relative flex items-center">
                    <input type="checkbox" x-model="form.remember"
                        class="peer absolute h-5 w-5 cursor-pointer opacity-0" />
                    <div
                        class="h-5 w-5 rounded-md border border-white/10 bg-slate-950 transition-all peer-checked:border-amber-500 peer-checked:bg-amber-500">
                    </div>
                    <div class="absolute left-1 text-slate-950 opacity-0 transition-opacity peer-checked:opacity-100">
                        <svg width="12" height="10" viewBox="0 0 12 10" fill="none">
                            <path d="M1 5L4 8L11 1" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>
                <span
                    class="text-[10px] font-black uppercase tracking-widest text-gray-500 transition-colors group-hover:text-gray-300">Keep
                    Active</span>
            </label>
            <a href="{{ route('request-change-password-form') }}"
                class="text-[10px] font-black uppercase tracking-widest text-amber-500/50 transition-colors hover:text-amber-500">
                Forgot Key?
            </a>
        </div>

        {{-- Submit --}}
        <button type="button" @click="submit" :disabled="processing"
            class="flex w-full items-center justify-center gap-3 rounded-2xl bg-amber-500 py-5 text-xs font-black uppercase tracking-[0.2em] text-slate-950 shadow-xl shadow-amber-500/20 transition-all hover:bg-amber-400 active:scale-95 disabled:opacity-50">
            <template x-if="processing">
                <span class="flex items-center gap-3">
                    <svg class="h-[18px] w-[18px] animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
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
                    <i data-lucide="arrow-right" class="h-[18px] w-[18px]"></i>
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

                        const data = await res.json()

                        if (!res.ok) {
                            this.handleError(data.code, data.errors)
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

                handleError(code, validationErrors) {
                    if (validationErrors) {
                        this.errors = validationErrors
                    }

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
        <p class="text-[10px] font-black uppercase tracking-widest text-gray-600">
            Belum punya akun?
            <a href="/register" class="ml-1 text-amber-500 transition-colors hover:text-amber-400">Daftar Sekarang</a>
        </p>
    @endslot

</x-auth-layout>
