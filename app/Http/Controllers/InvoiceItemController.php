<?php

namespace App\Http\Controllers;

use App\Models\InvoiceItem;
use App\Models\Invoice;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class InvoiceItemController extends Controller
{
    /**
     * Show all invoice items.
     */
    public function index(): View
    {
        $items = InvoiceItem::with(['invoice', 'category'])
            ->latest()
            ->paginate(15);

        return view('invoice-items.index', compact('items'));
    }

    /**
     * Show form to create one or more invoice items.
     */
    public function create(): View
    {
        $invoices = Invoice::pluck('invoice_number', 'id');
        $categories = ItemCategory::pluck('name', 'id');

        return view('invoice-items.create', compact('invoices', 'categories'));
    }

    /**
     * Store multiple invoice items at once.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'invoice_id'               => 'required|exists:invoices,id',
            'items.*.item_name'        => 'required|string|max:255',
            'items.*.item_code'        => 'nullable|string|max:100',
            'items.*.description'      => 'nullable|string',
            'items.*.quantity'         => 'required|integer|min:1',
            'items.*.unit'             => 'required|string|max:20',
            'items.*.item_category_id' => 'nullable|exists:item_categories,id',
            'items.*.unit_price'       => 'required|numeric|min:0',
            'items.*.tax_percent'      => 'nullable|numeric|min:0|max:100',
            'items.*.total'            => 'required|numeric|min:0',
        ]);

        foreach ($request->items as $item) {
            InvoiceItem::create([
                'invoice_id'        => $request->invoice_id,
                'item_name'         => $item['item_name'],
                'item_code'         => $item['item_code'] ?? null,
                'description'       => $item['description'] ?? null,
                'quantity'          => $item['quantity'],
                'unit'              => $item['unit'],
                'item_category_id'  => $item['item_category_id'] ?? null,
                'unit_price'        => $item['unit_price'],
                'tax_percent'       => $item['tax_percent'] ?? null,
                'total'             => $item['total'],
                'created_by'        => auth()->id(),
            ]);
        }

        return redirect()->route('invoice-items.index')
            ->with('success', 'All invoice items created successfully.');
    }

    /**
     * Show form to edit a single invoice item.
     */
    public function edit(InvoiceItem $invoiceItem): View
    {
        $invoices = Invoice::pluck('invoice_number', 'id');
        $categories = ItemCategory::pluck('name', 'id');

        return view('invoice-items.edit', compact('invoiceItem', 'invoices', 'categories'));
    }

    /**
     * Update a single invoice item.
     */
    public function update(Request $request, InvoiceItem $invoiceItem): RedirectResponse
    {
        $validated = $request->validate([
            'invoice_id'       => 'required|exists:invoices,id',
            'item_code'        => 'nullable|string|max:100',
            'item_name'        => 'required|string|max:255',
            'description'      => 'nullable|string',
            'quantity'         => 'required|integer|min:1',
            'unit'             => 'required|string|max:20',
            'item_category_id' => 'nullable|exists:item_categories,id',
            'unit_price'       => 'required|numeric|min:0',
            'tax_percent'      => 'nullable|numeric|min:0|max:100',
            'total'            => 'required|numeric|min:0',
        ]);

        $validated['updated_by'] = auth()->id();

        $invoiceItem->update($validated);

        return redirect()->route('invoice-items.index')
            ->with('success', 'Invoice item updated successfully.');
    }

    /**
     * Soft delete an invoice item.
     */
    public function destroy(InvoiceItem $invoiceItem): RedirectResponse
    {
        $invoiceItem->delete();

        return redirect()->route('invoice-items.index')
            ->with('success', 'Invoice item deleted successfully.');
    }
}
