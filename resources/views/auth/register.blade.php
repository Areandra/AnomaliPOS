<x-auth-layout title="Daftar Akun" subtitle="Buat akun restoran baru">

    <div x-data="registerForm()">
        {{-- SECTION: Owner --}}
        <div class="mb-4 flex items-center gap-2">
            <div class="h-px flex-1 bg-white/5"></div>
            <span class="px-2 text-[9px] font-black uppercase tracking-[0.3em] text-amber-500/70">Owner Account</span>
            <div class="h-px flex-1 bg-white/5"></div>
        </div>

        {{-- Name --}}
        <div class="mb-4 space-y-2">
            <label class="ml-1 block text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">Nama
                Lengkap</label>
            <div class="group relative flex items-center">
                <div
                    class="absolute left-4 z-10 flex items-center justify-center text-gray-600 transition-colors group-focus-within:text-amber-500">
                    <i data-lucide="user" class="h-[18px] w-[18px]"></i>
                </div>
                <input type="text" x-model="form.name" placeholder="John Doe"
                    class="w-full rounded-2xl border border-white/5 bg-slate-950/50 py-4 pl-12 pr-4 font-medium text-white outline-none transition-all placeholder:text-gray-700 focus:border-amber-500/50 focus:ring-4 focus:ring-amber-500/5" />
            </div>
            <template x-if="errors.name">
                <p class="ml-1 text-[10px] font-black uppercase tracking-tighter text-red-500" x-text="errors.name"></p>
            </template>
        </div>

        {{-- Email --}}
        <div class="mb-4 space-y-2">
            <label class="ml-1 block text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">Email</label>
            <div class="group relative flex items-center">
                <div
                    class="absolute left-4 z-10 flex items-center justify-center text-gray-600 transition-colors group-focus-within:text-amber-500">
                    <i data-lucide="mail" class="h-[18px] w-[18px]"></i>
                </div>
                <input type="email" x-model="form.email" placeholder="owner@email.com"
                    class="w-full rounded-2xl border border-white/5 bg-slate-950/50 py-4 pl-12 pr-4 font-medium text-white outline-none transition-all placeholder:text-gray-700 focus:border-amber-500/50 focus:ring-4 focus:ring-amber-500/5" />
            </div>
            <template x-if="errors.email">
                <p class="ml-1 text-[10px] font-black uppercase tracking-tighter text-red-500" x-text="errors.email">
                </p>
            </template>
        </div>

        {{-- Password --}}
        <div class="mb-6 space-y-2">
            <label class="ml-1 block text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">Password</label>
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

        {{-- SECTION: Restaurant --}}
        <div class="mb-4 flex items-center gap-2">
            <div class="h-px flex-1 bg-white/5"></div>
            <span class="px-2 text-[9px] font-black uppercase tracking-[0.3em] text-amber-500/70">Restaurant</span>
            <div class="h-px flex-1 bg-white/5"></div>
        </div>

        {{-- Restaurant Name --}}
        <div class="mb-4 space-y-2">
            <label class="ml-1 block text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">Nama
                Restoran</label>
            <div class="group relative flex items-center">
                <div
                    class="absolute left-4 z-10 flex items-center justify-center text-gray-600 transition-colors group-focus-within:text-amber-500">
                    <i data-lucide="chef-hat" class="h-[18px] w-[18px]"></i>
                </div>
                <input type="text" x-model="form.restaurant_name" placeholder="Warung Makan Saya"
                    class="w-full rounded-2xl border border-white/5 bg-slate-950/50 py-4 pl-12 pr-4 font-medium text-white outline-none transition-all placeholder:text-gray-700 focus:border-amber-500/50 focus:ring-4 focus:ring-amber-500/5" />
            </div>
            <template x-if="errors.restaurant_name">
                <p class="ml-1 text-[10px] font-black uppercase tracking-tighter text-red-500"
                    x-text="errors.restaurant_name"></p>
            </template>
        </div>

        {{-- Restaurant PIN --}}
        <div class="mb-6 space-y-2">
            <label class="ml-1 block text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">PIN
                Restoran</label>
            <div class="group relative flex items-center">
                <div
                    class="absolute left-4 z-10 flex items-center justify-center text-gray-600 transition-colors group-focus-within:text-amber-500">
                    <i data-lucide="key-round" class="h-[18px] w-[18px]"></i>
                </div>
                <input :type="showPin ? 'text' : 'password'" x-model="form.restaurant_pin"
                    placeholder="PIN untuk akses kasir"
                    class="w-full rounded-2xl border border-white/5 bg-slate-950/50 py-4 pl-12 pr-12 font-medium text-white outline-none transition-all placeholder:text-gray-700 focus:border-amber-500/50 focus:ring-4 focus:ring-amber-500/5" />
                <button type="button" @click="showPin = !showPin"
                    class="absolute right-4 z-10 flex items-center justify-center text-gray-600 transition-colors group-focus-within:text-amber-500">
                    <span x-show="showPassword" class="flex items-center">
                        <x-lucide-eye-off class="h-[18px] w-[18px]" />
                    </span>
                    <span x-show="!showPassword" class="flex items-center">
                        <x-lucide-eye class="h-[18px] w-[18px]" />
                    </span>
                </button>
            </div>
            <p class="ml-1 text-[9px] text-gray-600">PIN digunakan untuk masuk ke mode kasir setiap shift.</p>
            <template x-if="errors.restaurant_pin">
                <p class="ml-1 text-[10px] font-black uppercase tracking-tighter text-red-500"
                    x-text="errors.restaurant_pin"></p>
            </template>
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
                    Mendaftar...
                </span>
            </template>
            <template x-if="!processing">
                <span class="flex items-center gap-3">
                    Daftar Sekarang
                    <i data-lucide="arrow-right" class="h-[18px] w-[18px]"></i>
                </span>
            </template>
        </button>
    </div>

    <script>
        function registerForm() {
            return {
                form: {
                    name: '',
                    email: '',
                    password: '',
                    restaurant_name: '',
                    restaurant_pin: '',
                },
                errors: {},
                processing: false,
                showPassword: false,
                showPin: false,

                async submit() {
                    if (this.processing) return
                    this.processing = true
                    this.errors = {}

                    try {
                        const res = await fetch('/register', {
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

                        Alpine.store('notifs').set('success', 'Pendaftaran Berhasil',
                            'Akun Anda sedang diproses. Kami akan mengirim email konfirmasi.')
                        setTimeout(() => window.location.href = '/login', 5000)

                    } catch (e) {
                        Alpine.store('notifs').set('error', 'Error', 'Ada Kesalahan, Coba lagi nanti')
                    } finally {
                        this.processing = false
                        lucide.createIcons()
                    }
                },

                handleError(code, validationErrors) {
                    if (validationErrors) {
                        this.errors = validationErrors
                    }

                    const messages = {
                        'invalid_credential': 'Email sudah terdaftar',
                        'disabled': 'Akun belum diaktifkan, silahkan tunggu konfirmasi',
                    }

                    const message = messages[code] ?? 'Ada Kesalahan, Coba lagi nanti'
                    Alpine.store('notifs').set('error', 'Pendaftaran Gagal', message)
                }
            }
        }
    </script>

    @slot('footerLink')
        <p class="text-[10px] font-black uppercase tracking-widest text-gray-600">
            Sudah punya akun?
            <a href="/login" class="ml-1 text-amber-500 transition-colors hover:text-amber-400">Login di sini</a>
        </p>
    @endslot

</x-auth-layout>
