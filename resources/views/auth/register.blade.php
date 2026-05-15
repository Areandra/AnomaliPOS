@extends('layouts.auth')

@section('title', 'Daftar Akun')
@section('subtitle', 'Buat akun restoran baru')

@section('form')

<div x-data="registerForm()">

    {{-- SECTION: Owner --}}
    <div class="flex items-center gap-2 mb-4">
        <div class="h-px flex-1 bg-white/5"></div>
        <span class="text-[9px] font-black uppercase tracking-[0.3em] text-amber-500/70 px-2">Owner Account</span>
        <div class="h-px flex-1 bg-white/5"></div>
    </div>

    {{-- Name --}}
    <div class="space-y-2 mb-4">
        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 ml-1">Nama Lengkap</label>
        <div class="relative group">
            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-600 group-focus-within:text-amber-500 transition-colors">
                <i data-lucide="user" class="w-[18px] h-[18px]"></i>
            </div>
            <input type="text" x-model="form.name" placeholder="John Doe"
                class="w-full bg-slate-950/50 border border-white/5 py-4 pl-12 pr-4 rounded-2xl text-white font-medium placeholder:text-gray-700 outline-none focus:border-amber-500/50 focus:ring-4 focus:ring-amber-500/5 transition-all" />
        </div>
        <template x-if="errors.name">
            <p class="text-[10px] font-black uppercase text-red-500 ml-1 tracking-tighter" x-text="errors.name"></p>
        </template>
    </div>

    {{-- Email --}}
    <div class="space-y-2 mb-4">
        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 ml-1">Email</label>
        <div class="relative group">
            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-600 group-focus-within:text-amber-500 transition-colors">
                <i data-lucide="mail" class="w-[18px] h-[18px]"></i>
            </div>
            <input type="email" x-model="form.email" placeholder="owner@email.com"
                class="w-full bg-slate-950/50 border border-white/5 py-4 pl-12 pr-4 rounded-2xl text-white font-medium placeholder:text-gray-700 outline-none focus:border-amber-500/50 focus:ring-4 focus:ring-amber-500/5 transition-all" />
        </div>
        <template x-if="errors.email">
            <p class="text-[10px] font-black uppercase text-red-500 ml-1 tracking-tighter" x-text="errors.email"></p>
        </template>
    </div>

    {{-- Password --}}
    <div class="space-y-2 mb-6">
        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 ml-1">Password</label>
        <div class="relative group">
            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-600 group-focus-within:text-amber-500 transition-colors">
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
            <p class="text-[10px] font-black uppercase text-red-500 ml-1 tracking-tighter" x-text="errors.password"></p>
        </template>
    </div>

    {{-- SECTION: Restaurant --}}
    <div class="flex items-center gap-2 mb-4">
        <div class="h-px flex-1 bg-white/5"></div>
        <span class="text-[9px] font-black uppercase tracking-[0.3em] text-amber-500/70 px-2">Restaurant</span>
        <div class="h-px flex-1 bg-white/5"></div>
    </div>

    {{-- Restaurant Name --}}
    <div class="space-y-2 mb-4">
        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 ml-1">Nama Restoran</label>
        <div class="relative group">
            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-600 group-focus-within:text-amber-500 transition-colors">
                <i data-lucide="chef-hat" class="w-[18px] h-[18px]"></i>
            </div>
            <input type="text" x-model="form.restaurant_name" placeholder="Warung Makan Saya"
                class="w-full bg-slate-950/50 border border-white/5 py-4 pl-12 pr-4 rounded-2xl text-white font-medium placeholder:text-gray-700 outline-none focus:border-amber-500/50 focus:ring-4 focus:ring-amber-500/5 transition-all" />
        </div>
        <template x-if="errors.restaurant_name">
            <p class="text-[10px] font-black uppercase text-red-500 ml-1 tracking-tighter" x-text="errors.restaurant_name"></p>
        </template>
    </div>

    {{-- Restaurant PIN --}}
    <div class="space-y-2 mb-6">
        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 ml-1">PIN Restoran</label>
        <div class="relative group">
            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-600 group-focus-within:text-amber-500 transition-colors">
                <i data-lucide="key-round" class="w-[18px] h-[18px]"></i>
            </div>
            <input :type="showPin ? 'text' : 'password'" x-model="form.restaurant_pin" placeholder="PIN untuk akses kasir"
                class="w-full bg-slate-950/50 border border-white/5 py-4 pl-12 pr-12 rounded-2xl text-white font-medium placeholder:text-gray-700 outline-none focus:border-amber-500/50 focus:ring-4 focus:ring-amber-500/5 transition-all" />
            <button type="button" @click="showPin = !showPin"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-600 hover:text-amber-500 transition-colors">
                <template x-if="showPin">
                    <i data-lucide="eye-off" class="w-[18px] h-[18px]"></i>
                </template>
                <template x-if="!showPin">
                    <i data-lucide="eye" class="w-[18px] h-[18px]"></i>
                </template>
            </button>
        </div>
        <p class="text-[9px] text-gray-600 ml-1">PIN digunakan untuk masuk ke mode kasir setiap shift.</p>
        <template x-if="errors.restaurant_pin">
            <p class="text-[10px] font-black uppercase text-red-500 ml-1 tracking-tighter" x-text="errors.restaurant_pin"></p>
        </template>
    </div>

    {{-- Submit --}}
    <button type="button" @click="submit" :disabled="processing"
        class="w-full py-5 rounded-2xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black uppercase text-xs tracking-[0.2em] flex items-center justify-center gap-3 transition-all active:scale-95 disabled:opacity-50 shadow-xl shadow-amber-500/20">
        <template x-if="processing">
            <span class="flex items-center gap-3">
                <svg class="animate-spin w-[18px] h-[18px]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                Mendaftar...
            </span>
        </template>
        <template x-if="!processing">
            <span class="flex items-center gap-3">
                Daftar Sekarang
                <i data-lucide="arrow-right" class="w-[18px] h-[18px]"></i>
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

@endsection

@section('footer_link')
<p class="text-[10px] font-black text-gray-600 uppercase tracking-widest">
    Sudah punya akun?
    <a href="/login" class="text-amber-500 hover:text-amber-400 transition-colors ml-1">Login di sini</a>
</p>
@endsection
