<x-app-layout title="404 - Halaman Tidak Ditemukan">

    <x-auth-page glow-from="bg-purple-900" glow-to="bg-blue-900">
        <div x-data x-init="lucide.createIcons()" class="w-full text-center">

            {{-- ICON --}}
            <div class="flex justify-center mb-10">
                <div :class="$store.theme.isDark ? 'bg-slate-900 border-amber-500/20 text-amber-500' :
                    'bg-white border-orange-100 text-orange-600'"
                    class="relative h-32 w-32 rounded-[3rem] flex items-center justify-center shadow-2xl border-2 -rotate-3">
                    <i data-lucide="search-x" class="w-16 h-16" style="stroke-width: 1.5"></i>
                    <div :class="$store.theme.isDark ? 'bg-amber-500 text-slate-950' : 'bg-orange-600 text-white'"
                        class="absolute -bottom-2 -left-2 h-12 w-12 rounded-2xl flex items-center justify-center shadow-xl rotate-12">
                        <i data-lucide="compass" class="w-6 h-6 animate-spin"></i>
                    </div>
                </div>
            </div>

            {{-- ERROR CODE --}}
            <h1 class="text-7xl font-black uppercase tracking-tighter italic leading-none mb-2 opacity-10">404</h1>
            <h2 class="text-3xl font-black uppercase tracking-tighter italic leading-none mb-4">
                Halaman <span :class="$store.theme.isDark ? 'text-amber-500' : 'text-orange-600'">Hilang</span>
            </h2>

            <div :class="$store.theme.isDark ? 'bg-amber-500/20' : 'bg-orange-200'"
                class="h-1.5 w-16 mx-auto rounded-full mb-8"></div>

            <p class="text-[10px] font-black uppercase tracking-[0.3em] mb-3 opacity-80">Lost in Space</p>

            <p class="text-xs font-medium leading-relaxed mb-12 px-6 opacity-50 italic">
                Maaf, menu atau halaman yang Anda cari tidak ditemukan. Mungkin telah dihapus atau URL salah ketik.
            </p>

            {{-- ACTION --}}
            <div class="space-y-4">
                <a href="/"
                    :class="$store.theme.isDark ?
                        'bg-white text-slate-950 hover:bg-amber-500 shadow-white/5' :
                        'bg-slate-900 text-white hover:bg-orange-600 shadow-slate-900/20'"
                    class="flex items-center justify-center gap-3 w-full py-4 rounded-[1.5rem] font-black uppercase text-xs tracking-[0.2em] transition-all active:scale-95 shadow-xl">
                    <i data-lucide="home" class="w-4 h-4"></i>
                    Kembali ke Menu
                </a>

                <p class="text-[9px] font-black uppercase tracking-widest opacity-30">
                    Automated Navigation System v1.0
                </p>
            </div>

        </div>
    </x-auth-page>

</x-app-layout>
