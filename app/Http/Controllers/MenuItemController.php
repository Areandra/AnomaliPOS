<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MenuItemController extends Controller
{
    public function index(): View
    {

        $items = MenuItem::with('category')
            ->orderBy('name')
            ->get();

        $categories = MenuCategory::query()->select('*')
            ->orderBy('sort_order')
            ->get();

        return view('menu.items.index', compact('items', 'categories'));
    }

    public function create(): View
    {

        $categories = MenuCategory::query()->select('*')
            ->orderBy('sort_order')
            ->get();

        return view('menu.items.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id'   => 'required|exists:menu_categories,id',
            'name'          => 'required|string|max:100',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'cost_of_goods' => 'nullable|numeric|min:0',
            'sku'           => 'nullable|string|max:100',
            'is_available'  => 'nullable|boolean',
            'image'         => 'nullable|image|max:2048',
        ]);


        $imageUrl = null;
        if ($request->hasFile('image')) {
            $imageUrl = $request->file('image')->store('menu-items', 'public');
        }

        MenuItem::create([
            ...$validated,
            'is_available'  => $request->boolean('is_available', true),
            'image_url'     => $imageUrl,
        ]);

        return redirect()->route('menu.items.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(string $id): View
    {

        $item = MenuItem::query()->where('id', $id)
            ->firstOrFail();

        $categories = MenuCategory::query()->select('*')
            ->orderBy('sort_order')
            ->get();

        return view('menu.items.create', compact('item', 'categories'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {

        $item = MenuItem::query()->where('id', $id)
            ->firstOrFail();

        $validated = $request->validate([
            'category_id'   => 'required|exists:menu_categories,id',
            'name'          => 'required|string|max:100',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'cost_of_goods' => 'nullable|numeric|min:0',
            'sku'           => 'nullable|string|max:100',
            'is_available'  => 'nullable|boolean',
            'image'         => 'nullable|image|max:2048',
        ]);

        $imageUrl = $item->image_url;
        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($imageUrl) {
                Storage::disk('public')->delete($imageUrl);
            }
            $imageUrl = $request->file('image')->store('menu-items', 'public');
        }

        $item->update([
            ...$validated,
            'is_available' => $request->boolean('is_available', true),
            'image_url'    => $imageUrl,
        ]);

        return redirect()->route('menu.items.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(string $id): RedirectResponse
    {

        $item = MenuItem::query()->where('id', $id)
            ->firstOrFail();

        if ($item->image_url) {
            Storage::disk('public')->delete($item->image_url);
        }

        $item->delete($item->id);

        return redirect()->route('menu.items.index')->with('success', 'Menu berhasil dihapus.');
    }

    public function toggleAvailable(string $id): RedirectResponse
    {
        $item = MenuItem::query()->where('id', $id)
            ->firstOrFail();

        $item->update(['is_available' => !$item->is_available]);

        return redirect()->route('menu.items.index')->with('success', 'Status menu berhasil diubah.');
    }
}
