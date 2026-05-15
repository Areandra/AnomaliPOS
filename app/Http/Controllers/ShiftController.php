<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    public function open(Request $request): JsonResponse
    {
        $user = $request->user();

        $existing = Shift::where('user_id', $user->id)
            ->where('status', 'open')
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Anda masih memiliki sesi shift yang aktif.'], 400);
        }

        $shift = Shift::create([
            'user_id'         => $user->id,
            'restaurant_id'   => $user->restaurant_id,
            'modal_awal'      => $request->input('startingCash', 0),
            'status'          => 'open',
            'opened_at'       => now(),
            'cash_system'     => 0,
            'cash_physical'   => 0,
            'cash_variance'   => 0,
            'qris_system'     => 0,
            'debit_system'    => 0,
            'transfer_system' => 0,
        ]);

        $shift->load('user');

        return response()->json($shift, 201);
    }

    public function close(Request $request): JsonResponse
    {
        $user         = $request->user();
        $cashPhysical = (float) $request->input('cashPhysical', 0);
        $notes        = $request->input('notes');

        $shift = Shift::with(['user', 'payments' => fn($q) => $q->where('status', 'paid')])
            ->where('user_id', $user->id)
            ->where('status', 'open')
            ->first();

        if (!$shift) {
            return response()->json(['message' => 'Tidak ada sesi shift aktif.'], 404);
        }

        $totalCash = $totalQris = $totalTransfer = $totalDebit = 0;

        foreach ($shift->payments as $p) {
            $amount = (float) $p->amount;
            match ($p->payment_method) {
                'cash'     => $totalCash     += $amount,
                'qris'     => $totalQris     += $amount,
                'transfer' => $totalTransfer += $amount,
                'debit'    => $totalDebit    += $amount,
                default    => null,
            };
        }

        $expectedCash = (float) $shift->modal_awal + $totalCash;
        $variance     = $cashPhysical - $expectedCash;

        $shift->update([
            'status'          => 'closed',
            'closed_at'       => now(),
            'cash_system'     => $totalCash,
            'cash_physical'   => $cashPhysical,
            'cash_variance'   => $variance,
            'qris_system'     => $totalQris,
            'transfer_system' => $totalTransfer,
            'debit_system'    => $totalDebit,
            'notes'           => $notes,
        ]);

        return response()->json([
            'message' => 'Shift closed successfully',
            'summary' => [
                'modal_awal'          => $shift->modal_awal,
                'penjualan_tunai'     => $totalCash,
                'seharusnya_di_laci'  => $expectedCash,
                'fisik_di_laci'       => $cashPhysical,
                'selisih'             => $variance,
            ],
            'data' => $shift,
        ]);
    }

    public function history(): \Illuminate\View\View
    {
        $restaurantId = Auth::guard('restaurant')->user()->id ?? null;

        $shifts = Shift::with('user')
            ->when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
            ->where('status', 'closed')
            ->orderBy('opened_at', 'desc')
            ->paginate(20);

        return view('shift.index', compact('shifts'));
    }

    public function destroy(string $id): \Illuminate\Http\RedirectResponse
    {
        $restaurantId = Auth::guard('restaurant')->user()->id;

        $shift = Shift::where('id', $id)
            ->where('restaurant_id', $restaurantId)
            ->firstOrFail();

        $shift->delete();

        return redirect()->route('shifts.index')->with('success', 'Shift berhasil dihapus.');
    }


    public function show($id): \Illuminate\View\View
    {
        $shift = Shift::with([
            'user',
            'restaurant:id,name',
            'payments.createdBy',
            'payments.order.items.menuItem',
        ])
            ->where('id', $id)
            ->where('status', 'closed')
            ->firstOrFail();

        return view('shift/show', ['shift' => $shift]);
    }

    public function historyMe(Request $request): \Illuminate\View\View
    {
        $shifts = Shift::where('user_id', $request->user()->id)
            ->where('status', 'closed')
            ->orderBy('opened_at', 'desc')
            ->get();

        $data = $shifts->map(fn($s) => [
            'id'           => $s->id,
            'openedAt'     => $s->opened_at,
            'closedAt'     => $s->closed_at,
            'startingCash' => (float) $s->modal_awal + (float) $s->cash_system,
            'cashPhysical' => $s->cash_physical,
            'selisih'      => $s->cash_variance,
            'status'       => $s->status,
        ]);

        return view('shift/me', ['data' => $data]);
    }

    public function attendenceMe(Request $request): \Illuminate\View\View
    {
        $shifts = Shift::where('user_id', $request->user()->id)
            ->where('status', 'closed')
            ->orderBy('opened_at', 'desc')
            ->get();

        $data = $shifts->map(fn($s) => [
            'id'           => $s->id,
            'openedAt'     => $s->opened_at,
            'closedAt'     => $s->closed_at,
            'startingCash' => (float) $s->modal_awal + (float) $s->cash_system,
            'cashPhysical' => $s->cash_physical,
            'selisih'      => $s->cash_variance,
            'status'       => $s->status,
        ]);

        return view('attendence/me', ['data' => $data]);
    }
}
