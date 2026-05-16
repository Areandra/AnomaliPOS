{{--
    <x-notification />
    Dipanggil otomatis dari x-app-layout.
    Dikendalikan via: $store.notif.set(type, title, message)

    Tipe: 'success' | 'error' | 'warning' | 'info'
--}}

<div
    x-data
    x-show="$store.notif.open"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-3 scale-95"
    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
    x-transition:leave-end="opacity-0 translate-y-3 scale-95"
    class="fixed bottom-6 right-6 z-[9999] w-full max-w-sm"
    style="display: none"
>
    <div
        :class="{
            'bg-emerald-600 border-emerald-500 shadow-emerald-900/40': $store.notif.type === 'success',
            'bg-red-600 border-red-500 shadow-red-900/40':             $store.notif.type === 'error',
            'bg-amber-500 border-amber-400 shadow-amber-900/40':       $store.notif.type === 'warning',
            'bg-blue-600 border-blue-500 shadow-blue-900/40':          $store.notif.type === 'info',
        }"
        class="relative overflow-hidden flex items-start gap-3 p-4 rounded-2xl border shadow-2xl text-white"
    >
        {{-- Icon — inline SVG, tidak butuh lucide.createIcons() --}}
        <div class="shrink-0 mt-0.5 w-5 h-5">
            <svg x-show="$store.notif.type === 'success'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
            <svg x-show="$store.notif.type === 'error'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                <circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/>
            </svg>
            <svg x-show="$store.notif.type === 'warning'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/>
            </svg>
            <svg x-show="$store.notif.type === 'info'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                <circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/>
            </svg>
        </div>

        {{-- Text --}}
        <div class="flex-1 min-w-0">
            <p class="text-sm font-black uppercase tracking-widest leading-none" x-text="$store.notif.title"></p>
            <p
                x-show="$store.notif.message"
                x-text="$store.notif.message"
                class="text-xs opacity-80 mt-1.5 font-medium leading-snug"
            ></p>
        </div>

        {{-- Close button --}}
        <button
            @click="$store.notif.open = false"
            class="shrink-0 opacity-50 hover:opacity-100 transition-opacity -mt-0.5 -mr-0.5 p-1 rounded-lg hover:bg-white/10"
        >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                <line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/>
            </svg>
        </button>

        {{-- Progress bar — mengecil dari 100% → 0% selama 4 detik --}}
        <div class="absolute bottom-0 left-0 right-0 h-[3px] bg-white/20">
            <div
                x-show="$store.notif.open"
                x-transition:enter="transition-none"
                x-transition:enter-start="opacity-100"
                x-transition:enter-end="opacity-100"
                class="h-full bg-white/50 transition-[width] ease-linear"
                :style="$store.notif.open ? 'width: 0%; transition-duration: 4000ms' : 'width: 100%; transition-duration: 0ms'"
            ></div>
        </div>
    </div>
</div>
