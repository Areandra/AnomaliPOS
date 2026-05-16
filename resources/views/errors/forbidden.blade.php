<x-app-layout title="403 - Akses Ditolak">

    <x-auth-page glow-from="bg-purple-900" glow-to="bg-blue-900">
        <div x-data="{ count: 5 }" x-init="lucide.createIcons();
        const timer = setInterval(() => {
            count--;
            if (count <= 0) {
                clearInterval(timer);
                window.location.href = '{{ $redirectUrl ?? '/' }}';
            }
        }, 1000)" class="w-full text-center">

            {{-- ICON --}}
            <div class="flex justify-center mb-10">
                <div :class="$store.theme.isDark ? 'bg-slate-900 border-amber-500/20 text-amber-500' :
                    'bg-white border-orange-100 text-orange-600'"
                    class="relative h-32 w-32 rounded-[3rem] flex items-center justify-center shadow-2xl border-2 rotate-3">
                    <i data-lucide="shield-alert" class="w-16 h-16" style="stroke-width: 1.5"></i>
                    <div :class="$store.theme.isDark ? 'bg-amber-500 text-slate-950' : 'bg-orange-600 text-white'"
                        class="absolute -top-2 -right-2 h-12 w-12 rounded-2xl flex items-center justify-center shadow-xl -rotate-12">
                        <i data-lucide="loader-2" class="w-6 h-6 animate-spin"></i>
                    </div>
                </div>
            </div>

            {{-- ERROR CODE --}}
            <h1 class="text-7xl font-black uppercase tracking-tighter italic leading-none mb-2 opacity-10">403</h1>
            <h2 class="text-3xl font-black uppercase tracking-tighter italic leading-none mb-4">
                Akses <span :class="$store.theme.isDark ? 'text-amber-500' : 'text-orange-600'">Ditolak</span>
            </h2>

            <div :class="$store.theme.isDark ? 'bg-amber-500/20' : 'bg-orange-200'"
                class="h-1.5 w-16 mx-auto rounded-full mb-8"></div>

            <p class="text-[10px] font-black uppercase tracking-[0.3em] mb-3 opacity-80">Privilege Restriction</p>

            <p class="text-xs font-medium leading-relaxed mb-12 px-6 opacity-50 italic">
                Maaf, Anda tidak memiliki izin yang cukup untuk mengakses halaman ini. Sesi Anda mungkin tidak valid
                atau terbatas.
            </p>

            {{-- REDIRECT CARD --}}
            <div :class="$store.theme.isDark ? 'bg-slate-900/50 border-white/10 shadow-2xl' :
                'bg-white border-slate-100 shadow-xl shadow-slate-200/50'"
                class="p-6 rounded-[2.5rem] border backdrop-blur-md transition-all mb-8">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-left">
                        <p class="text-[9px] font-black uppercase tracking-widest opacity-40">Auto Redirect</p>
                        <p class="text-xs font-bold italic">Returning to safety...</p>
                    </div>
                    <span :class="$store.theme.isDark ? 'text-amber-500' : 'text-orange-600'"
                        class="text-4xl font-black italic" x-text="'0' + count"></span>
                </div>

                <div :class="$store.theme.isDark ? 'bg-white/5' : 'bg-slate-100'"
                    class="h-1.5 w-full rounded-full overflow-hidden">
                    <div :class="$store.theme.isDark ? 'bg-amber-500' : 'bg-orange-600'"
                        :style="'width: ' + (count / 5 * 100) + '%'"
                        class="h-full transition-all duration-1000 ease-linear"></div>
                </div>
            </div>

            {{-- MANUAL BUTTON --}}
            <a href="{{ $redirectUrl ?? '/' }}"
                :class="$store.theme.isDark ?
                    'bg-white text-slate-950 hover:bg-amber-500 shadow-white/5' :
                    'bg-slate-900 text-white hover:bg-orange-600 shadow-slate-900/20'"
                class="group flex items-center justify-center gap-3 w-full py-4 rounded-[1.5rem] font-black uppercase text-xs tracking-[0.2em] transition-all active:scale-95 shadow-xl">
                <i data-lucide="home" class="w-4 h-4"></i>
                Kembali Sekarang
                <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
            </a>

        </div>
    </x-auth-page>

</x-app-layout>
