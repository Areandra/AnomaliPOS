<aside
    :class="$store.theme.isDark ? 'bg-[#0f172a] border-white/5' : 'bg-white border-gray-200'"
    class="w-72 shrink-0 border-r flex flex-col h-full transition-all duration-300"
>
    {{-- Header Categories --}}
    <div class="px-6 flex items-center h-16 border-b dark:border-white/5 justify-between">
        <div class="flex items-center gap-2">
            {{-- Warna Icon disamakan ke Amber-500 --}}
            <i data-lucide="layers" class="text-amber-500" size="16"></i>
            <span class="font-black text-[11px] uppercase tracking-[0.2em] dark:text-white">
                Categories
            </span>
        </div>
        {{-- Tombol Plus disesuaikan ke Amber --}}
        <a href="{{ route('menu.categories.create') }}"
           class="p-1.5 rounded-lg bg-white text-slate-950 hover:bg-gray-200 active:scale-95 transition-all shadow-sm">
            <i data-lucide="plus" size="14"></i>
        </a>
    </div>

    {{-- Category List --}}
    <nav class="flex-1 p-4 space-y-2 overflow-y-auto scrollbar-hide">
        @php
            $categories = [
                ['id' => -1, 'name' => 'All Categories'],
                ['id' => 1,  'name' => 'Nasi'],
                ['id' => 2,  'name' => 'Ayam'],
                ['id' => 3,  'name' => 'Minuman']
            ];
        @endphp

        @foreach($categories as $cat)
            <div class="group flex items-center justify-between px-4 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 cursor-pointer
                {{ $loop->first
                    ? 'bg-amber-500 text-slate-950 shadow-[0_0_20px_rgba(245,158,11,0.3)]'
                    : 'text-gray-400 hover:bg-white/5 hover:text-white'
                }}">

                <span class="flex-1 truncate py-1">
                    {{ $cat['name'] }}
                </span>

                @if($cat['id'] !== -1)
                    <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="flex bg-black/20 backdrop-blur-md rounded-lg p-0.5 border border-white/10">
                            <a href="/menu/categories/{{ $cat['id'] }}"
                               class="p-1.5 rounded-md hover:bg-white/20 text-blue-400">
                                <i data-lucide="edit-3" size="12"></i>
                            </a>
                            <button class="p-1.5 rounded-md hover:bg-white/20 text-red-400">
                                <i data-lucide="trash-2" size="12"></i>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </nav>
</aside>
