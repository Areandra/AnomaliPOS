<x-cashier-layout>
    <div
        class="h-screen overflow-hidden bg-slate-50 pb-16 text-slate-900 transition-colors duration-500 dark:bg-slate-950 dark:text-white">
        <div class="pointer-events-none fixed inset-0 hidden opacity-20 dark:block">
            <div class="w-125 h-125 absolute left-0 top-0 rounded-full bg-purple-900 mix-blend-screen blur-[120px]">
            </div>
            <div class="w-125 h-125 absolute bottom-0 right-0 rounded-full bg-blue-900 mix-blend-screen blur-[120px]">
            </div>
        </div>

        <main class="relative z-10 h-full w-full overflow-auto p-6 lg:p-12">
            <div
                class="relative mb-8 overflow-hidden rounded-[3rem] border border-slate-200 bg-white p-8 shadow-xl transition-all lg:p-12 dark:border-white/5 dark:bg-slate-900 dark:shadow-2xl">
                <div class="relative z-10 flex flex-col items-center gap-8 md:flex-row">
                    <div class="relative">
                        <div
                            class="h-32 w-32 rounded-[2.5rem] border-2 border-orange-500/50 p-1.5 dark:border-amber-500/50">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=f59e0b&color=fff&size=128"
                                class="h-full w-full rounded-[2rem] object-cover">
                        </div>
                        <div
                            class="absolute -bottom-2 -right-2 cursor-pointer rounded-2xl bg-amber-500 p-2.5 text-slate-950 shadow-lg">
                            <i data-lucide="camera" class="h-4 w-4"></i>
                        </div>
                    </div>

                    <div class="flex-1 text-center md:text-left">
                        <div
                            class="mb-3 inline-flex items-center gap-2 rounded-full border border-amber-500/20 bg-amber-500/10 px-3 py-1">
                            <span
                                class="text-[10px] font-black uppercase tracking-widest text-amber-500">{{ $user->role }}</span>
                        </div>
                        <h1 class="mb-1 text-4xl font-black uppercase tracking-tighter">{{ $user->name }}</h1>
                        <div
                            class="flex items-center justify-center gap-2 text-sm font-bold italic opacity-50 md:justify-start">
                            <i data-lucide="mail" class="h-3.5 w-3.5"></i>
                            <span>{{ $user->email }}</span>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button
                            @click="$dispatch('notify', {title: 'Berhasil', message: 'Link ubah password dikirim!', type: 'success'})"
                            class="flex items-center gap-3 rounded-[1.5rem] border border-rose-100 bg-rose-50 px-6 py-4 text-xs font-black uppercase tracking-widest text-rose-600 transition-all hover:bg-rose-600 hover:text-white dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-500">
                            <i data-lucide="lock" class="h-5 w-5 text-amber-500"></i> Change Password
                        </button>
                        <form action="#" method="POST">
                            <button
                                class="flex items-center gap-3 rounded-[1.5rem] border border-rose-100 bg-rose-50 px-6 py-4 text-xs font-black uppercase tracking-widest text-rose-600 transition-all hover:bg-rose-600 hover:text-white dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-500">
                                <i data-lucide="log-out" class="w-4.5 h-4.5"></i> Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-3">
                @php
                    $avgPerf =
                        $shifts->count() > 0
                            ? round(
                                ($shifts->filter(fn($i) => $i->cashVariance >= 0)->count() / $shifts->count()) * 100,
                            )
                            : 0;
                    $stats = [
                        [
                            'label' => 'Shift Completed',
                            'value' => $shifts->count(),
                            'icon' => 'clock',
                            'color' => 'text-blue-500',
                        ],
                        [
                            'label' => 'Avg Performance',
                            'value' => $avgPerf . '%',
                            'icon' => 'target',
                            'color' => 'text-emerald-500',
                        ],
                        [
                            'label' => 'Access Level',
                            'value' => strtoupper($user->role),
                            'icon' => 'shield',
                            'color' => 'text-amber-500',
                        ],
                    ];
                @endphp

                @foreach ($stats as $stat)
                    <div
                        class="rounded-[2rem] border border-slate-200 bg-white p-6 dark:border-white/5 dark:bg-slate-900/50">
                        <div class="mb-4 flex items-start justify-between">
                            <div class="{{ $stat['color'] }} rounded-2xl bg-slate-50 p-3 dark:bg-white/5">
                                <i data-lucide="{{ $stat['icon'] }}" class="h-5 w-5"></i>
                            </div>
                        </div>
                        <p class="mb-1 text-[10px] font-black uppercase tracking-widest text-slate-500">
                            {{ $stat['label'] }}</p>
                        <p class="text-2xl font-black tracking-tighter">{{ $stat['value'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-1">
                {{-- <div
                    class="rounded-[2.5rem] border border-slate-200 bg-white p-8 dark:border-white/5 dark:bg-slate-900/50">
                    <h3
                        class="mb-8 flex items-center gap-3 text-sm font-black uppercase tracking-widest text-slate-900 dark:text-white">
                        <i data-lucide="layout-grid" class="w-4.5 h-4.5 text-amber-500"></i> Account Details
                    </h3>
                    <div class="space-y-6">
                        <div
                            class="flex items-center justify-between border-b border-gray-100 py-2 dark:border-white/5">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-500">Employee
                                ID</span>
                            <span
                                class="font-mono text-sm font-bold">#USR-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div
                            class="flex items-center justify-between border-b border-gray-100 py-2 dark:border-white/5">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-500">Status</span>
                            <div class="flex items-center gap-2 text-emerald-500">
                                <div class="h-2 w-2 animate-pulse rounded-full bg-emerald-500"></div>
                                <span class="text-sm font-bold uppercase">{{ $user->status }}</span>
                            </div>
                        </div>
                    </div>
                </div> --}}

                <div
                    class="rounded-[2.5rem] border border-slate-200 bg-white p-8 dark:border-white/5 dark:bg-slate-900/50">
                    <h3
                        class="mb-8 flex items-center gap-3 text-sm font-black uppercase tracking-widest text-slate-900 dark:text-white">
                        <i data-lucide="clock" class="w-4.5 h-4.5 text-amber-500"></i> Quick Actions
                    </h3>
                    <div class="space-y-3">
                        @foreach ([['name' => 'Performance Report', 'url' => '/shifts/me']] as $item)
                            <a href="{{ $item['url'] }}"
                                class="group flex w-full items-center justify-between rounded-2xl border border-slate-100 bg-slate-50 p-4 transition-all hover:bg-white hover:shadow-md dark:border-white/5 dark:bg-white/5 dark:hover:bg-white/10">
                                <p
                                    class="text-xs font-bold uppercase tracking-widest text-slate-900 dark:text-gray-300">
                                    {{ $item['name'] }}</p>
                                <i data-lucide="chevron-right"
                                    class="h-4 w-4 text-amber-500 transition-transform group-hover:translate-x-1"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-cashier-layout>
