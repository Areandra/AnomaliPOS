<x-admin-layout title="Management Dashboard" page_title="Management > Dashboard">

<div x-data="dashboardApp()" class="p-8 pb-16 space-y-8 transition-colors duration-500"
    :class="isDark ? 'bg-slate-950 text-white' : 'bg-slate-50 text-slate-900'">

    {{-- Header --}}
    <header class="flex justify-between items-center mb-8">
        <div class="flex items-center gap-4">
            <div class="p-3 rounded-2xl" :class="isDark ? 'bg-slate-800 text-amber-500' : 'bg-orange-600 text-white'">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
            </div>
            <div>
                <h1 class="text-2xl font-black uppercase tracking-tight">Dashboard</h1>
                <p class="text-xs font-bold opacity-50 tracking-widest uppercase">Real-time Analytics</p>
            </div>
        </div>
    </header>

    @php $atLeastPro = $plan !== 'starter'; @endphp

    {{-- Top Grid --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <div class="{{ $atLeastPro ? 'xl:col-span-2 space-y-8' : 'xl:col-span-3 space-y-8' }}">

            @if($atLeastPro)
                {{-- Summary Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @php
                        $icons = [
                            '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
                            '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>',
                            '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><path d="M12 18V6"/></svg>',
                        ];
                    @endphp
                    @foreach($summaryData as $i => $card)
                    <div class="relative overflow-hidden p-6 rounded-3xl border transition-all"
                        :class="isDark ? 'bg-slate-900 border-white/5 shadow-2xl' : 'bg-white border-slate-200 shadow-lg'">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-3 rounded-2xl" :class="isDark ? 'bg-amber-500/10 text-amber-500' : 'bg-orange-100 text-orange-600'">
                                {!! $icons[$i] !!}
                            </div>
                            <div class="flex items-center gap-1 px-2 py-1 rounded-lg text-[10px] font-black {{ $card['isPositive'] ? 'bg-emerald-500/10 text-emerald-600' : 'bg-red-500/10 text-red-600' }}">
                                @if($card['isPositive'])
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7"/><path d="M7 7h10v10"/></svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7 7 10 10"/><path d="M17 7v10H7"/></svg>
                                @endif
                                {{ $card['change'] }}
                            </div>
                        </div>
                        <p class="text-[10px] font-black uppercase tracking-widest mb-1" :class="isDark ? 'text-gray-400' : 'text-slate-500'">
                            {{ $card['title'] }}
                        </p>
                        <h3 class="text-2xl font-black tracking-tighter" :class="isDark ? 'text-white' : 'text-slate-900'">
                            @if(str_contains($card['title'], 'Revenue'))
                                Rp {{ number_format($card['value'], 0, ',', '.') }}
                            @else
                                {{ $card['value'] }}
                            @endif
                        </h3>
                    </div>
                    @endforeach
                </div>

                {{-- Revenue Line Chart --}}
                <section class="p-8 rounded-[2.5rem] border flex flex-col transition-all"
                    :class="isDark ? 'bg-slate-900/50 border-white/5' : 'bg-white border-slate-200 shadow-sm'">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 rounded-lg" :class="isDark ? 'bg-amber-500/10 text-amber-500' : 'bg-orange-100 text-orange-600'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                        </div>
                        <h3 class="text-xs font-black uppercase tracking-[0.2em]" :class="isDark ? 'text-gray-300' : 'text-slate-800'">
                            Revenue Analysis (30 Days)
                        </h3>
                    </div>
                    <div class="h-[400px]">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </section>

            @else
                {{-- Starter: Simple Revenue Table --}}
                <section class="p-8 rounded-[2.5rem] border flex flex-col transition-all"
                    :class="isDark ? 'bg-slate-900/50 border-white/5' : 'bg-white border-slate-200 shadow-sm'">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 rounded-lg" :class="isDark ? 'bg-amber-500/10 text-amber-500' : 'bg-orange-100 text-orange-600'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                        </div>
                        <h3 class="text-xs font-black uppercase tracking-[0.2em]" :class="isDark ? 'text-gray-300' : 'text-slate-800'">
                            Insight Pendapatan (30 Hari Terakhir)
                        </h3>
                    </div>

                    <div class="space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @php
                                $totalRevenue = array_sum(array_column($revenueData['values'], 'total'));
                                $avgRevenue   = count($revenueData['values']) > 0 ? $totalRevenue / count($revenueData['values']) : 0;
                            @endphp
                            <div class="group p-6 rounded-[2rem] border transition-all duration-300 hover:scale-[1.02]"
                                :class="isDark ? 'bg-gradient-to-br from-slate-800/50 to-slate-900/50 border-white/5 shadow-2xl' : 'bg-gradient-to-br from-white to-slate-50 border-slate-200 shadow-md'">
                                <p class="text-[10px] text-amber-500 uppercase tracking-[0.2em] font-black mb-2">Total Pendapatan</p>
                                <p class="text-3xl font-black tracking-tighter" :class="isDark ? 'text-white' : 'text-slate-900'">
                                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="group p-6 rounded-[2rem] border transition-all duration-300 hover:scale-[1.02]"
                                :class="isDark ? 'bg-gradient-to-br from-slate-800/50 to-slate-900/50 border-white/5 shadow-2xl' : 'bg-gradient-to-br from-white to-slate-50 border-slate-200 shadow-md'">
                                <p class="text-[10px] text-slate-400 uppercase tracking-[0.2em] font-black mb-2">Rata-rata Harian</p>
                                <p class="text-3xl font-black tracking-tighter" :class="isDark ? 'text-white' : 'text-slate-900'">
                                    Rp {{ number_format($avgRevenue, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between px-2">
                                <h4 class="text-[10px] font-black uppercase tracking-widest opacity-50">Log Transaksi Terakhir</h4>
                                <span class="text-[10px] font-bold px-2 py-1 rounded bg-amber-500/10 text-amber-500 uppercase">Auto-updated</span>
                            </div>
                            <div class="rounded-[2rem] border overflow-hidden"
                                :class="isDark ? 'border-white/5 bg-slate-900/30' : 'border-slate-200 bg-white shadow-sm'">
                                <div class="max-h-[450px] overflow-y-auto">
                                    <table class="w-full text-left border-collapse">
                                        <thead class="sticky top-0 z-10" :class="isDark ? 'bg-slate-900' : 'bg-slate-50'">
                                            <tr>
                                                <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 border-b border-white/5">Tanggal</th>
                                                <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 text-right border-b border-white/5">Nominal Pendapatan</th>
                                            </tr>
                                        </thead>
                                        <tbody :class="isDark ? 'divide-y divide-white/5' : 'divide-y divide-slate-100'">
                                            @foreach(array_reverse(array_keys($revenueData['labels'])) as $idx)
                                            @php $label = $revenueData['labels'][$idx]; $val = $revenueData['values'][$idx]['total']; @endphp
                                            <tr class="group transition-colors" :class="isDark ? 'hover:bg-white/[0.02]' : 'hover:bg-slate-50'">
                                                <td class="px-8 py-5">
                                                    <div class="flex flex-col">
                                                        <span class="text-sm font-bold" :class="isDark ? 'text-slate-200' : 'text-slate-700'">{{ $label }}</span>
                                                        <span class="text-[10px] opacity-40 font-medium uppercase tracking-tighter">Success Transaction</span>
                                                    </div>
                                                </td>
                                                <td class="px-8 py-5 text-right">
                                                    <span class="text-sm font-black tracking-tight" :class="isDark ? 'text-amber-500' : 'text-orange-600'">
                                                        Rp {{ number_format($val, 0, ',', '.') }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <p class="text-center text-[10px] font-bold opacity-30 uppercase tracking-[0.3em] py-4">--- End of 30 Days Record ---</p>
                        </div>
                    </div>
                </section>
            @endif
        </div>

        {{-- Top 5 Best Sellers (pro only) --}}
        @if($atLeastPro)
        <div class="xl:col-span-1">
            <section class="p-8 rounded-[2.5rem] border flex flex-col transition-all h-full"
                :class="isDark ? 'bg-slate-900/50 border-white/5' : 'bg-white border-slate-200 shadow-sm'">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 rounded-lg" :class="isDark ? 'bg-amber-500/10 text-amber-500' : 'bg-orange-100 text-orange-600'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                    </div>
                    <h3 class="text-xs font-black uppercase tracking-[0.2em]" :class="isDark ? 'text-gray-300' : 'text-slate-800'">Top 5 Best Sellers</h3>
                </div>
                <div class="space-y-4 flex-1">
                    @foreach($top5Menu as $index => $item)
                    <div class="flex items-center gap-5 p-5 rounded-[2rem] border transition-all"
                        :class="isDark ? 'bg-white/5 border-white/5' : 'bg-slate-50 border-slate-200 shadow-sm'">
                        <div class="shrink-0 w-8 text-center">
                            <span class="text-3xl font-black italic {{ $index === 0 ? '' : 'opacity-20' }}"
                                :class="'{{ $index === 0 ? '' : '' }}' || (isDark ? 'text-amber-500' : 'text-orange-600')">
                                {{ $index + 1 }}
                            </span>
                        </div>
                        <div class="w-16 h-16 rounded-2xl bg-cover bg-center bg-slate-200"
                            style="background-image: url('{{ $item['menu']['imageUrl'] ?? 'https://via.placeholder.com/100' }}')">
                        </div>
                        <div class="grow">
                            <h4 class="text-sm font-black uppercase leading-tight">{{ $item['menu']['name'] }}</h4>
                            <p class="text-xs font-bold" :class="isDark ? 'text-amber-500' : 'text-orange-600'">
                                {{ $item['quantity'] }} SOLD
                            </p>
                        </div>
                        <div class="text-right font-black text-xs">
                            IDR {{ number_format($item['menu']['price'], 0, ',', '.') }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
        </div>
        @endif
    </div>

    {{-- Category Bar Chart (pro only) --}}
    @if($atLeastPro)
    <section class="p-8 rounded-[2.5rem] border flex flex-col transition-all"
        :class="isDark ? 'bg-slate-900/50 border-white/5' : 'bg-white border-slate-200 shadow-sm'">
        <div class="flex items-center gap-3 mb-6">
            <div class="p-2 rounded-lg" :class="isDark ? 'bg-amber-500/10 text-amber-500' : 'bg-orange-100 text-orange-600'">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
            </div>
            <h3 class="text-xs font-black uppercase tracking-[0.2em]" :class="isDark ? 'text-gray-300' : 'text-slate-800'">Category Mix Analysis</h3>
        </div>
        <div class="h-[400px]">
            <canvas id="categoryChart"></canvas>
        </div>
    </section>
    @endif

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
function dashboardApp() {
    return {
        init() {
            this.$nextTick(() => this.initCharts());
        },

        initCharts() {
            const isDark = this.isDark;
            const revenueData = @json($revenueData);
            const categoryData = @json($categoryData);

            // Revenue Line Chart
            const revenueCtx = document.getElementById('revenueChart');
            if (revenueCtx) {
                new Chart(revenueCtx, {
                    type: 'line',
                    data: {
                        labels: revenueData.labels,
                        datasets: [
                            {
                                label: 'Total Pendapatan',
                                data: revenueData.values.map(v => v.total),
                                borderColor: '#F59E0B',
                                backgroundColor: 'rgba(245,158,11,0.1)',
                                fill: true, tension: 0.4, borderWidth: 3, pointRadius: 2,
                            },
                            {
                                label: 'Total Bersih',
                                data: revenueData.values.map(v => v.totalBersih),
                                borderColor: '#10B981',
                                backgroundColor: 'transparent',
                                fill: false, tension: 0.4, borderWidth: 3, pointRadius: 2,
                            },
                            {
                                label: 'Modal (COGS)',
                                data: revenueData.values.map(v => v.costOfGoods),
                                borderColor: '#EF4444',
                                backgroundColor: 'transparent',
                                fill: false, tension: 0.4, borderWidth: 2,
                                borderDash: [5, 5], pointRadius: 0,
                            },
                        ],
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: {
                                display: true, position: 'top', align: 'end',
                                labels: { color: isDark ? '#cbd5e1' : '#475569', boxWidth: 10, usePointStyle: true, font: { size: 11, weight: 'bold' } },
                            },
                            tooltip: {
                                backgroundColor: isDark ? '#1e293b' : '#ffffff',
                                titleColor: isDark ? '#f8fafc' : '#1e293b',
                                bodyColor: isDark ? '#cbd5e1' : '#475569',
                                borderColor: isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)',
                                borderWidth: 1, padding: 12, cornerRadius: 8,
                                callbacks: {
                                    label: ctx => `${ctx.dataset.label}: Rp ${ctx.parsed.y.toLocaleString('id-ID')}`,
                                },
                            },
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)' },
                                ticks: { color: '#64748b', font: { size: 10 }, callback: v => 'Rp' + v.toLocaleString() },
                            },
                            x: { grid: { display: false }, ticks: { color: '#64748b', font: { size: 10 } } },
                        },
                    },
                });
            }

            // Category Bar Chart
            const catCtx = document.getElementById('categoryChart');
            if (catCtx) {
                new Chart(catCtx, {
                    type: 'bar',
                    data: {
                        labels: categoryData.labels,
                        datasets: [{ data: categoryData.values, backgroundColor: '#F59E0B', borderRadius: 12, barThickness: 12 }],
                    },
                    options: {
                        indexAxis: 'y', responsive: true, maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: isDark ? '#1e293b' : '#ffffff',
                                titleColor: isDark ? '#f8fafc' : '#1e293b',
                                titleFont: { size: 14, weight: 'bold' },
                                bodyColor: '#F59E0B', bodyFont: { size: 13, weight: 'bold' },
                                padding: 12, cornerRadius: 10, displayColors: false,
                                callbacks: { label: ctx => ` Sold: ${ctx.parsed.x} porsi` },
                            },
                        },
                        scales: {
                            y: { ticks: { color: isDark ? '#fff' : '#1e293b', font: { size: 13, weight: 'bold' } }, grid: { display: false } },
                            x: { grid: { color: isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)' } },
                        },
                    },
                });
            }
        }
    }
}
</script>

</x-admin-layout>
