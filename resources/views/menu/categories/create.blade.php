<x-admin-layout>
    <x-slot:title>Manajemen Kategori</x-slot:title>
    <x-slot:page_title>
        {{ isset($initialData) ? 'Management > Menu Categories > Update' : 'Management > Menu Categories > New Menu Category' }}
    </x-slot:page_title>

    <div x-data="menuCategoryForm({{ json_encode($initialData ?? null) }}, {{ $categoryId ?? 'null' }})" x-init="$nextTick(() => lucide.createIcons())" class="mx-auto max-w-4xl p-8">
        {{-- Mengubah wrapper menjadi form HTML bawaan Laravel --}}
        <form method="POST" action="{{ isset($initialData) ? '/menu/categories/' . $categoryId : '/menu/categories' }}"
            :class="isDark ? 'bg-slate-900 border-white/5' : 'bg-white border-gray-100'"
            class="overflow-hidden rounded-[2.5rem] border shadow-2xl transition-all duration-500">

            @csrf
            @if (isset($initialData))
                @method('PUT')
            @endif

            {{-- Header --}}
            <div :class="isDark ? 'border-white/5 bg-white/5' : 'border-gray-50 bg-gray-50/50'"
                class="flex items-center justify-between border-b px-10 py-8">
                <div class="flex items-center gap-4">
                    <div :class="isDark ? 'bg-amber-500/10 text-amber-500' : 'bg-orange-100 text-orange-600'"
                        class="rounded-2xl p-3">
                        <i data-lucide="layers" class="h-6 w-6"></i>
                    </div>
                    <div>
                        <h2 :class="isDark ? 'text-white' : 'text-slate-800'"
                            class="text-xl font-black uppercase tracking-tight"
                            x-text="isEdit ? 'Edit Category' : 'Create Category'"></h2>
                        <p :class="isDark ? 'text-gray-400' : 'text-gray-500'"
                            class="text-[10px] font-black uppercase tracking-widest opacity-50">Organize your menu
                            structure
                        </p>
                    </div>
                </div>
                <a href="/menu"
                    :class="isDark ? 'hover:bg-white/10 text-gray-400' : 'hover:bg-gray-200 text-gray-600'"
                    class="rounded-full p-2 transition-all">
                    <i data-lucide="x" class="h-6 w-6"></i>
                </a>
            </div>

            <div class="space-y-10 p-10">

                {{-- Name --}}
                <div class="group relative">
                    <label
                        :class="isDark ? 'text-gray-500 group-focus-within:text-amber-500' :
                            'text-gray-400 group-focus-within:text-orange-600'"
                        class="mb-3 block text-[10px] font-black uppercase tracking-[0.2em] transition-colors">Category
                        Name</label>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30">
                            <i data-lucide="layers" class="h-[18px] w-[18px]"></i>
                        </div>
                        {{-- Menambahkan atribut name untuk request Laravel, value dari old() atau data awal --}}
                        <input type="text" name="name" x-model="form.name" required
                            placeholder="e.g. Main Course, Desserts, Beverages"
                            value="{{ old('name', $initialData['name'] ?? '') }}"
                            :class="isDark ?
                                'bg-slate-950 border-white/5 text-white focus:border-amber-500/50 focus:ring-4 focus:ring-amber-500/10' :
                                'bg-gray-50 border-gray-200 text-slate-800 focus:bg-white focus:border-orange-500/50'"
                            class="w-full rounded-2xl border py-4 pl-12 pr-4 text-sm font-bold outline-none transition-all" />
                    </div>
                    {{-- Menggunakan error handling bawaan Blade --}}
                    @error('name')
                        <p class="mt-2 text-[9px] font-black uppercase tracking-widest text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="group relative">
                    <label :class="isDark ? 'text-gray-400' : 'text-gray-500'"
                        class="mb-3 block text-[10px] font-black uppercase tracking-[0.2em] opacity-50">Brief
                        Description</label>
                    <div class="relative">
                        <div class="absolute left-4 top-4 opacity-30">
                            <i data-lucide="align-left" class="h-[18px] w-[18px]"></i>
                        </div>
                        {{-- Menambahkan atribut name dan value dari old() --}}
                        <textarea name="description" x-model="form.description" rows="3"
                            placeholder="Optional: Detail for this category group..."
                            :class="isDark ? 'bg-slate-950 border-white/5 text-white focus:border-amber-500/50' :
                                'bg-gray-50 border-gray-200 text-slate-800 focus:bg-white'"
                            class="w-full rounded-2xl border py-4 pl-12 pr-4 text-sm font-bold outline-none transition-all">{{ old('description', $initialData['description'] ?? '') }}</textarea>
                    </div>
                    @error('description')
                        <p class="mt-2 text-[9px] font-black uppercase tracking-widest text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 items-start gap-8 md:grid-cols-2">
                    {{-- Sort Order --}}
                    <div class="group relative">
                        <label :class="isDark ? 'text-gray-400' : 'text-gray-500'"
                            class="mb-3 block text-[10px] font-black uppercase tracking-[0.2em] opacity-50">Sorting
                            Priority</label>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30">
                                <i data-lucide="hash" class="h-[18px] w-[18px]"></i>
                            </div>
                            {{-- Menambahkan atribut name dan value dari old() --}}
                            <input type="number" name="sort_order" x-model="form.sort_order" required
                                value="{{ old('sort_order', $initialData['sort_order'] ?? 0) }}"
                                :class="isDark ?
                                    'bg-slate-950 border-white/5 text-white focus:border-amber-500/50' :
                                    'bg-gray-50 border-gray-200 text-slate-800 focus:bg-white'"
                                class="w-full rounded-2xl border py-4 pl-12 pr-4 text-sm font-bold outline-none transition-all" />
                        </div>
                        @error('sort_order')
                            <p class="mt-2 text-[9px] font-black uppercase tracking-widest text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Info Box --}}
                    <div :class="isDark ? 'bg-white/5 border-white/10' : 'bg-gray-50 border-gray-200'"
                        class="flex items-center gap-4 rounded-3xl border border-dashed p-6">
                        <div :class="isDark ? 'bg-indigo-500/10 text-indigo-400' : 'bg-indigo-50 text-indigo-600'"
                            class="rounded-xl p-2">
                            <i data-lucide="info" class="h-5 w-5"></i>
                        </div>
                        <p :class="isDark ? 'text-gray-300' : 'text-gray-600'"
                            class="text-[9px] font-bold uppercase leading-relaxed tracking-tight opacity-60">
                            Urutan menentukan posisi kategori pada menu pelanggan & dashboard kasir.
                        </p>
                    </div>
                </div>

                {{-- Actions --}}
                <div :class="isDark ? 'border-white/5' : 'border-gray-100'"
                    class="mt-12 flex items-center justify-end gap-4 border-t pt-8">
                    <a href="/menu"
                        :class="isDark ? 'text-gray-400 hover:bg-white/5' : 'text-gray-500 hover:bg-gray-100'"
                        class="rounded-2xl px-8 py-4 text-[10px] font-black uppercase tracking-widest transition-all">Discard</a>

                    {{-- Mengubah type menjadi submit dan menghapus trigger click submit js --}}
                    <button type="submit" :disabled="!form.name"
                        :class="isDark ? 'bg-white text-slate-950 hover:bg-gray-200' :
                            'bg-slate-900 text-white hover:bg-slate-800 shadow-slate-200'"
                        class="flex items-center gap-2 rounded-2xl px-12 py-4 text-[10px] font-black uppercase tracking-widest shadow-xl transition-all active:scale-95 disabled:opacity-50">
                        <i data-lucide="save" class="h-3.5 w-3.5"></i>
                        <span x-text="isEdit ? 'Update Category' : 'Save Category'"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function menuCategoryForm(initialData, categoryId) {
            return {
                isEdit: !!initialData,
                // Logika pemrosesan form lama dihapus karena sekarang dihandle murni oleh HTML Form
                form: initialData || {
                    name: '',
                    description: '',
                    sort_order: 0
                }
            }
        }
    </script>
</x-admin-layout>
