<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Shift;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // POST /api/payments
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'orderId'       => 'required|integer',
            'paymentMethod' => 'required|in:cash,qris,debit,transfer',
            'amount'        => 'required|numeric|min:0',
        ]);

        $order = Order::with(['items', 'payment'])->findOrFail($request->orderId);

        if ($order->payment) {
            return response()->json(['message' => 'Order sudah dibayar'], 400);
        }

        if ((float) $request->amount < (float) $order->total) {
            return response()->json(['message' => 'Jumlah pembayaran kurang'], 400);
        }

        $shift = Shift::where('user_id', auth()->id())
            ->where('status', 'open')
            ->first();

        if (!$shift) {
            return response()->json(['message' => 'Tidak ada shift aktif'], 400);
        }

        $change = (float) $request->amount - (float) $order->total;

        $payment = Payment::create([
            'order_id'       => $order->id,
            'user_id'        => auth()->id(),
            'restaurant_id'  => session('restaurant_id'),
            'shift_id'       => $shift->id,
            'payment_method' => $request->paymentMethod,
            'amount'         => $request->amount,
            'change'         => $change,
            'status'         => 'paid',
            'paid_at'        => now(),
        ]);

        $order->update(['status' => 'completed']);

        $order->load(['items.menuItem', 'table', 'payment', 'tableSession.createdBy']);

        return response()->json([
            'payment' => $payment,
            'order'   => [
                'id'         => $order->id,
                'order_code' => $order->order_code,
                'status'     => $order->status,
                'subtotal'   => $order->subtotal,
                'tax'        => $order->tax,
                'discount'   => $order->discount,
                'total'      => $order->total,
                'payment'    => $order->payment,
                'table'      => $order->table ? ['table_number' => $order->table->table_number] : null,
                'session'    => $order->tableSession ? [
                    'session_token'   => $order->tableSession->token,
                    'created_by_user' => $order->tableSession->createdBy,
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
                ])->values(),
            ],
        ]);
    }
}
