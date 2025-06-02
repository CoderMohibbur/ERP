<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;

class InvoiceItemController extends Controller
{
    /**
     * Display a listing of the invoice items.
     */
    public function index()
    {
        $invoiceItems = InvoiceItem::with('invoice')->latest()->paginate(15);
        return view('invoice-items.index', compact('invoiceItems'));
    }

    /**
     * Show the form for creating a new invoice item.
     */
    public function create()
    {
        $invoices = Invoice::pluck('invoice_number', 'id');
        return view('invoice-items.create', compact('invoices'));
    }

    /**
     * Store a newly created invoice item in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id'   => 'required|exists:invoices,id',
            'item_code'    => 'nullable|string|max:50',
            'item_name'    => 'required|string|max:255',
            'description'  => 'nullable|string',
            'quantity'     => 'required|integer|min:1',
            'unit_price'   => 'required|numeric|min:0',
            'tax_percent'  => 'nullable|numeric|min:0|max:100',
        ]);

        $validated['total'] = $validated['quantity'] * $validated['unit_price'];

        if ($validated['tax_percent'] ?? false) {
            $validated['total'] += ($validated['total'] * $validated['tax_percent']) / 100;
        }

        InvoiceItem::create($validated);

        return redirect()->route('invoice-items.index')->with('success', 'Invoice item added successfully.');
    }

    /**
     * Show the form for editing the specified invoice item.
     */
    public function edit(InvoiceItem $invoiceItem)
    {
        $invoices = Invoice::pluck('invoice_number', 'id');
        return view('invoice-items.edit', compact('invoiceItem', 'invoices'));
    }

    /**
     * Update the specified invoice item in storage.
     */
    public function update(Request $request, InvoiceItem $invoiceItem)
    {
        $validated = $request->validate([
            'invoice_id'   => 'required|exists:invoices,id',
            'item_code'    => 'nullable|string|max:50',
            'item_name'    => 'required|string|max:255',
            'description'  => 'nullable|string',
            'quantity'     => 'required|integer|min:1',
            'unit_price'   => 'required|numeric|min:0',
            'tax_percent'  => 'nullable|numeric|min:0|max:100',
        ]);

        $validated['total'] = $validated['quantity'] * $validated['unit_price'];

        if ($validated['tax_percent'] ?? false) {
            $validated['total'] += ($validated['total'] * $validated['tax_percent']) / 100;
        }

        $invoiceItem->update($validated);

        return redirect()->route('invoice-items.index')->with('success', 'Invoice item updated successfully.');
    }

    /**
     * Remove the specified invoice item from storage.
     */
    public function destroy(InvoiceItem $invoiceItem)
    {
        $invoiceItem->delete();
        return redirect()->route('invoice-items.index')->with('success', 'Invoice item deleted.');
    }
}
