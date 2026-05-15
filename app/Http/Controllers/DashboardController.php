<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $restaurantId = session('restaurant_id');
        $plan         = session('restaurant_plan');

        // Fallback ke database kalau session belum di-set
        if (!$restaurantId || !$plan) {
            $user = auth()->user();
            if ($user) {
                $restaurant   = \App\Models\Restaurant::find($user->restaurant_id);
                $restaurantId = $restaurantId ?? $user->restaurant_id;
                $plan         = $plan ?? $restaurant?->plan ?? 'starter';
                session(['restaurant_id' => $restaurantId, 'restaurant_plan' => $plan]);
            } else {
                $plan = 'starter';
            }
        }
        $now          = Carbon::now();
        $thirtyDaysAgo = $now->copy()->subDays(30);

        // --- Revenue per hari (30 hari) ---
        $payments = Payment::where('restaurant_id', $restaurantId)
            ->where('status', 'paid')
            ->whereBetween('paid_at', [$thirtyDaysAgo, $now])
            ->with('order.items.menuItem')
            ->get();

        $revenueByDay = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i)->format('d M');
            $revenueByDay[$date] = ['total' => 0, 'totalBersih' => 0, 'costOfGoods' => 0];
        }

        foreach ($payments as $payment) {
            $date = Carbon::parse($payment->paid_at)->format('d M');
            if (!isset($revenueByDay[$date])) continue;

            $total = (float) $payment->order->total;
            $cogs  = $payment->order->items->sum(fn($item) =>
                (float) ($item->menuItem->cost_of_goods ?? 0) * $item->quantity
            );

            $revenueByDay[$date]['total']       += $total;
            $revenueByDay[$date]['costOfGoods'] += $cogs;
            $revenueByDay[$date]['totalBersih'] += $total - $cogs;
        }

        $revenueData = [
            'labels' => array_keys($revenueByDay),
            'values' => array_values($revenueByDay),
        ];

        // --- Summary Cards (pro only) ---
        $summaryData = [];
        if ($plan !== 'starter') {
            $todayPayments = Payment::where('restaurant_id', $restaurantId)
                ->where('status', 'paid')
                ->whereDate('paid_at', $now->toDateString())
                ->get();

            $yesterdayPayments = Payment::where('restaurant_id', $restaurantId)
                ->where('status', 'paid')
                ->whereDate('paid_at', $now->copy()->subDay()->toDateString())
                ->get();

            $todayRevenue     = $todayPayments->sum('amount');
            $yesterdayRevenue = $yesterdayPayments->sum('amount');
            $revenueChange    = $yesterdayRevenue > 0
                ? round((($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100, 1) . '%'
                : '+0%';

            $todayOrders     = Order::where('restaurant_id', $restaurantId)->whereDate('created_at', $now)->count();
            $yesterdayOrders = Order::where('restaurant_id', $restaurantId)->whereDate('created_at', $now->copy()->subDay())->count();
            $ordersChange    = $yesterdayOrders > 0
                ? round((($todayOrders - $yesterdayOrders) / $yesterdayOrders) * 100, 1) . '%'
                : '+0%';

            $activeUsers = User::where('restaurant_id', $restaurantId)->where('status', 'active')->count();

            $summaryData = [
                ['title' => 'Active Staff',    'value' => $activeUsers,    'change' => '+0%',         'isPositive' => true],
                ['title' => 'Orders Today',    'value' => $todayOrders,    'change' => $ordersChange,  'isPositive' => (float) $ordersChange >= 0],
                ['title' => 'Revenue Today',   'value' => $todayRevenue,   'change' => $revenueChange, 'isPositive' => (float) $revenueChange >= 0],
            ];
        }

        // --- Top 5 Menu (pro only) ---
        $top5Menu = [];
        if ($plan !== 'starter') {
            $top5 = OrderItem::where('restaurant_id', $restaurantId)
                ->select('menu_item_id', DB::raw('SUM(quantity) as total_qty'))
                ->groupBy('menu_item_id')
                ->orderByDesc('total_qty')
                ->limit(5)
                ->with('menuItem')
                ->get();

            $top5Menu = $top5->map(fn($item) => [
                'quantity' => $item->total_qty,
                'menu'     => [
                    'id'       => $item->menuItem->id,
                    'name'     => $item->menuItem->name,
                    'price'    => (float) $item->menuItem->price,
                    'imageUrl' => $item->menuItem->image_url,
                ],
            ])->toArray();
        }

        // --- Category Mix (pro only) ---
        $categoryData = ['labels' => [], 'values' => []];
        if ($plan !== 'starter') {
            $catMix = OrderItem::where('order_items.restaurant_id', $restaurantId)
                ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
                ->join('menu_categories', 'menu_items.category_id', '=', 'menu_categories.id')
                ->select('menu_categories.name', DB::raw('SUM(order_items.quantity) as total'))
                ->groupBy('menu_categories.name')
                ->orderByDesc('total')
                ->get();

            $categoryData = [
                'labels' => $catMix->pluck('name')->toArray(),
                'values' => $catMix->pluck('total')->toArray(),
            ];
        }

        return view('dashboard', compact('summaryData', 'revenueData', 'top5Menu', 'categoryData', 'plan'));
    }
}
