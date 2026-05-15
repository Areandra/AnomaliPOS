@extends('layouts.admin')
@section('title', 'Edit Kategori')
@section('page_title', 'Menu > Edit Kategori')

@section('content')
<div x-init="$store.theme.init(); $nextTick(() => lucide.createIcons())" class="max-w-lg mx-auto p-8">
    <div :class="$store.theme.isDark ? 'bg-slate-900 border-white/5' : 'bg-white border-gray-100'"
        class="rounded-3xl border p-8 shadow-xl">
        <h2 class="font-black text-lg uppercase tracking-tight mb-6 flex items-center gap-2">
            <i data-lucide="edit-3" class="w-5 h-5 text-orange-500"></i> Edit Kategori
        </h2>

        @if($errors->any())
        <div class="mb-4 p-4 rounded-2xl bg-red-100 text-red-700 text-sm">
            <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <form action="{{ route('menu.categories.update', $category->id) }}" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest opacity-60 mb-1">Nama Kategori</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                    class="w-full px-4 py-3 rounded-xl border text-sm font-medium bg-transparent focus:outline-none focus:ring-2 focus:ring-orange-500 border-gray-200 dark:border-white/10">
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest opacity-60 mb-1">Deskripsi</label>
                <textarea name="description" rows="3"
                    class="w-full px-4 py-3 rounded-xl border text-sm font-medium bg-transparent focus:outline-none focus:ring-2 focus:ring-orange-500 border-gray-200 dark:border-white/10">{{ old('description', $category->description) }}</textarea>
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest opacity-60 mb-1">Urutan</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0" required
                    class="w-full px-4 py-3 rounded-xl border text-sm font-medium bg-transparent focus:outline-none focus:ring-2 focus:ring-orange-500 border-gray-200 dark:border-white/10">
            </div>
            <div class="flex gap-3 pt-2">
                <a href="{{ route('menu.categories.index') }}"
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
