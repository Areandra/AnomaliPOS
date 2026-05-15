@extends('layouts.admin')
@section('title', isset($table) ? 'Update Table ' . $table->table_number : 'Register Table')
@section('page_title', isset($table) ? 'Management > Table > ' . $table->table_number . ' > Update' : 'Management > Table > Create New Table')

@section('content')
<div x-init="$store.theme.init(); $nextTick(() => lucide.createIcons())" class="h-full px-16 flex justify-center overflow-hidden">
    <div class="p-8 m-auto max-w-5xl w-full">
        <div :class="$store.theme.isDark ? 'bg-slate-900 border-white/5' : 'bg-white border-gray-100'"
            class="rounded-[2.5rem] shadow-2xl transition-all duration-500 border overflow-hidden">

            {{-- Header --}}
            <div :class="$store.theme.isDark ? 'border-white/5 bg-white/5' : 'border-gray-50 bg-gray-50/50'"
                class="px-10 py-8 flex justify-between items-center border-b">
                <div class="flex items-center gap-4">
                    <div :class="$store.theme.isDark ? 'bg-amber-500/10 text-amber-500' : 'bg-orange-100 text-orange-600'" class="p-3 rounded-2xl">
                        <i data-lucide="hash" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h2 :class="$store.theme.isDark ? 'text-white' : 'text-slate-800'" class="text-xl font-black uppercase tracking-tight">
                            {{ isset($table) ? 'Update Table ' . $table->table_number : 'Register Table' }}
                        </h2>
                        <p class="text-[10px] font-black uppercase tracking-widest opacity-50">Input detail meja ke sistem</p>
                    </div>
                </div>
                <a href="{{ route('tables.index') }}"
                    :class="$store.theme.isDark ? 'hover:bg-white/10 text-gray-400' : 'hover:bg-gray-200 text-gray-600'"
                    class="p-2 rounded-full transition-all">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </a>
            </div>

            @if($errors->any())
            <div class="mx-10 mt-6 p-4 rounded-2xl bg-red-100 text-red-700 text-sm">
                <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <form action="{{ isset($table) ? route('tables.update', $table->id) : route('tables.store') }}" method="POST" class="p-10">
                @csrf
                @if(isset($table)) @method('PUT') @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <div class="space-y-8">
                        {{-- Nomor Meja --}}
                        <div class="relative group">
                            <label :class="$store.theme.isDark ? 'text-gray-500 group-focus-within:text-amber-500' : 'text-gray-400 group-focus-within:text-orange-600'"
                                class="block text-[10px] font-black uppercase tracking-[0.2em] mb-3 transition-colors">Nomor Meja</label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30"><i data-lucide="hash" class="w-[18px] h-[18px]"></i></div>
                                <input type="text" name="table_number" value="{{ old('table_number', $table->table_number ?? '') }}" required
                                    :class="$store.theme.isDark ? 'bg-slate-950 border-white/5 text-white focus:border-amber-500/50 focus:ring-4 focus:ring-amber-500/10' : 'bg-gray-50 border-gray-200 text-slate-800 focus:bg-white focus:border-orange-500/50 focus:ring-4 focus:ring-orange-500/10'"
                                    class="w-full pl-12 pr-4 py-4 rounded-2xl border text-sm font-bold transition-all outline-none"
                                    placeholder="Contoh: A1, BAR 03" />
                            </div>
                        </div>

                        {{-- Kapasitas --}}
                        <div class="relative group">
                            <label :class="$store.theme.isDark ? 'text-gray-500 group-focus-within:text-indigo-500' : 'text-gray-400 group-focus-within:text-indigo-600'"
                                class="block text-[10px] font-black uppercase tracking-[0.2em] mb-3 transition-colors">Kapasitas (Orang)</label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30"><i data-lucide="users" class="w-[18px] h-[18px]"></i></div>
                                <select name="capacity" required
                                    :class="$store.theme.isDark ? 'bg-slate-950 border-white/5 text-white focus:border-indigo-500/50' : 'bg-gray-50 border-gray-200 text-slate-800 focus:bg-white focus:border-orange-500/50'"
                                    class="w-full pl-12 pr-4 py-4 rounded-2xl border text-sm font-bold transition-all outline-none appearance-none">
                                    @foreach([2, 4, 6, 8, 10, 12] as $cap)
                                    <option value="{{ $cap }}" {{ old('capacity', $table->capacity ?? 4) == $cap ? 'selected' : '' }}>{{ $cap }} Orang</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Info Box --}}
                    <div class="space-y-6">
                        <div :class="$store.theme.isDark ? 'bg-white/5 border-white/10' : 'bg-gray-50 border-gray-200'"
                            class="p-8 rounded-3xl border border-dashed flex flex-col items-center justify-center text-center h-full space-y-4">
                            <div :class="$store.theme.isDark ? 'bg-indigo-500/10 text-indigo-400' : 'bg-indigo-50 text-indigo-600'" class="p-4 rounded-full">
                                <i data-lucide="info" class="w-8 h-8"></i>
                            </div>
                            <div>
                                <h4 :class="$store.theme.isDark ? 'text-white' : 'text-slate-800'" class="text-xs font-black uppercase tracking-widest mb-2">QR Code System</h4>
                                <p :class="$store.theme.isDark ? 'text-gray-300' : 'text-gray-600'"
                                    class="text-[10px] font-bold leading-relaxed opacity-60">
                                    QR Code akan otomatis dibuat oleh sistem ketika sesi meja diaktifkan oleh kasir di halaman operasional.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div :class="$store.theme.isDark ? 'border-white/5' : 'border-gray-100'"
                    class="flex flex-row justify-between mt-12 pt-8 border-t">
                    @if(isset($table))
                    <form action="{{ route('tables.destroy', $table->id) }}" method="POST" onsubmit="return confirm('Hapus meja ini?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="px-10 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl transition-all active:scale-95 bg-red-500 text-white hover:bg-red-600">
                            Delete Table
                        </button>
                    </form>
                    @else
                    <div></div>
                    @endif

                    <div class="flex flex-1 justify-end items-center gap-4">
                        <a href="{{ route('tables.index') }}"
                            :class="$store.theme.isDark ? 'text-gray-400 hover:bg-white/5' : 'text-gray-500 hover:bg-gray-100'"
                            class="px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all">Discard</a>
                        <button type="submit"
                            :class="$store.theme.isDark ? 'bg-white text-slate-950 hover:bg-gray-200' : 'bg-slate-900 text-white hover:bg-slate-800 shadow-slate-200'"
                            class="px-10 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl transition-all active:scale-95">
                            {{ isset($table) ? 'Update Table' : 'Register Table' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
