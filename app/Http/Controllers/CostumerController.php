<?php

namespace App\Http\Controllers;

use App\Models\Kot;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\TableSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CostumerController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Helper
    |--------------------------------------------------------------------------
    */

    private function loadSessionWithActiveOrder(string $sessionToken): array
    {
        $tableSession = TableSession::query()
            ->where('token', $sessionToken)
            ->with([
                'orders.items.menuItem',
                'orders.payment',
                'orders.table',
                'orders.tableSession.createdBy',
            ])
            ->firstOrFail();

        $activeOrder = $tableSession->orders->first(function ($order) {
            return !$order->payment;
        });

        return [
            'session'     => $tableSession,
            'activeOrder' => $activeOrder,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Customer Menu
    |--------------------------------------------------------------------------
    */

    public function index(Request $request, string $sessionToken)
    {
        $tableSession = TableSession::query()
            ->where('token', $sessionToken)
            ->first();

        if (!$tableSession || !$tableSession->is_active) {
            return view('errors.not-found');
        }

        $tableSession->load([
            'orders.payment',
            'orders.items.menuItem',
        ]);

        $activeOrder = $tableSession->orders->first(function ($order) {
            return !$order->payment;
        });

        $menuItems = MenuItem::query()->get();

        $category = MenuCategory::query()->get();

        return view('costumer.index', [
            'menuItems'    => $menuItems,
            'sessionToken' => $sessionToken,
            'category'     => $category,
            'currentOrder' => $activeOrder,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Cart Page
    |--------------------------------------------------------------------------
    */

    public function cart(Request $request, string $sessionToken)
    {
        $tableSession = TableSession::query()
            ->where('token', $sessionToken)
            ->firstOrFail();

        $tableSession->load([
            'orders.items.menuItem',
            'orders.table',
            'orders.payment',
            'orders.tableSession.createdBy',
        ]);

        return view('costumer.cart', [
            'sessionToken' => $tableSession->token,
            'ordersData'   => $tableSession->orders,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Order History
    |--------------------------------------------------------------------------
    */

    public function order(Request $request, string $sessionToken)
    {
        $tableSession = TableSession::query()
            ->where('token', $sessionToken)
            ->firstOrFail();

        if (!$tableSession->is_active) {
            return view('errors.not-found');
        }

        $tableSession->load([
            'orders' => function ($query) use ($tableSession) {
                $query
                    ->where('table_session_id', $tableSession->id)
                    ->with([
                        'items' => function ($q) {
                            $q->where('status', '!=', 'cart')
                                ->with('menuItem');
                        },
                        'payment',
                    ]);
            },
            'table',
        ]);

        return view('costumer.order', [
            'data' => $tableSession,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Add Item
    |--------------------------------------------------------------------------
    */

    public function addItem(Request $request, string $sessionToken)
    {
        $validated = $request->validate([
            'menu_item_id' => ['required', 'integer'],
            'qty'        => ['required', 'integer', 'min:1'],
        ]);

        $data = $this->loadSessionWithActiveOrder($sessionToken);

        $activeOrder = $data['activeOrder'];

        if (!$activeOrder) {
            return view('errors.bill-closed', [
                'sessionToken' => $sessionToken,
            ]);
        }

        $menuItem = MenuItem::query()->find($validated['menu_item_id']);

        if (!$menuItem) {
            abort(404, 'Menu item not found');
        }

        $orderItem = OrderItem::query()
            ->where('order_id', $activeOrder->id)
            ->where('status', 'cart')
            ->where('menu_item_id', $validated['menu_item_id'])
            ->first();

        if ($orderItem) {
            $orderItem->quantity += $validated['qty'];
            $orderItem->subtotal = $orderItem->quantity * $orderItem->price;
            $orderItem->save();
        } else {
            OrderItem::create([
                'order_id'     => $activeOrder->id,
                'menu_item_id' => $validated['menu_item_id'],
                'quantity'     => $validated['qty'],
                'price'        => $menuItem->price,
                'subtotal'     => $validated['qty'] * $menuItem->price,
                'status'       => 'cart',
            ]);
        }

        return ([
            'order' => $activeOrder->refresh()
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Update Qty
    |--------------------------------------------------------------------------
    */

    public function updateQty(Request $request, string $sessionToken)
    {
        $validated = $request->validate([
            'itemId' => ['required', 'integer'],
            'qty'    => ['required', 'integer', 'min:1'],
        ]);

        $data = $this->loadSessionWithActiveOrder($sessionToken);

        $activeOrder = $data['activeOrder'];

        if (!$activeOrder) {
            abort(404, 'No active order');
        }

        $orderItem = OrderItem::query()
            ->where('id', $validated['itemId'])
            ->where('order_id', $activeOrder->id)
            ->where('status', 'cart')
            ->first();

        if (!$orderItem) {
            abort(404, 'Order item not found');
        }

        $orderItem->update([
            'quantity' => $validated['qty'],
            'subtotal' => $validated['qty'] * $orderItem->price,
        ]);

        return ([
            'order' => $activeOrder->refresh()
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Item
    |--------------------------------------------------------------------------
    */

    public function deleteItem(Request $request, string $sessionToken)
    {
        $validated = $request->validate([
            'itemId' => ['required', 'integer'],
        ]);

        $data = $this->loadSessionWithActiveOrder($sessionToken);

        $activeOrder = $data['activeOrder'];

        if (!$activeOrder) {
            abort(404, 'No active order');
        }

        $orderItem = OrderItem::query()
            ->where('id', $validated['itemId'])
            ->where('order_id', $activeOrder->id)
            ->where('status', 'cart')
            ->first();

        if (!$orderItem) {
            abort(404, 'Order item not found');
        }

        $orderItem->delete();

        return ([
            'order' => $activeOrder->refresh()
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Place Order
    |--------------------------------------------------------------------------
    */

    public function placeOrder(Request $request, string $sessionToken)
    {
        DB::beginTransaction();

        try {
            $data = $this->loadSessionWithActiveOrder($sessionToken);

            $activeOrder = $data['activeOrder'];

            if (!$activeOrder) {
                abort(404, 'No active order');
            }

            $cartItems = OrderItem::query()
                ->where('order_id', $activeOrder->id)
                ->where('status', 'cart')
                ->get();

            if ($cartItems->isEmpty()) {
                return back()->with('error', 'Cart kosong');
            }

            $this->recalculate($activeOrder->id);

            OrderItem::query()
                ->where('order_id', $activeOrder->id)
                ->where('status', 'cart')
                ->update([
                    'status' => 'ordered',
                ]);

            $activeOrder->status = 'cooking';
            $activeOrder->save();

            $kots = $cartItems->map(function ($item) use ($activeOrder) {
                return [
                    'order_id'      => $activeOrder->id,
                    'order_item_id' => $item->id,
                    'kot_number'    => 'KOT-' . time(),
                    'section'       => 'kitchen',
                    'status'        => 'sent',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ];
            })->toArray();

            Kot::insert($kots);

            DB::commit();

            return back();
        } catch (\Throwable $th) {
            DB::rollBack();

            throw $th;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Item Notes
    |--------------------------------------------------------------------------
    */

    public function makeNotes(Request $request, string $sessionToken, int $itemId)
    {
        $validated = $request->validate([
            'notes' => ['required', 'string'],
        ]);

        $data = $this->loadSessionWithActiveOrder($sessionToken);

        $activeOrder = $data['activeOrder'];

        if (!$activeOrder) {
            abort(404, 'No active order');
        }

        $item = OrderItem::query()
            ->where('id', $itemId)
            ->where('order_id', $activeOrder->id)
            ->whereIn('status', ['cart', 'ordered'])
            ->firstOrFail();

        $item->update([
            'notes' => $validated['notes'],
        ]);

        return back();
    }

    /*
    |--------------------------------------------------------------------------
    | Recalculate Order
    |--------------------------------------------------------------------------
    */

    private function recalculate(int $orderId): void
    {
        $order = Order::findOrFail($orderId);

        $cartSubtotal = OrderItem::query()
            ->where('order_id', $orderId)
            ->where('status', 'cart')
            ->sum('subtotal');

        $subtotal = (float) $order->subtotal + (float) $cartSubtotal;

        $tax = $subtotal * 0.1;

        $order->update([
            'subtotal' => $subtotal,
            'tax'      => $tax,
            'discount' => 0,
            'total'    => $subtotal + $tax,
        ]);
    }
}
