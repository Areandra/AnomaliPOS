{{--
    <x-auth-page>
        Wrapper untuk semua halaman auth (login, reset password, dll).
        Menangani: background color, glow effect dark mode, centering konten.

        Props:
            $glowFrom  → warna glow kiri atas   (default: 'bg-indigo-900')
            $glowTo    → warna glow kanan bawah  (default: 'bg-slate-900')

    Contoh:
        <x-auth-page glow-from="bg-emerald-900" glow-to="bg-blue-900">
            <p>Konten form di sini</p>
        </x-auth-page>
--}}

@props([
    'glowFrom' => 'bg-indigo-900',
    'glowTo'   => 'bg-slate-900',
])

<div
    :class="$store.theme.isDark ? 'bg-slate-950 text-white' : 'bg-[#FDFBF7] text-slate-900'"
    class="min-h-screen flex items-center justify-center p-6 transition-colors duration-500"
>

    {{-- Glow background — hanya muncul di dark mode --}}
    <template x-if="$store.theme.isDark">
        <div class="fixed inset-0 pointer-events-none opacity-20">
            <div class="absolute top-0 left-0 w-[500px] h-[500px] rounded-full blur-[120px] mix-blend-screen {{ $glowFrom }}"></div>
            <div class="absolute bottom-0 right-0 w-[500px] h-[500px] rounded-full blur-[120px] mix-blend-screen {{ $glowTo }}"></div>
        </div>
    </template>

    <div class="relative z-10 w-full max-w-sm">
        {{ $slot }}
    </div>

</div>
