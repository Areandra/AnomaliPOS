@extends('layouts.app')

@section('title', 'MFA')

@section('content')

    <div x-data="deviceVerificationStatus()" x-init="init()"
        :class="isDark ? 'bg-slate-950 text-white' : 'bg-[#FDFBF7] text-slate-900'"
        class="min-h-screen flex items-center justify-center p-6 transition-colors duration-500">
        {{-- BACKGROUND DECORATIVE --}}
        <template x-if="isDark">
            <div class="fixed inset-0 pointer-events-none opacity-20">
                <div :class="isSuccess ? 'bg-emerald-900' : 'bg-red-900'"
                    class="absolute top-0 left-0 w-125 h-125 rounded-full blur-[120px] mix-blend-screen"></div>
                <div :class="isSuccess ? 'bg-blue-900' : 'bg-orange-900'"
                    class="absolute bottom-0 right-0 w-125 h-125 rounded-full blur-[120px] mix-blend-screen"></div>
            </div>
        </template>

        <div class="relative z-10 max-w-sm w-full text-center">

            {{-- ICON STATUS --}}
            <div class="flex justify-center mb-8">
                <div :class="isSuccess
                            ? (isDark ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-500' : 'bg-emerald-50 border-emerald-100 text-emerald-600')
                            : (isDark ? 'bg-red-500/10 border-red-500/20 text-red-500' : 'bg-red-50 border-red-100 text-red-600')"
                    class="relative h-24 w-24 rounded-[2.5rem] flex items-center justify-center shadow-2xl border">
                    <template x-if="isSuccess">
                        <i data-lucide="shield-check" class="w-12 h-12 animate-bounce"></i>
                    </template>
                    <template x-if="!isSuccess">
                        <i data-lucide="shield-alert" class="w-12 h-12 animate-pulse"></i>
                    </template>

                    <div :class="[isDark ? 'bg-slate-800' : 'bg-white', isSuccess ? 'text-emerald-500' : 'text-red-500']"
                        class="absolute -bottom-2 -right-2 h-10 w-10 rounded-2xl flex items-center justify-center shadow-lg rotate-12">
                        <i data-lucide="check-circle-2" :class="isSuccess ? 'opacity-100' : 'opacity-20'"
                            class="w-5 h-5"></i>
                    </div>
                </div>
            </div>

            {{-- TEXT CONTENT --}}
            <h1 class="text-4xl font-black uppercase tracking-tighter italic leading-none mb-4">
                <template x-if="isSuccess">
                    <span>
                        Device <span :class="isDark ? 'text-emerald-500' : 'text-emerald-600'">Trusted</span>
                    </span>
                </template>
                <template x-if="!isSuccess">
                    <span>
                        Access <span :class="isDark ? 'text-red-500' : 'text-red-600'">Denied</span>
                    </span>
                </template>
            </h1>

            <div :class="isSuccess ? 'bg-emerald-500/20' : 'bg-red-500/20'" class="h-1 w-12 mx-auto rounded-full mb-6">
            </div>

            <p class="text-sm font-black uppercase tracking-[0.2em] mb-2 opacity-80"
                x-text="isSuccess ? 'Verifikasi Berhasil' : 'Verifikasi Gagal'">
            </p>

            <p class="text-xs font-medium leading-relaxed mb-10 px-4 opacity-50 italic">
                {{ $message }}
                @if($status === 'success' && $deviceName)
                    untuk perangkat {{ $deviceName }}.
                @endif
            </p>

        </div>
    </div>

    <script>
        function deviceVerificationStatus() {
            return {
                isDark: false,
                isSuccess: {{ $status === 'success' ? 'true' : 'false' }},

                init() {
                    const stored = localStorage.getItem('theme')
                    if (stored === 'light' || stored === 'dark') {
                        this.isDark = stored === 'dark'
                    } else {
                        this.isDark = window.matchMedia('(prefers-color-scheme: dark)').matches
                    }
                    document.documentElement.setAttribute('data-theme', this.isDark ? 'dark' : 'light')
                    this.$watch('isDark', (val) => {
                        localStorage.setItem('theme', val ? 'dark' : 'light')
                        document.documentElement.setAttribute('data-theme', val ? 'dark' : 'light')
                    })

                    // Panggil setelah x-if selesai render
                    this.$nextTick(() => lucide.createIcons())
                }
            }
        }
    </script>

@endsection