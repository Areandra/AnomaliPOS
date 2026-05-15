<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Shift;
use App\Models\Table;

class CashierController extends Controller
{
  public function index()
  {
    $plan        = session('restaurant_plan', 'starter');
    $menuItems   = MenuItem::where('restaurant_id', session('restaurant_id'))->where('is_available', true)->get();
    $categories  = MenuCategory::where('restaurant_id', session('restaurant_id'))->get();

    $orders = $plan !== 'starter'
      ? Order::with(['payment', 'table'])
      ->whereHas('tableSession', fn($q) => $q->where('is_active', 1))
      ->with('tableSession')
      ->where('restaurant_id', session('restaurant_id'))
      ->get()
      : Order::whereDoesntHave('payment')
      ->where('restaurant_id', session('restaurant_id'))
      ->get();

    $currentShift = Shift::with('user')
      ->where('user_id', auth()->id())
      ->where('status', 'open')
      ->first();

    return view('cashier.index', [
      'categoriesData' => $categories,
      'menuItemsData'  => $menuItems,
      'ordersData'     => $orders,
      'currentShift'   => $currentShift,
    ]);
  }

  public function start()
  {
    $tables = Table::with([
      'tableSessions' => fn($q) => $q->where('is_active', 1),
      'orders.payment',
    ])->where('restaurant_id', session('restaurant_id'))->get();

    $currentShift = Shift::where('user_id', auth()->id())->where('status', 'open')->first();

    return view('cashier.start', ['data' => $tables->map(fn($t) => [
      'id'             => $t->id,
      'table_number'   => $t->table_number,
      'capacity'       => $t->capacity,
      'position_x'     => $t->position_x,
      'position_y'     => $t->position_y,
      'table_sessions' => $t->tableSessions->values(),
      'orders'         => $t->orders->map(fn($o) => [
        'id'      => $o->id,
        'guest'   => $o->guest ?? null,
        'payment' => $o->payment,
      ])->values(),
    ]), 'currentShift' => $currentShift]);
  }
}
