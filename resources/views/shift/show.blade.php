@extends('layouts.admin')
@section('title', 'Detail Shift #' . $shift->id)
@section('page_title', 'Shift > #' . $shift->id)

@section('content')
    @php
        $cashExpected = (float) $shift->modal_awal + (float) $shift->cash_system;
    @endphp
    <div x-init="$store.theme.init();
    $nextTick(() => lucide.createIcons())" class="flex h-full flex-col overflow-hidden">
    <x-receipt-modal />

        {{-- Header --}}
        <div :class="$store.theme.isDark ? 'border-white/5' : 'border-gray-100'" class="border-b p-5 md:p-8">
            <button onclick="window.history.back()"
                class="mb-4 inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest opacity-50 md:mb-6">
                <i data-lucide="arrow-left" class="h-3.5 w-3.5"></i> Back
            </button>

            <div class="flex flex-col items-start justify-between gap-6 sm:flex-row sm:items-center">
                <div class="flex items-center gap-4">
                    <div :class="$store.theme.isDark ? 'bg-slate-800 border-amber-500/20 text-amber-500' :
                        'bg-orange-100 border-orange-200 text-orange-600'"
                        class="flex h-14 w-14 items-center justify-center rounded-[1.5rem] border-2 md:h-16 md:w-16 md:rounded-[2rem]">
                        <i data-lucide="user" class="h-7 w-7"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-black uppercase leading-none tracking-tighter md:text-2xl">
                            {{ $shift->user->name ?? 'Unknown' }}
                        </h1>
                        <div
                            class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-1 text-[9px] font-bold uppercase tracking-widest opacity-50 md:text-[10px]">
                            <span class="flex items-center gap-1">
                                <i data-lucide="calendar" class="h-3 w-3"></i>
                                {{ $shift->opened_at->format('d M Y') }}
                            </span>
                            <span class="flex items-center gap-1">
                                <i data-lucide="clock" class="h-3 w-3"></i> ID: {{ $shift->id }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Variance Badge --}}
                <div
                    class="{{ $shift->cash_variance == 0 ? 'border-emerald-500/20 bg-emerald-500/5 text-emerald-500' : ($shift->cash_variance < 0 ? 'border-red-500/20 bg-red-500/5 text-red-500' : 'border-amber-500/20 text-amber-500') }} flex w-full flex-row items-center justify-between gap-2 rounded-2xl border-2 border-dashed px-6 py-3 sm:w-auto sm:flex-col sm:justify-center">
                    <span class="text-[8px] font-black uppercase tracking-widest opacity-60">Variance</span>
                    <div class="flex items-center gap-2">
                        @if ($shift->cash_variance == 0)
                            <i data-lucide="minus-circle" class="h-4 w-4"></i>
                            <span class="text-sm font-black uppercase">Balanced</span>
                        @elseif($shift->cash_variance < 0)
                            <i data-lucide="trending-down" class="h-4 w-4"></i>
                            <span class="text-sm font-black uppercase">Error</span>
                        @else
                            <i data-lucide="trending-up" class="h-4 w-4"></i>
                            <span class="text-sm font-black uppercase">Error</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="flex-1 overflow-y-auto p-4 md:p-8">
            <div class="grid max-w-[1600px] grid-cols-1 gap-6 md:gap-8 xl:grid-cols-12">

                {{-- Financial Stack --}}
                <div class="order-1 space-y-6 md:space-y-8 xl:col-span-4">
                    {{-- Cash Summary --}}
                    <section class="space-y-4">
                        <h3 class="ml-2 text-[9px] font-black uppercase tracking-[0.2em] opacity-40">Cash Summary</h3>
                        <div :class="$store.theme.isDark ? 'bg-slate-900/50 border-white/5' : 'bg-white border-gray-100 shadow-sm'"
                            class="rounded-[2rem] border p-6 md:rounded-[2.5rem]">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-bold uppercase tracking-widest opacity-40">Modal</span>
                                    <span class="text-sm font-black tracking-tight">Rp
                                        {{ number_format($shift->modal_awal, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-bold uppercase tracking-widest opacity-40">Sales</span>
                                    <span class="text-sm font-black tracking-tight">Rp
                                        {{ number_format($shift->cash_system, 0, ',', '.') }}</span>
                                </div>
                                <div :class="$store.theme.isDark ? 'bg-white/5' : 'bg-gray-50'" class="h-px w-full"></div>
                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-[10px] font-bold uppercase tracking-widest text-amber-500">Expected</span>
                                    <span class="text-sm font-black tracking-tight text-amber-500">Rp
                                        {{ number_format($cashExpected, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-bold uppercase tracking-widest opacity-40">Physical</span>
                                    <span class="text-sm font-black tracking-tight">
                                        {{ $shift->status === 'closed' ? 'Rp ' . number_format($shift->cash_physical, 0, ',', '.') : '---' }}
                                    </span>
                                </div>
                                <div
                                    class="{{ $shift->cash_variance < 0 ? 'bg-red-500/10 text-red-500' : ($shift->cash_variance > 0 ? 'bg-emerald-500/10 text-emerald-500' : 'bg-gray-500/5 text-gray-400') }} mt-2 flex items-center justify-between rounded-2xl p-4">
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
                        <h3 class="ml-2 text-[9px] font-black uppercase tracking-[0.2em] opacity-40">Digital (System)</h3>
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3 xl:grid-cols-1">
                            @foreach ([['label' => 'QRIS', 'icon' => 'qr-code', 'value' => $shift->qris_system], ['label' => 'Debit', 'icon' => 'credit-card', 'value' => $shift->debit_system], ['label' => 'Transfer', 'icon' => 'wallet', 'value' => $shift->transfer_system]] as $d)
                                <div :class="$store.theme.isDark ? 'bg-slate-900/50 border-white/5 hover:bg-white/5' :
                                    'bg-white border-gray-100 hover:shadow-md'"
                                    class="flex items-center justify-between rounded-[2rem] border p-5 transition-all">
                                    <div class="flex items-center gap-3">
                                        <div :class="$store.theme.isDark ? 'bg-white/5 text-gray-400' : 'bg-gray-50 text-gray-500'"
                                            class="rounded-xl p-2">
                                            <i data-lucide="{{ $d['icon'] }}" class="h-3.5 w-3.5"></i>
                                        </div>
                                        <span
                                            class="text-[10px] font-black uppercase tracking-widest opacity-60">{{ $d['label'] }}</span>
                                    </div>
                                    <span class="text-sm font-black tracking-tight">Rp
                                        {{ number_format($d['value'], 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </section>
                </div>

                {{-- Transaction Log --}}
                <div class="order-2 space-y-4 pb-10 xl:col-span-8 xl:pb-0">
                    <div class="ml-2 mt-4 flex items-center justify-between xl:mt-0">
                        <h3 class="text-[9px] font-black uppercase tracking-[0.2em] opacity-40">Transactions</h3>
                        <span :class="$store.theme.isDark ? 'bg-white/5 text-amber-500' : 'bg-gray-100 text-orange-600'"
                            class="rounded-full px-3 py-1 text-[9px] font-black">
                            {{ $shift->payments->count() }} Items
                        </span>
                    </div>

                    <div :class="$store.theme.isDark ? 'bg-slate-900/50 border-white/5' : 'bg-white border-gray-100 shadow-sm'"
                        class="rounded-[2rem] border md:rounded-[2.5rem]">
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[500px] text-left">
                                <thead>
                                    <tr :class="$store.theme.isDark ? 'border-white/5' : 'border-gray-50'"
                                        class="border-b text-[9px] font-black uppercase tracking-widest opacity-40">
                                        <th class="px-6 py-5">Order</th>
                                        <th class="px-6 py-5">Method</th>
                                        <th class="px-6 py-5">Time</th>
                                        <th class="px-6 py-5 text-right">Amount</th>
                                        <th class="px-6 py-5 text-right">Action</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($shift->payments as $pay)
                                        <tr :class="$store.theme.isDark ? 'hover:bg-white/[0.02]' : 'hover:bg-gray-50/50'"
                                            class="transition-colors">
                                            <td class="px-6 py-4">
                                                <span class="text-[11px] font-black tracking-widest">
                                                    {{ $pay->order->order_code ?? '#' . $pay->order_id }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div :class="$store.theme.isDark ? 'bg-white/5 text-gray-400' :
                                                    'bg-gray-100 text-gray-500'"
                                                    class="flex w-fit items-center gap-1.5 rounded-lg px-2.5 py-1 text-[8px] font-black uppercase">
                                                    @php
                                                        $icons = [
                                                            'cash' => 'banknote',
                                                            'qris' => 'qr-code',
                                                            'debit' => 'credit-card',
                                                            'transfer' => 'wallet',
                                                        ];
                                                    @endphp
                                                    <i data-lucide="{{ $icons[$pay->payment_method] ?? 'circle-dollar-sign' }}"
                                                        class="h-3.5 w-3.5"></i>
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
                                            <td class="px-6 py-4 text-center">
                                                <button
                                                    onclick="showReceiptModal('{{ $shift->restaurant->name ?? '' }}',{{ json_encode($pay->order->load(['table', 'items.menuItem'])) }},{{ json_encode($pay->load('createdBy')) }})"
                                                    :class="$store.theme.isDark ?
                                                        'bg-white/5 text-amber-500 hover:bg-amber-500 hover:text-black shadow-lg shadow-black/20' :
                                                        'bg-orange-50 text-orange-600 hover:bg-orange-600 hover:text-white shadow-sm'"
                                                    class="inline-flex items-center justify-center rounded-xl p-2.5 transition-all active:scale-95">
                                                    <i data-lucide="receipt-cent" class="h-4 w-4"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4">
                                                <div class="flex flex-col items-center gap-4 py-20 text-center opacity-20">
                                                    <i data-lucide="receipt" class="h-12 w-12"></i>
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
    <script>
        document.addEventListener('alpine:init', () => {
            if (!Alpine.store('receipt')) {
                Alpine.store('receipt', {
                    visible: false,
                    title: '',
                    restaurant: '',
                    order: null,
                    payment: null
                });
            }
        });

        function showReceiptModal(restaurantName, orderData, paymentData) {
            console.log(paymentData)
            if (window.Alpine && Alpine.store('receipt')) {
                // Masukkan data transaksi/order ke store Alpine agar bisa dibaca receipt-modal
                Alpine.store('receipt').title = 'Receipt Information';
                Alpine.store('receipt').restaurant = restaurantName;
                Alpine.store('receipt').order = orderData;
                Alpine.store('receipt').payment = paymentData;

                // Ubah state menjadi true agar modal struk langsung menyembul muncul
                Alpine.store('receipt').visible = true;
            } else {
                console.error('Alpine.js atau global store "receipt" tidak ditemukan.');
            }
        }
    </script>
@endsection
