@extends('layouts.admin')
@section('title', 'Detail Shift #' . $shift->id)
@section('page_title', 'Shift > #' . $shift->id)

@section('content')
@php
    $cashExpected = (float)$shift->modal_awal + (float)$shift->cash_system;
@endphp
<div x-init="$store.theme.init(); $nextTick(() => lucide.createIcons())"
    class="h-full flex flex-col overflow-hidden">

    {{-- Header --}}
    <div :class="$store.theme.isDark ? 'border-white/5' : 'border-gray-100'" class="p-5 md:p-8 border-b">
        <button onclick="window.history.back()"
            class="inline-flex items-center gap-2 mb-4 md:mb-6 text-[10px] font-black uppercase tracking-widest opacity-50">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Back
        </button>

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
            <div class="flex items-center gap-4">
                <div :class="$store.theme.isDark ? 'bg-slate-800 border-amber-500/20 text-amber-500' : 'bg-orange-100 border-orange-200 text-orange-600'"
                    class="w-14 h-14 md:w-16 md:h-16 rounded-[1.5rem] md:rounded-[2rem] flex items-center justify-center border-2">
                    <i data-lucide="user" class="w-7 h-7"></i>
                </div>
                <div>
                    <h1 class="text-xl md:text-2xl font-black tracking-tighter uppercase leading-none">
                        {{ $shift->user->name ?? 'Unknown' }}
                    </h1>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-2 opacity-50 text-[9px] md:text-[10px] font-bold uppercase tracking-widest">
                        <span class="flex items-center gap-1">
                            <i data-lucide="calendar" class="w-3 h-3"></i>
                            {{ $shift->opened_at->format('d M Y') }}
                        </span>
                        <span class="flex items-center gap-1">
                            <i data-lucide="clock" class="w-3 h-3"></i> ID: {{ $shift->id }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Variance Badge --}}
            <div class="{{ $shift->cash_variance == 0 ? 'border-emerald-500/20 bg-emerald-500/5 text-emerald-500' : ($shift->cash_variance < 0 ? 'border-red-500/20 bg-red-500/5 text-red-500' : 'border-amber-500/20 text-amber-500') }}
                w-full sm:w-auto px-6 py-3 rounded-2xl border-2 border-dashed flex flex-row sm:flex-col items-center justify-between sm:justify-center gap-2">
                <span class="text-[8px] font-black uppercase tracking-widest opacity-60">Variance</span>
                <div class="flex items-center gap-2">
                    @if($shift->cash_variance == 0)
                        <i data-lucide="minus-circle" class="w-4 h-4"></i>
                        <span class="text-sm font-black uppercase">Balanced</span>
                    @elseif($shift->cash_variance < 0)
                        <i data-lucide="trending-down" class="w-4 h-4"></i>
                        <span class="text-sm font-black uppercase">Error</span>
                    @else
                        <i data-lucide="trending-up" class="w-4 h-4"></i>
                        <span class="text-sm font-black uppercase">Error</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div class="p-4 md:p-8 flex-1 overflow-y-auto">
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 md:gap-8 max-w-[1600px]">

            {{-- Financial Stack --}}
            <div class="xl:col-span-4 space-y-6 md:space-y-8 order-1">
                {{-- Cash Summary --}}
                <section class="space-y-4">
                    <h3 class="text-[9px] font-black uppercase tracking-[0.2em] opacity-40 ml-2">Cash Summary</h3>
                    <div :class="$store.theme.isDark ? 'bg-slate-900/50 border-white/5' : 'bg-white border-gray-100 shadow-sm'"
                        class="p-6 rounded-[2rem] md:rounded-[2.5rem] border">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-bold uppercase tracking-widest opacity-40">Modal</span>
                                <span class="text-sm font-black tracking-tight">Rp {{ number_format($shift->modal_awal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-bold uppercase tracking-widest opacity-40">Sales</span>
                                <span class="text-sm font-black tracking-tight">Rp {{ number_format($shift->cash_system, 0, ',', '.') }}</span>
                            </div>
                            <div :class="$store.theme.isDark ? 'bg-white/5' : 'bg-gray-50'" class="h-px w-full"></div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-amber-500">Expected</span>
                                <span class="text-sm font-black tracking-tight text-amber-500">Rp {{ number_format($cashExpected, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-bold uppercase tracking-widest opacity-40">Physical</span>
                                <span class="text-sm font-black tracking-tight">
                                    {{ $shift->status === 'closed' ? 'Rp ' . number_format($shift->cash_physical, 0, ',', '.') : '---' }}
                                </span>
                            </div>
                            <div class="{{ $shift->cash_variance < 0 ? 'bg-red-500/10 text-red-500' : ($shift->cash_variance > 0 ? 'bg-emerald-500/10 text-emerald-500' : 'bg-gray-500/5 text-gray-400') }}
                                mt-2 p-4 rounded-2xl flex justify-between items-center">
                                <span class="text-[9px] font-black uppercase italic">Variance</span>
                                <span class="text-sm font-black">
                                    {{ $shift->status === 'open' ? '---' : 'Rp ' . number_format($shift->cash_variance, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Digital --}}
                <section class="space-y-4">
                    <h3 class="text-[9px] font-black uppercase tracking-[0.2em] opacity-40 ml-2">Digital (System)</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 xl:grid-cols-1 gap-3">
                        @foreach([['label' => 'QRIS', 'icon' => 'qr-code', 'value' => $shift->qris_system], ['label' => 'Debit', 'icon' => 'credit-card', 'value' => $shift->debit_system], ['label' => 'Transfer', 'icon' => 'wallet', 'value' => $shift->transfer_system]] as $d)
                        <div :class="$store.theme.isDark ? 'bg-slate-900/50 border-white/5 hover:bg-white/5' : 'bg-white border-gray-100 hover:shadow-md'"
                            class="p-5 rounded-[2rem] flex justify-between items-center border transition-all">
                            <div class="flex items-center gap-3">
                                <div :class="$store.theme.isDark ? 'bg-white/5 text-gray-400' : 'bg-gray-50 text-gray-500'" class="p-2 rounded-xl">
                                    <i data-lucide="{{ $d['icon'] }}" class="w-3.5 h-3.5"></i>
                                </div>
                                <span class="text-[10px] font-black uppercase tracking-widest opacity-60">{{ $d['label'] }}</span>
                            </div>
                            <span class="text-sm font-black tracking-tight">Rp {{ number_format($d['value'], 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                </section>
            </div>

            {{-- Transaction Log --}}
            <div class="xl:col-span-8 space-y-4 order-2 pb-10 xl:pb-0">
                <div class="flex justify-between items-center ml-2 mt-4 xl:mt-0">
                    <h3 class="text-[9px] font-black uppercase tracking-[0.2em] opacity-40">Transactions</h3>
                    <span :class="$store.theme.isDark ? 'bg-white/5 text-amber-500' : 'bg-gray-100 text-orange-600'"
                        class="px-3 py-1 rounded-full text-[9px] font-black">
                        {{ $shift->payments->count() }} Items
                    </span>
                </div>

                <div :class="$store.theme.isDark ? 'bg-slate-900/50 border-white/5' : 'bg-white border-gray-100 shadow-sm'"
                    class="rounded-[2rem] md:rounded-[2.5rem] border">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left min-w-[500px]">
                            <thead>
                                <tr :class="$store.theme.isDark ? 'border-white/5' : 'border-gray-50'"
                                    class="text-[9px] font-black uppercase tracking-widest opacity-40 border-b">
                                    <th class="px-6 py-5">Order</th>
                                    <th class="px-6 py-5">Method</th>
                                    <th class="px-6 py-5">Time</th>
                                    <th class="px-6 py-5 text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($shift->payments as $pay)
                                <tr :class="$store.theme.isDark ? 'hover:bg-white/[0.02]' : 'hover:bg-gray-50/50'" class="transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="text-[11px] font-black tracking-widest">
                                            {{ $pay->order->order_code ?? '#' . $pay->order_id }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div :class="$store.theme.isDark ? 'bg-white/5 text-gray-400' : 'bg-gray-100 text-gray-500'"
                                            class="flex items-center gap-1.5 w-fit px-2.5 py-1 rounded-lg text-[8px] font-black uppercase">
                                            @php
                                                $icons = ['cash' => 'banknote', 'qris' => 'qr-code', 'debit' => 'credit-card', 'transfer' => 'wallet'];
                                            @endphp
                                            <i data-lucide="{{ $icons[$pay->payment_method] ?? 'circle-dollar-sign' }}" class="w-3.5 h-3.5"></i>
                                            {{ $pay->payment_method }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-[10px] font-bold opacity-40">
                                        {{ $pay->paid_at ? \Carbon\Carbon::parse($pay->paid_at)->format('H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="text-xs font-black tracking-tight">
                                            Rp {{ number_format($pay->amount, 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="py-20 text-center opacity-20 flex flex-col items-center gap-4">
                                            <i data-lucide="receipt" class="w-12 h-12"></i>
                                            <p class="text-[9px] font-black uppercase tracking-widest">No data</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
