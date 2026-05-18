<x-admin-layout>
    <x-slot:title>{{ isset($table) ? 'Update Table ' . $table->table_number : 'Register Table' }}</x-slot:title>
    <x-slot:page_title>{{ isset($table)
        ? 'Management > Table > ' . $table->table_number . ' > Update'
        : 'Management >
            Table > Create New Table' }}</x-slot:page_title>

    <div class="flex h-full justify-center overflow-hidden px-16">
        <div class="m-auto w-full max-w-5xl p-8">

            {{-- DELETE FORM TERPISAH --}}
            @if (isset($table))
                <form id="delete-table-form" action="{{ route('tables.destroy', $table->id) }}" method="POST"
                    class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            @endif

            {{-- MAIN FORM --}}
            <form action="{{ isset($table) ? route('tables.update', $table->id) : route('tables.store') }}"
                method="POST">
                @csrf

                @if (isset($table))
                    @method('PUT')
                @endif

                <div :class="isDark ?
                    'bg-slate-900 border-white/5' :
                    'bg-white border-gray-100'"
                    class="overflow-hidden rounded-[2.5rem] border shadow-2xl transition-all duration-500">

                    {{-- Header --}}
                    <div :class="isDark ?
                        'border-white/5 bg-white/5' :
                        'border-gray-50 bg-gray-50/50'"
                        class="flex items-center justify-between border-b px-10 py-8">
                        <div class="flex items-center gap-4">
                            <div :class="isDark ?
                                'bg-amber-500/10 text-amber-500' :
                                'bg-orange-100 text-orange-600'"
                                class="rounded-2xl p-3">
                                <i data-lucide="hash" class="h-6 w-6"></i>
                            </div>

                            <div>
                                <h2 :class="isDark ? 'text-white' : 'text-slate-800'"
                                    class="text-xl font-black uppercase tracking-tight">
                                    {{ isset($table) ? 'Update Table ' . $table->table_number : 'Register Table' }}
                                </h2>

                                <p class="text-[10px] font-black uppercase tracking-widest opacity-50">
                                    Input detail meja ke sistem
                                </p>
                            </div>
                        </div>

                        <a href="{{ route('tables.index') }}"
                            :class="isDark ?
                                'hover:bg-white/10 text-gray-400' :
                                'hover:bg-gray-200 text-gray-600'"
                            class="rounded-full p-2 transition-all">
                            <i data-lucide="x" class="h-6 w-6"></i>
                        </a>
                    </div>

                    {{-- ERROR --}}
                    @if ($errors->any())
                        <div class="mx-10 mt-6 rounded-2xl bg-red-100 p-4 text-sm text-red-700">
                            <ul class="list-inside list-disc">
                                @foreach ($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- BODY --}}
                    <div class="p-10">

                        <div class="grid grid-cols-1 gap-12 md:grid-cols-2">

                            {{-- LEFT --}}
                            <div class="space-y-8">

                                {{-- NOMOR MEJA --}}
                                <div class="group relative">

                                    <label
                                        :class="isDark ?
                                            'text-gray-500 group-focus-within:text-amber-500' :
                                            'text-gray-400 group-focus-within:text-orange-600'"
                                        class="mb-3 block text-[10px] font-black uppercase tracking-[0.2em] transition-colors">
                                        Nomor Meja
                                    </label>

                                    <div class="relative">

                                        <div class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30">
                                            <i data-lucide="hash" class="h-[18px] w-[18px]"></i>
                                        </div>

                                        <input type="text" name="table_number"
                                            value="{{ old('table_number', $table->table_number ?? '') }}" required
                                            :class="isDark ?
                                                'bg-slate-950 border-white/5 text-white focus:border-amber-500/50 focus:ring-4 focus:ring-amber-500/10' :
                                                'bg-gray-50 border-gray-200 text-slate-800 focus:bg-white focus:border-orange-500/50 focus:ring-4 focus:ring-orange-500/10'"
                                            class="w-full rounded-2xl border py-4 pl-12 pr-4 text-sm font-bold outline-none transition-all"
                                            placeholder="Contoh: A1, BAR 03" />
                                    </div>
                                </div>

                                {{-- KAPASITAS --}}
                                <div class="group relative">

                                    <label
                                        :class="isDark ?
                                            'text-gray-500 group-focus-within:text-indigo-500' :
                                            'text-gray-400 group-focus-within:text-indigo-600'"
                                        class="mb-3 block text-[10px] font-black uppercase tracking-[0.2em] transition-colors">
                                        Kapasitas (Orang)
                                    </label>

                                    <div class="relative">

                                        <div class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30">
                                            <i data-lucide="users" class="h-[18px] w-[18px]"></i>
                                        </div>

                                        <select name="capacity" required
                                            :class="isDark ?
                                                'bg-slate-950 border-white/5 text-white focus:border-indigo-500/50' :
                                                'bg-gray-50 border-gray-200 text-slate-800 focus:bg-white focus:border-orange-500/50'"
                                            class="w-full appearance-none rounded-2xl border py-4 pl-12 pr-4 text-sm font-bold outline-none transition-all">
                                            @foreach ([2, 4, 6, 8, 10, 12] as $cap)
                                                <option value="{{ $cap }}"
                                                    {{ old('capacity', $table->capacity ?? 4) == $cap ? 'selected' : '' }}>
                                                    {{ $cap }} Orang
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>

                            {{-- RIGHT --}}
                            <div class="space-y-6">

                                <div :class="isDark ?
                                    'bg-white/5 border-white/10' :
                                    'bg-gray-50 border-gray-200'"
                                    class="flex h-full flex-col items-center justify-center space-y-4 rounded-3xl border border-dashed p-8 text-center">
                                    <div :class="isDark ?
                                        'bg-indigo-500/10 text-indigo-400' :
                                        'bg-indigo-50 text-indigo-600'"
                                        class="rounded-full p-4">
                                        <i data-lucide="info" class="h-8 w-8"></i>
                                    </div>

                                    <div>
                                        <h4 :class="isDark ?
                                            'text-white' :
                                            'text-slate-800'"
                                            class="mb-2 text-xs font-black uppercase tracking-widest">
                                            QR Code System
                                        </h4>

                                        <p :class="isDark ?
                                            'text-gray-300' :
                                            'text-gray-600'"
                                            class="text-[10px] font-bold leading-relaxed opacity-60">
                                            QR Code akan otomatis dibuat oleh sistem ketika sesi meja diaktifkan oleh
                                            kasir
                                            di halaman operasional.
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- FOOTER --}}
                        <div :class="isDark ?
                            'border-white/5' :
                            'border-gray-100'"
                            class="mt-12 flex flex-row justify-between border-t pt-8">

                            {{-- DELETE --}}
                            @if (isset($table))
                                <button type="submit" form="delete-table-form"
                                    onclick="return confirm('Hapus meja ini?')"
                                    class="rounded-2xl bg-red-500 px-10 py-4 text-[10px] font-black uppercase tracking-widest text-white shadow-xl transition-all hover:bg-red-600 active:scale-95">
                                    Delete Table
                                </button>
                            @else
                                <div></div>
                            @endif

                            {{-- ACTION --}}
                            <div class="flex flex-1 items-center justify-end gap-4">

                                <a href="{{ route('tables.index') }}"
                                    :class="isDark ?
                                        'text-gray-400 hover:bg-white/5' :
                                        'text-gray-500 hover:bg-gray-100'"
                                    class="rounded-2xl px-8 py-4 text-[10px] font-black uppercase tracking-widest transition-all">
                                    Discard
                                </a>

                                <button type="submit"
                                    :class="isDark ?
                                        'bg-white text-slate-950 hover:bg-gray-200' :
                                        'bg-slate-900 text-white hover:bg-slate-800 shadow-slate-200'"
                                    class="rounded-2xl px-10 py-4 text-[10px] font-black uppercase tracking-widest shadow-xl transition-all active:scale-95">
                                    {{ isset($table) ? 'Update Table' : 'Register Table' }}
                                </button>

                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
