@extends('layouts.admin')

@section('title', 'Manajemen Kategori')
@section('page_title', isset($initialData) ? 'Management > Menu Categories > Update' : 'Management > Menu Categories > New Menu Category')

@section('content')

<div
    x-data="menuCategoryForm({{ json_encode($initialData ?? null) }}, {{ $categoryId ?? 'null' }})"
    x-init="$nextTick(() => lucide.createIcons())"
    class="p-8 max-w-4xl mx-auto"
>
    <div :class="$store.theme.isDark ? 'bg-slate-900 border-white/5' : 'bg-white border-gray-100'"
         class="rounded-[2.5rem] shadow-2xl transition-all duration-500 border overflow-hidden">

        {{-- Header --}}
        <div :class="$store.theme.isDark ? 'border-white/5 bg-white/5' : 'border-gray-50 bg-gray-50/50'"
             class="px-10 py-8 flex justify-between items-center border-b">
            <div class="flex items-center gap-4">
                <div :class="$store.theme.isDark ? 'bg-amber-500/10 text-amber-500' : 'bg-orange-100 text-orange-600'"
                     class="p-3 rounded-2xl">
                    <i data-lucide="layers" class="w-6 h-6"></i>
                </div>
                <div>
                    <h2 :class="$store.theme.isDark ? 'text-white' : 'text-slate-800'"
                        class="text-xl font-black uppercase tracking-tight"
                        x-text="isEdit ? 'Edit Category' : 'Create Category'"></h2>
                    <p :class="$store.theme.isDark ? 'text-gray-400' : 'text-gray-500'"
                       class="text-[10px] font-black uppercase tracking-widest opacity-50">Organize your menu structure</p>
                </div>
            </div>
            <a href="/menu" :class="$store.theme.isDark ? 'hover:bg-white/10 text-gray-400' : 'hover:bg-gray-200 text-gray-600'"
               class="p-2 rounded-full transition-all">
                <i data-lucide="x" class="w-6 h-6"></i>
            </a>
        </div>

        <div class="p-10 space-y-10">

            {{-- Name --}}
            <div class="relative group">
                <label :class="$store.theme.isDark ? 'text-gray-500 group-focus-within:text-amber-500' : 'text-gray-400 group-focus-within:text-orange-600'"
                       class="block text-[10px] font-black uppercase tracking-[0.2em] mb-3 transition-colors">Category Name</label>
                <div class="relative">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30">
                        <i data-lucide="layers" class="w-[18px] h-[18px]"></i>
                    </div>
                    <input type="text" x-model="form.name" required
                           placeholder="e.g. Main Course, Desserts, Beverages"
                        :class="$store.theme.isDark ? 'bg-slate-950 border-white/5 text-white focus:border-amber-500/50 focus:ring-4 focus:ring-amber-500/10' : 'bg-gray-50 border-gray-200 text-slate-800 focus:bg-white focus:border-orange-500/50'"
                        class="w-full pl-12 pr-4 py-4 rounded-2xl border text-sm font-bold transition-all outline-none" />
                </div>
                <template x-if="errors.name">
                    <p class="text-red-500 text-[9px] font-black uppercase mt-2 tracking-widest" x-text="errors.name"></p>
                </template>
            </div>

            {{-- Description --}}
            <div class="relative group">
                <label :class="$store.theme.isDark ? 'text-gray-400' : 'text-gray-500'"
                       class="block text-[10px] font-black uppercase tracking-[0.2em] mb-3 opacity-50">Brief Description</label>
                <div class="relative">
                    <div class="absolute left-4 top-4 opacity-30">
                        <i data-lucide="align-left" class="w-[18px] h-[18px]"></i>
                    </div>
                    <textarea x-model="form.description" rows="3"
                              placeholder="Optional: Detail for this category group..."
                        :class="$store.theme.isDark ? 'bg-slate-950 border-white/5 text-white focus:border-amber-500/50' : 'bg-gray-50 border-gray-200 text-slate-800 focus:bg-white'"
                        class="w-full pl-12 pr-4 py-4 rounded-2xl border text-sm font-bold transition-all outline-none"></textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                {{-- Sort Order --}}
                <div class="relative group">
                    <label :class="$store.theme.isDark ? 'text-gray-400' : 'text-gray-500'"
                           class="block text-[10px] font-black uppercase tracking-[0.2em] mb-3 opacity-50">Sorting Priority</label>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30">
                            <i data-lucide="hash" class="w-[18px] h-[18px]"></i>
                        </div>
                        <input type="number" x-model="form.sortOrder" required
                            :class="$store.theme.isDark ? 'bg-slate-950 border-white/5 text-white focus:border-amber-500/50' : 'bg-gray-50 border-gray-200 text-slate-800 focus:bg-white'"
                            class="w-full pl-12 pr-4 py-4 rounded-2xl border text-sm font-bold transition-all outline-none" />
                    </div>
                </div>

                {{-- Info Box --}}
                <div :class="$store.theme.isDark ? 'bg-white/5 border-white/10' : 'bg-gray-50 border-gray-200'"
                     class="p-6 rounded-3xl flex items-center gap-4 border border-dashed">
                    <div :class="$store.theme.isDark ? 'bg-indigo-500/10 text-indigo-400' : 'bg-indigo-50 text-indigo-600'"
                         class="p-2 rounded-xl">
                        <i data-lucide="info" class="w-5 h-5"></i>
                    </div>
                    <p :class="$store.theme.isDark ? 'text-gray-300' : 'text-gray-600'"
                       class="text-[9px] font-bold leading-relaxed uppercase tracking-tight opacity-60">
                        Urutan menentukan posisi kategori pada menu pelanggan & dashboard kasir.
                    </p>
                </div>
            </div>

            {{-- Actions --}}
            <div :class="$store.theme.isDark ? 'border-white/5' : 'border-gray-100'"
                 class="mt-12 pt-8 border-t flex justify-end items-center gap-4">
                <a href="/menu" :class="$store.theme.isDark ? 'text-gray-400 hover:bg-white/5' : 'text-gray-500 hover:bg-gray-100'"
                   class="px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all">Discard</a>

                <button type="button" @click="submit" :disabled="processing || !form.name"
                    :class="$store.theme.isDark ? 'bg-white text-slate-950 hover:bg-gray-200' : 'bg-slate-900 text-white hover:bg-slate-800 shadow-slate-200'"
                    class="flex items-center gap-2 px-12 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl transition-all active:scale-95 disabled:opacity-50">
                    <i data-lucide="save" class="w-3.5 h-3.5"></i>
                    <span x-text="processing ? 'Processing...' : (isEdit ? 'Update Category' : 'Save Category')"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function menuCategoryForm(initialData, categoryId) {
    return {
        isEdit: !!initialData,
        processing: false,
        errors: {},
        form: initialData || { name: '', description: '', sortOrder: 0 },

        async submit() {
            this.processing = true
            this.errors = {}
            const url    = this.isEdit ? `/menu/categories/${categoryId}` : '/menu/categories'
            const method = this.isEdit ? 'PUT' : 'POST'
            try {
                const res = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(this.form)
                })
                const data = await res.json()
                if (!res.ok) {
                    if (data.errors) Object.keys(data.errors).forEach(k => this.errors[k] = data.errors[k][0])
                    this.$store.notif.set('error', 'Error', data.message ?? 'Terjadi kesalahan')
                    return
                }
                this.$store.notif.set('success', 'Berhasil', this.isEdit ? 'Kategori diperbarui' : 'Kategori ditambahkan')
                setTimeout(() => window.location.href = '/menu', 1000)
            } catch (e) {
                this.$store.notif.set('error', 'Error', 'Ada kesalahan, coba lagi nanti')
            } finally {
                this.processing = false
            }
        }
    }
}
</script>

@endsection