@extends('layouts.admin')
@section('title', 'Edit Meja')
@section('page_title', 'Tables > Edit')

@section('content')
<div x-init="$store.theme.init(); $nextTick(() => lucide.createIcons())" class="max-w-lg mx-auto p-8">
    <div :class="$store.theme.isDark ? 'bg-slate-900 border-white/5' : 'bg-white border-gray-100'"
        class="rounded-3xl border p-8 shadow-xl">
        <h2 class="font-black text-lg uppercase tracking-tight mb-6 flex items-center gap-2">
            <i data-lucide="edit-3" class="w-5 h-5 text-orange-500"></i> Edit Meja {{ $table->table_number }}
        </h2>

        @if($errors->any())
        <div class="mb-4 p-4 rounded-2xl bg-red-100 text-red-700 text-sm">
            <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <form action="{{ route('tables.update', $table->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest opacity-60 mb-1">Nomor Meja</label>
                <input type="text" name="table_number" value="{{ old('table_number', $table->table_number) }}" required
                    class="w-full px-4 py-3 rounded-xl border text-sm font-medium bg-transparent focus:outline-none focus:ring-2 focus:ring-orange-500 border-gray-200 dark:border-white/10">
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest opacity-60 mb-1">Kapasitas</label>
                <input type="number" name="capacity" value="{{ old('capacity', $table->capacity) }}" min="1" required
                    class="w-full px-4 py-3 rounded-xl border text-sm font-medium bg-transparent focus:outline-none focus:ring-2 focus:ring-orange-500 border-gray-200 dark:border-white/10">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest opacity-60 mb-1">Posisi X</label>
                    <input type="number" name="position_x" value="{{ old('position_x', $table->position_x) }}" step="0.01"
                        class="w-full px-4 py-3 rounded-xl border text-sm font-medium bg-transparent focus:outline-none focus:ring-2 focus:ring-orange-500 border-gray-200 dark:border-white/10">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest opacity-60 mb-1">Posisi Y</label>
                    <input type="number" name="position_y" value="{{ old('position_y', $table->position_y) }}" step="0.01"
                        class="w-full px-4 py-3 rounded-xl border text-sm font-medium bg-transparent focus:outline-none focus:ring-2 focus:ring-orange-500 border-gray-200 dark:border-white/10">
                </div>
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest opacity-60 mb-1">Status</label>
                <select name="status"
                    class="w-full px-4 py-3 rounded-xl border text-sm font-medium bg-transparent focus:outline-none focus:ring-2 focus:ring-orange-500 border-gray-200 dark:border-white/10">
                    @foreach(['available', 'occupied', 'waiting_payment'] as $s)
                    <option value="{{ $s }}" {{ old('status', $table->status) === $s ? 'selected' : '' }}>
                        {{ ucwords(str_replace('_', ' ', $s)) }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-6">
                <label class="flex items-center gap-2 text-sm font-bold cursor-pointer">
                    <input type="checkbox" name="facing" value="1" {{ old('facing', $table->facing) ? 'checked' : '' }}
                        class="w-4 h-4 rounded accent-orange-500">
                    Facing
                </label>
                <label class="flex items-center gap-2 text-sm font-bold cursor-pointer">
                    <input type="checkbox" name="vertical" value="1" {{ old('vertical', $table->vertical) ? 'checked' : '' }}
                        class="w-4 h-4 rounded accent-orange-500">
                    Vertical
                </label>
            </div>
            <div class="flex gap-3 pt-2">
                <a href="{{ route('tables.index') }}"
                    class="flex-1 py-3 rounded-xl text-sm font-black uppercase tracking-widest text-center border border-gray-200 dark:border-white/10 hover:bg-gray-100 dark:hover:bg-white/5 transition-all">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 py-3 rounded-xl text-sm font-black uppercase tracking-widest bg-slate-900 text-white hover:bg-slate-700 dark:bg-white dark:text-slate-950 dark:hover:bg-gray-200 transition-all">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
