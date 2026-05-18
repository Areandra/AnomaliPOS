<?php

namespace App\Http\Controllers;

use App\Models\Kot;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use App\Models\TableSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    private function orderWithRelations(int $id): ?Order
    {
        return Order::with([
            'items.menuItem',
            'table',
            'payment',
            'tableSession.createdBy',
        ])->find($id);
    }

    private function serializeOrder(?Order $order): array
    {
        if (!$order) return [];

        return [
            'id'         => $order->id,
            'order_code' => $order->order_code,
            'status'     => $order->status,
            'type'       => $order->type,
            'subtotal'   => $order->subtotal,
            'tax'        => $order->tax,
            'discount'   => $order->discount,
            'total'      => $order->total,
            'notes'      => $order->notes,
            'created_at' => $order->created_at,
            'payment'    => $order->payment,
            'table'      => $order->table ? ['table_number' => $order->table->table_number] : null,
            'session'    => $order->tableSession ? [
                'session_token'      => $order->tableSession->token,
                'created_by_user'    => $order->tableSession->createdBy,
            ] : null,
            'items' => $order->items->map(fn($item) => [
                'id'        => $item->id,
                'quantity'  => $item->quantity,
                'price'     => $item->price,
                'subtotal'  => $item->subtotal,
                'status'    => $item->status,
                'notes'     => $item->notes,
                'menu_item' => $item->menuItem ? [
                    'id'    => $item->menuItem->id,
                    'name'  => $item->menuItem->name,
                    'price' => $item->menuItem->price,
                ] : null,
            ])->values()->toArray(),
        ];
    }

    // GET /api/order/{id}
    public function show(int $id): JsonResponse
    {
        $order = $this->orderWithRelations($id);
        return response()->json($this->serializeOrder($order));
    }

    // POST /api/order
    public function store(Request $request)
    {
        $plan         = session('restaurant_plan', 'starter');
        $userId       = Auth::guard('web')->user()->id;

        // Starter plan: buat order kosong tanpa meja
        if ($plan === 'starter') {
            $order = Order::create([
                'order_code'    => 'ORD-' . time(),
                'type'          => 'dine_in',
                'status'        => 'pending',
                'subtotal'      => 0,
                'tax'           => 0,
                'discount'      => 0,
                'total'         => 0,
            ]);
            return response()->json($this->serializeOrder($this->orderWithRelations($order->id)));
        }

        $tableId = $request->input('tableId');
        $guest   = $request->input('guest', 1);
        $type    = $request->input('type', 'dine_in');

        $table = Table::findOrFail($tableId);

        // Cek apakah ada session aktif
        $session = TableSession::query()->where('table_id', $tableId)
            ->where('is_active', true)
            ->first();

        if ($session) {
            // Session aktif — buat order baru di session yang sama
            $order = Order::create([
                'table_id'         => $tableId,
                'table_session_id' => $session->id,
                'order_code'       => 'ORD-' . time(),
                'type'             => $type,
                'status'           => 'pending',
                'subtotal'         => 0,
                'tax'              => 0,
                'discount'         => 0,
                'total'            => 0,
            ]);
        } else {
            // Buat session baru
            $session = TableSession::create([
                'table_id'      => $tableId,
                'created_by'    => $userId,
                'token'         => Str::uuid(),
                'started_at'    => now(),
                'is_active'     => true,
            ]);

            $table->update([
                'status'                   => 'occupied',
                'current_table_session_id' => $session->id,
            ]);

            $order = Order::create([

                'table_id'         => $tableId,
                'table_session_id' => $session->id,
                'order_code'       => 'ORD-' . time(),
                'type'             => $type,
                'status'           => 'pending',
                'subtotal'         => 0,
                'tax'              => 0,
                'discount'         => 0,
                'total'            => 0,
            ]);
        }

        return response()->redirectToRoute('cashier.index');
    }

    // POST /api/order/add-item
    public function addItem(Request $request): JsonResponse
    {
        $orderId    = $request->input('orderId');
        $menuItemId = $request->input('menuItemId');
        $qty        = $request->input('qty', 1);

        $order = Order::with('payment')->findOrFail($orderId);
        if ($order->payment) {
            return response()->json(['message' => 'Order sudah dibayar'], 400);
        }

        $menu = MenuItem::findOrFail($menuItemId);

        OrderItem::create([
            'order_id'      => $orderId,
            'restaurant_id' => Auth::guard('restaurant')->user()->id,
            'menu_item_id'  => $menuItemId,
            'quantity'      => $qty,
            'price'         => $menu->price,
            'subtotal'      => $qty * $menu->price,
            'status'        => 'cart',
        ]);

        return response()->json($this->serializeOrder($this->orderWithRelations($orderId)));
    }

    // POST /api/order/update-qty
    public function updateQty(Request $request): JsonResponse
    {
        $itemId = $request->input('itemId');
        $qty    = $request->input('qty');

        $item = OrderItem::query()->where('id', $itemId)->where('status', 'cart')->firstOrFail();
        $item->update([
            'quantity' => $qty,
            'subtotal' => $qty * $item->price,
        ]);

        return response()->json($this->serializeOrder($this->orderWithRelations($item->order_id)));
    }

    // POST /api/order/delete-item
    public function deleteItem(Request $request): JsonResponse
    {
        $itemId  = $request->input('itemId');
        $item    = OrderItem::query()->where('id', $itemId)->where('status', 'cart')->firstOrFail();
        $orderId = $item->order_id;
        $item->delete();

        return response()->json($this->serializeOrder($this->orderWithRelations($orderId)));
    }

    // POST /api/order/place-order/{id}
    public function placeOrder(int $id): JsonResponse
    {
        $plan      = session('restaurant_plan', 'starter');
        $cartItems = OrderItem::query()->where('order_id', $id)->where('status', 'cart')->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Cart kosong'], 400);
        }

        $this->recalculate($id);

        OrderItem::query()->where('order_id', $id)->where('status', 'cart')->update(['status' => 'ordered']);

        $order         = Order::findOrFail($id);
        $order->status = $plan !== 'starter' ? 'cooking' : 'served';
        $order->save();

        if ($plan !== 'starter') {
            $kots = $cartItems->map(fn($i) => [
                'order_id'      => $id,
                'restaurant_id' => Auth::guard('restaurant')->user()->id,
                'order_item_id' => $i->id,
                'kot_number'    => 'KOT-' . time(),
                'section'       => 'kitchen',
                'status'        => 'sent',
                'created_at'    => now(),
                'updated_at'    => now(),
            ])->toArray();
            Kot::insert($kots);
        }

        return response()->json($this->serializeOrder($this->orderWithRelations($id)));
    }

    // POST /api/order/{id}/notes
    public function makeNotes(Request $request, int $id): JsonResponse
    {
        $notes = $request->input('notes');
        if (!$notes) {
            return response()->json(['message' => 'Notes tidak boleh kosong'], 400);
        }

        $item = OrderItem::query()->where('id', $id)
            ->whereIn('status', ['cart', 'ordered'])
            ->firstOrFail();

        $item->update(['notes' => $notes]);

        return response()->json($this->serializeOrder($this->orderWithRelations($item->order_id)));
    }

    // POST /api/session/{token}/end
    public function endSession(string $token): JsonResponse
    {
        $session = TableSession::query()->where('token', $token)->firstOrFail();

        $table = Table::query()->where('current_table_session_id', $session->id)->first();
        if ($table) {
            $table->update([
                'status'                   => 'available',
                'current_table_session_id' => null,
            ]);
        }

        $session->update(['is_active' => false, 'ended_at' => now()]);

        return response()->json(['message' => 'Session ended']);
    }

    private function recalculate(int $orderId): void
    {
        $order    = Order::findOrFail($orderId);
        $cartSub  = OrderItem::query()->where('order_id', $orderId)->where('status', 'cart')->sum('subtotal');
        $subtotal = (float) $order->subtotal + (float) $cartSub;
        $tax      = $subtotal * 0.1;

        $order->update([
            'subtotal' => $subtotal,
            'tax'      => $tax,
            'discount' => 0,
            'total'    => $subtotal + $tax,
        ]);
    }
}
