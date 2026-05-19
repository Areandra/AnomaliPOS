<?php

namespace App\Http\Controllers;

use App\Models\Kot;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KitchenController extends Controller
{
    /**
     * Menampilkan halaman utama Kitchen Board dengan semua data.
     */
    public function index()
    {
        $categoriesData = MenuCategory::all();

        $kotsData = Kot::query()
            ->with([
                'orderItem' => function ($q) {
                    $q->whereNotIn('status', ['cart'])->with(['menuItem.category']);
                },
                'order.table',
            ])
            ->get();
        return view('kitchen.index', compact('categoriesData', 'kotsData'));
    }

    /**
     * Menampilkan data berdasarkan kategori tertentu (Jika dibutuhkan routing terpisah).
     */
    public function show($id)
    {
        $category = MenuCategory::all();

        $kots = Kot::query()
            ->with(['order.table'])
            ->whereHas('orderItem.menuItem', function ($m) use ($id) {
                $m->where('category_id', $id); // Sesuaikan nama kolom foreign key di DB Anda
            })
            ->with([
                'orderItem' => function ($q) {
                    $q->whereNotIn('status', ['cart'])->with(['menuItem.category']);
                },
            ])
            ->get();

        return view('kitchen.index', [
            'kotsData' => $kots, // Disamakan variabelnya agar matching dengan blade
            'categoriesData' => $category,
        ]);
    }

    /**
     * Update status order item dari pemicu tombol di Kitchen Board.
     */
    public function updateStatus(Request $request, $orderItemId)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        // Berdasarkan logic JS di blade, route ini mengupdate status di OrderItem
        // Jika statusnya 'delivered', status KOT diubah menjadi 'done'

        // Cari KOT yang memiliki orderItem tersebut
        $kot = Kot::query()->where('order_item_id', $orderItemId)->firstOrFail();

        // Update status di relasi orderItem
        $kot->orderItem()->update([
            'status' => $request->status,
        ]);

        // Jika status baru adalah 'delivered', tandai KOT selesai ('done')
        if ($request->status === 'delivered') {
            $kot->status = 'done';
            $kot->save();
        }

        return response()->json([
            'success' => true,
            'status' => $kot->status,
        ]);
    }
}
