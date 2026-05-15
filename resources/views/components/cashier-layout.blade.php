@props(['currentShift' => null, 'pageTitle' => null])

<!DOCTYPE html>
<html lang="id" x-data="themeManager()">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'AnoPos' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="transition-colors duration-500" :class="isDark ? 'bg-slate-950 text-gray-100' : 'bg-[#FDFBF7] text-gray-900'">

<div class="h-dvh flex overflow-hidden flex-col transition-colors duration-500">

    {{-- HEADER --}}
    <header class="fixed top-0 left-0 w-full h-16 z-50 flex items-center justify-between px-6 backdrop-blur-xl border-b duration-300"
        :class="isDark ? 'bg-slate-900/70 border-white/5' : 'bg-white/70 border-gray-200/50'">

        {{-- KIRI --}}
        <div class="flex items-center gap-2">
            <div class="-ml-5">
                <button onclick="window.history.back()"
                    class="p-2 rounded-xl duration-200 group"
                    :class="isDark ? 'hover:bg-white/5 text-gray-400 hover:text-white' : 'hover:bg-gray-100 text-gray-600 hover:text-gray-900'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="transition-transform group-hover:-translate-x-1"><path d="m15 18-6-6 6-6"/></svg>
                </button>
                <button onclick="window.history.forward()"
                    class="p-2 rounded-xl duration-200 group"
                    :class="isDark ? 'hover:bg-white/5 text-gray-400 hover:text-white' : 'hover:bg-gray-100 text-gray-600 hover:text-gray-900'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="transition-transform group-hover:translate-x-1"><path d="m9 18 6-6-6-6"/></svg>
                </button>
            </div>
            <div class="flex flex-col">
                <span class="font-black text-xl tracking-tighter uppercase" :class="isDark ? 'text-white' : 'text-slate-800'">
                    Ano<span :class="isDark ? 'text-amber-500' : 'text-orange-600'">Pos</span>
                </span>
                @if($pageTitle)
                <span class="text-[9px] font-black uppercase tracking-[0.2em] opacity-50">{{ $pageTitle }}</span>
                @endif
            </div>
        </div>

        {{-- KANAN --}}
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-3">
                @isset($headerLinks)
                    {{ $headerLinks }}
                @endisset

                <button x-show="!isFullscreen" @click="toggleFullscreen()"
                    class="flex items-center gap-2 px-4 py-2 rounded-full duration-300 font-black text-[10px] uppercase tracking-widest shadow-sm"
                    :class="isDark ? 'bg-slate-800 text-amber-500 hover:bg-slate-700 border border-white/5' : 'bg-white text-orange-600 hover:bg-orange-50 border border-gray-200'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" x2="14" y1="3" y2="10"/><line x1="3" x2="10" y1="21" y2="14"/></svg>
                    <span class="hidden md:block">FullScreen</span>
                </button>
            </div>

            @if($currentShift)
                @if($currentShift->id)
                <div class="flex flex-col items-end pr-4 border-r" :class="isDark ? 'border-white/10' : 'border-gray-200'">
                    <div class="flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-[10px] font-black uppercase tracking-widest" :class="isDark ? 'text-white' : 'text-slate-900'">
                            Shift #{{ $currentShift->id }}
                        </span>
                    </div>
                    <span class="text-[9px] font-bold opacity-40 uppercase tracking-tighter">
                        Modal: Rp {{ number_format($currentShift->modal_awal, 0, ',', '.') }}
                    </span>
                </div>
                @endif

                <div class="flex items-center gap-3">
                    <div class="flex flex-col items-end leading-tight">
                        <span class="text-[11px] font-black uppercase tracking-tight" :class="isDark ? 'text-white' : 'text-slate-800'">
                            {{ $currentShift->user->name ?? 'Unknown User' }}
                        </span>
                        <span class="text-[9px] font-bold uppercase" :class="isDark ? 'text-amber-500' : 'text-orange-600'">
                            {{ $currentShift->user->role ?? 'No Role' }}
                        </span>
                    </div>
                    <a href="/me" class="w-10 h-10 rounded-2xl flex items-center justify-center border-2 overflow-hidden"
                        :class="isDark ? 'bg-slate-800 border-white/10' : 'bg-gray-100 border-white shadow-sm'">
                        @if($currentShift->user?->avatar_url)
                            <img src="{{ $currentShift->user->avatar_url }}" alt="avatar" class="w-full h-full object-cover">
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" :class="isDark ? 'text-gray-500' : 'text-gray-400'"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        @endif
                    </a>
                </div>
            @endif
        </div>
    </header>

    <main class="flex-1 h-full relative pt-16">
        {{ $slot }}
    </main>

</div>

<script>
function themeManager() {
    return {
        isDark: localStorage.getItem('theme') === 'dark',
        isFullscreen: false,

        toggleTheme() {
            this.isDark = !this.isDark;
            localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
            this.updateRootTheme();
        },

        updateRootTheme() {
            document.documentElement.classList.toggle('dark', this.isDark);
            document.documentElement.dataset.theme = this.isDark ? 'dark' : 'light';
        },

        toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
                this.isFullscreen = true;
            } else {
                document.exitFullscreen();
                this.isFullscreen = false;
            }
        },

        init() {
            // Sync state awal ke DOM
            this.updateRootTheme();

            // Listen event dari child components (active-order-panel, dll)
            window.addEventListener('toggle-theme', () => this.toggleTheme());

            document.addEventListener('fullscreenchange', () => {
                this.isFullscreen = !!document.fullscreenElement;
            });
        }
    }
}
</script>
</body>
</html>
