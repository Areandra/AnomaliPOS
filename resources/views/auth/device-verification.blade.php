<x-app-layout title="MFA - Verifikasi Perangkat">

    <x-auth-page :glow-from="$status === 'success' ? 'bg-emerald-900' : 'bg-red-900'" :glow-to="$status === 'success' ? 'bg-blue-900' : 'bg-orange-900'">
        <div x-data="{ isSuccess: {{ $status === 'success' ? 'true' : 'false' }} }" class="text-center">

            {{-- Icon: kondisional karena ikonnya beda tergantung status --}}
            <div class="flex justify-center mb-8">
                <div :class="isSuccess
                    ?
                    ($store.theme.isDark ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-500' :
                        'bg-emerald-50 border-emerald-100 text-emerald-600') :
                    ($store.theme.isDark ? 'bg-red-500/10 border-red-500/20 text-red-500' :
                        'bg-red-50 border-red-100 text-red-600')"
                    class="relative h-24 w-24 rounded-[2.5rem] flex items-center justify-center shadow-2xl border">
                    <template x-if="isSuccess">
                        <i data-lucide="shield-check" class="w-12 h-12 animate-bounce"></i>
                    </template>
                    <template x-if="!isSuccess">
                        <i data-lucide="shield-alert" class="w-12 h-12 animate-pulse"></i>
                    </template>
                    <div :class="[$store.theme.isDark ? 'bg-slate-800' : 'bg-white', isSuccess ? 'text-emerald-500' : 'text-red-500']"
                        class="absolute -bottom-2 -right-2 h-10 w-10 rounded-2xl flex items-center justify-center shadow-lg rotate-12">
                        <i :data-lucide="isSuccess ? 'check-circle-2' : 'x-circle'" class="w-5 h-5"></i>
                    </div>
                </div>
            </div>

            {{-- Heading --}}
            <h1 class="text-4xl font-black uppercase tracking-tighter italic leading-none mb-4">
                <template x-if="isSuccess">
                    <span>Device <span
                            :class="$store.theme.isDark ? 'text-emerald-500' : 'text-emerald-600'">Trusted</span></span>
                </template>
                <template x-if="!isSuccess">
                    <span>Access <span
                            :class="$store.theme.isDark ? 'text-red-500' : 'text-red-600'">Denied</span></span>
                </template>
            </h1>
            <div :class="isSuccess ? 'bg-emerald-500/20' : 'bg-red-500/20'" class="h-1 w-12 mx-auto rounded-full mb-6">
            </div>
            <p class="text-sm font-black uppercase tracking-[0.2em] mb-2 opacity-80"
                x-text="isSuccess ? 'Verifikasi Berhasil' : 'Verifikasi Gagal'"></p>

            <p class="text-xs font-medium leading-relaxed mb-10 px-4 opacity-50 italic">
                {{ $message }}
                @if ($status === 'success' && $deviceName)
                    untuk perangkat {{ $deviceName }}.
                @endif
            </p>

            {{-- CTA --}}
            <a href="{{ url('/login') }}"
                :class="isSuccess
                    ?
                    'bg-emerald-600 hover:bg-emerald-500 shadow-emerald-500/20' :
                    'bg-slate-800 hover:bg-slate-700 shadow-slate-500/20'"
                class="block w-full py-4 rounded-2xl text-white text-xs font-bold uppercase tracking-widest transition-all duration-300 shadow-lg active:scale-95">
                Kembali ke Login
            </a>

        </div>
    </x-auth-page>

</x-app-layout>
