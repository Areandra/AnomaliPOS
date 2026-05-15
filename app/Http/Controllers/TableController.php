<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class TableController extends Controller
{
    public function index(): View
    {
        $restaurantId = Auth::guard('restaurant')->user()->id;

        $tables = Table::where('restaurant_id', $restaurantId)
            ->orderBy('table_number')
            ->get();

        return view('tables.index', compact('tables'));
    }

    public function create(): View
    {
        return view('tables.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'table_number' => 'required|string|max:50',
            'capacity'     => 'required|integer|min:1',
            'position_x'   => 'nullable|numeric',
            'position_y'   => 'nullable|numeric',
            'facing'       => 'nullable|boolean',
            'vertical'     => 'nullable|boolean',
            'status'       => 'nullable|in:available,occupied,waiting_payment',
        ]);

        $restaurantId = Auth::guard('restaurant')->user()->id;

        Table::create([
            ...$validated,
            'restaurant_id' => $restaurantId,
            'facing'        => $request->boolean('facing'),
            'vertical'      => $request->boolean('vertical'),
            'status'        => $validated['status'] ?? 'available',
        ]);

        return redirect()->route('tables.index')->with('success', 'Meja berhasil ditambahkan.');
    }

    public function edit(string $id): View
    {
        $restaurantId = Auth::guard('restaurant')->user()->id;

        $table = Table::where('id', $id)
            ->where('restaurant_id', $restaurantId)
            ->firstOrFail();

        return view('tables.create', compact('table'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $restaurantId = Auth::guard('restaurant')->user()->id;

        $table = Table::where('id', $id)
            ->where('restaurant_id', $restaurantId)
            ->firstOrFail();

        $validated = $request->validate([
            'table_number' => 'required|string|max:50',
            'capacity'     => 'required|integer|min:1',
            'position_x'   => 'nullable|numeric',
            'position_y'   => 'nullable|numeric',
            'facing'       => 'nullable|boolean',
            'vertical'     => 'nullable|boolean',
            'status'       => 'nullable|in:available,occupied,waiting_payment',
        ]);

        $table->update([
            ...$validated,
            'facing'   => $request->boolean('facing'),
            'vertical' => $request->boolean('vertical'),
        ]);

        return redirect()->route('tables.index')->with('success', 'Meja berhasil diperbarui.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $restaurantId = Auth::guard('restaurant')->user()->id;

        $table = Table::where('id', $id)
            ->where('restaurant_id', $restaurantId)
            ->firstOrFail();

        $table->delete();

        return redirect()->route('tables.index')->with('success', 'Meja berhasil dihapus.');
    }
}
