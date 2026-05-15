<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class MenuCategoryController extends Controller
{
    public function index(): View
    {
        $restaurantId = Auth::guard('restaurant')->user()->id;

        $categories = MenuCategory::where('restaurant_id', $restaurantId)
            ->withCount('items')
            ->orderBy('sort_order')
            ->get();

        return view('menu.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('menu.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'sort_order'  => 'required|integer|min:0',
        ]);

        $restaurantId = Auth::guard('restaurant')->user()->id;

        MenuCategory::create([
            ...$validated,
            'restaurant_id' => $restaurantId,
        ]);

        return redirect()->route('menu.categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(string $id): View
    {
        $restaurantId = Auth::guard('restaurant')->user()->id;

        $category = MenuCategory::where('id', $id)
            ->where('restaurant_id', $restaurantId)
            ->firstOrFail();

        return view('menu.categories.edit', compact('category'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $restaurantId = Auth::guard('restaurant')->user()->id;

        $category = MenuCategory::where('id', $id)
            ->where('restaurant_id', $restaurantId)
            ->firstOrFail();

        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'sort_order'  => 'required|integer|min:0',
        ]);

        $category->update($validated);

        return redirect()->route('menu.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $restaurantId = Auth::guard('restaurant')->user()->id;

        $category = MenuCategory::where('id', $id)
            ->where('restaurant_id', $restaurantId)
            ->firstOrFail();

        $category->delete();

        return redirect()->route('menu.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
