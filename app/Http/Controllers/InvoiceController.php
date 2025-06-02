<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Project;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;

class InvoiceController extends Controller
{
    /**
     * Display a listing of invoices.
     */
    public function index(): View
    {
        $invoices = Invoice::with('client', 'project')->latest()->paginate(10);
        return view('invoices.index', compact('invoices'));
    }

    /**
     * Show the form to create a new invoice.
     */
    public function create(): View
    {
        $clients = Client::pluck('name', 'id');
        $projects = Project::pluck('title', 'id');
        return view('invoices.create', compact('clients', 'projects'));
    }

    /**
     * Store a newly created invoice.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'client_id'       => 'required|exists:clients,id',
            'project_id'      => 'nullable|exists:projects,id',
            'status'          => 'required|in:draft,sent,paid,overdue',
            'currency'        => 'required|string|max:10',
            'issue_date'      => 'required|date',
            'due_date'        => 'required|date|after_or_equal:issue_date',
            'sub_total'       => 'required|numeric',
            'discount_type'   => 'nullable|in:flat,percentage',
            'discount_value'  => 'nullable|numeric',
            'tax_rate'        => 'nullable|numeric',
            'total_amount'    => 'required|numeric',
            'paid_amount'     => 'nullable|numeric',
            'due_amount'      => 'nullable|numeric',
            'notes'           => 'nullable|string',
        ]);

        // Auto-generate invoice number like INV-1001
        $lastInvoiceId = Invoice::max('id') ?? 0;
        $validated['invoice_number'] = 'INV-' . str_pad($lastInvoiceId + 1, 4, '0', STR_PAD_LEFT);
        $validated['created_by'] = auth()->id();

        Invoice::create($validated);

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }

    /**
     * Show a specific invoice.
     */
    public function show(Invoice $invoice): View
    {
        return view('invoices.show', compact('invoice'));
    }



public function print(Invoice $invoice)
{
    return view('invoices.print', compact('invoice'));
}

public function download(Invoice $invoice)
{
    $pdf = Pdf::loadView('invoices.print', compact('invoice'));
    return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
}


    /**
     * Show the form for editing a specific invoice.
     */
    public function edit(Invoice $invoice): View
    {
        $clients = Client::pluck('name', 'id');
        $projects = Project::pluck('title', 'id');
        return view('invoices.edit', compact('invoice', 'clients', 'projects'));
    }

    /**
     * Update an invoice.
     */
    public function update(Request $request, Invoice $invoice): RedirectResponse
    {
        $validated = $request->validate([
            'client_id'       => 'required|exists:clients,id',
            'project_id'      => 'nullable|exists:projects,id',
            'status'          => 'required|in:draft,sent,paid,overdue',
            'currency'        => 'required|string|max:10',
            'issue_date'      => 'required|date',
            'due_date'        => 'required|date|after_or_equal:issue_date',
            'sub_total'       => 'required|numeric',
            'discount_type'   => 'nullable|in:flat,percentage',
            'discount_value'  => 'nullable|numeric',
            'tax_rate'        => 'nullable|numeric',
            'total_amount'    => 'required|numeric',
            'paid_amount'     => 'nullable|numeric',
            'due_amount'      => 'nullable|numeric',
            'notes'           => 'nullable|string',
        ]);

        $invoice->update($validated);

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
    }

    /**
     * Delete an invoice.
     */
    public function destroy(Invoice $invoice): RedirectResponse
    {
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }
}
