{{--
    <x-notification />
    Dipanggil otomatis dari x-app-layout / x-auth-layout.
    Dikendalikan via: Alpine.store('notifs').set(type, title, message)
--}}

<div class="pointer-events-none fixed top-6 right-6 z-[9999] flex w-full max-w-sm flex-col gap-3">
    <template x-for="item in $store.notifs.items" :key="item.id">
        <div x-show="item.visible" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-3 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-3 scale-95" class="pointer-events-auto w-full">
            <div :class="{
                'bg-emerald-600 border-emerald-500 shadow-emerald-900/40': item.type === 'success',
                'bg-red-600 border-red-500 shadow-red-900/40': item.type === 'error',
                'bg-amber-500 border-amber-400 shadow-amber-900/40': item.type === 'warning',
                'bg-blue-600 border-blue-500 shadow-blue-900/40': item.type === 'info',
            }"
                class="relative flex items-start gap-3 overflow-hidden rounded-2xl border p-4 text-white shadow-2xl">
                {{-- Icon — inline SVG --}}
                <div class="mt-0.5 h-5 w-5 shrink-0">
                    <svg x-show="item.type === 'success'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="h-5 w-5">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                        <polyline points="22 4 12 14.01 9 11.01" />
                    </svg>
                    <svg x-show="item.type === 'error'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="h-5 w-5">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="15" x2="9" y1="9" y2="15" />
                        <line x1="9" x2="15" y1="9" y2="15" />
                    </svg>
                    <svg x-show="item.type === 'warning'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="h-5 w-5">
                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3" />
                        <line x1="12" x2="12" y1="9" y2="13" />
                        <line x1="12" x2="12.01" y1="17" y2="17" />
                    </svg>
                    <svg x-show="item.type === 'info'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="h-5 w-5">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" x2="12" y1="8" y2="12" />
                        <line x1="12" x2="12.01" y1="16" y2="16" />
                    </svg>
                </div>

                {{-- Text --}}
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-black uppercase leading-none tracking-widest" x-text="item.title"></p>
                    <p x-show="item.message" x-text="item.message"
                        class="mt-1.5 text-xs font-medium leading-snug opacity-80"></p>
                </div>

                {{-- Close button --}}
                <button @click="$store.notifs.dismiss(item.id)"
                    class="-mr-0.5 -mt-0.5 shrink-0 rounded-lg p-1 opacity-50 transition-opacity hover:bg-white/10 hover:opacity-100">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                        <line x1="18" x2="6" y1="6" y2="18" />
                        <line x1="6" x2="18" y1="6" y2="18" />
                    </svg>
                </button>

                {{-- Progress bar — Animasi murni CSS transition (Lebih Andal) --}}
                <div class="absolute bottom-0 left-0 right-0 h-[3px] bg-white/20">
                    <div class="h-full w-full origin-left bg-white/50 transition-all duration-[4000ms] ease-linear"
                        x-init="$nextTick(() => $el.style.width = '0%')"></div>
                </div>
            </div>
        </div>
    </template>
</div>
