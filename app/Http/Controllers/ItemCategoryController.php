<?php

namespace App\Http\Controllers;

use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ItemCategoryController extends Controller
{
    /**
     * Display a listing of item categories.
     */
    public function index(): View
    {
        $categories = ItemCategory::with('parent')->latest()->paginate(10);
        return view('item-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new item category.
     */
    public function create(): View
    {
        $categories = ItemCategory::pluck('name', 'id');
        return view('item-categories.create', compact('categories'));
    }

    /**
     * Store a newly created item category in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255|unique:item_categories,name',
            'slug'        => 'nullable|string|max:255|unique:item_categories,slug',
            'parent_id'   => 'nullable|exists:item_categories,id',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        $data['created_by'] = Auth::id();

        ItemCategory::create($data);

        return redirect()->route('item-categories.index')->with('success', '✅ Category created successfully.');
    }

    /**
     * Show the form for editing the specified item category.
     */
    public function edit(ItemCategory $itemCategory): View
    {
        $categories = ItemCategory::where('id', '!=', $itemCategory->id)->pluck('name', 'id');
        return view('item-categories.edit', compact('itemCategory', 'categories'));
    }

    /**
     * Update the specified item category in storage.
     */
    public function update(Request $request, ItemCategory $itemCategory): RedirectResponse
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255|unique:item_categories,name,' . $itemCategory->id,
            'slug'        => 'nullable|string|max:255|unique:item_categories,slug,' . $itemCategory->id,
            'parent_id'   => 'nullable|exists:item_categories,id|not_in:' . $itemCategory->id,
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        $data['updated_by'] = Auth::id();

        $itemCategory->update($data);

        return redirect()->route('item-categories.index')->with('success', '✅ Category updated successfully.');
    }

    /**
     * Remove the specified item category from storage.
     */
    public function destroy(ItemCategory $itemCategory): RedirectResponse
    {
        $itemCategory->delete();
        return redirect()->route('item-categories.index')->with('success', '🗑️ Category deleted.');
    }
}
