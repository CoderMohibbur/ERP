<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Client;
use App\Models\Project;
use App\Models\TermAndCondition;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    /**
     * Display a listing of invoices.
     */
    public function index(Request $request): View
    {
        $query = Invoice::with(['client', 'project'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('invoice_type')) {
            $query->where('invoice_type', $request->invoice_type);
        }

        $invoices = $query->paginate(10)->withQueryString();

        return view('invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new invoice.
     */
    public function create(): View
    {
        $clients  = Client::pluck('name', 'id');
        $projects = Project::pluck('title', 'id');
        $terms    = TermAndCondition::pluck('title', 'id');

        return view('invoices.create', compact('clients', 'projects', 'terms'));
    }

    /**
     * Store a newly created invoice.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'invoice_number'     => 'required|unique:invoices',
            'invoice_type'       => 'required|in:proforma,final',
            'status'             => 'required|in:draft,sent,paid,overdue',
            'client_id'          => 'required|exists:clients,id',
            'project_id'         => 'nullable|exists:projects,id',
            'terms_id'           => 'nullable|exists:term_and_conditions,id',
            'currency'           => 'required|string|max:10',
            'currency_rate'      => 'required|numeric|min:0',
            'issue_date'         => 'required|date',
            'due_date'           => 'required|date|after_or_equal:issue_date',
            'sub_total'          => 'required|numeric|min:0',
            'discount_type'      => 'nullable|in:flat,percentage',
            'discount_value'     => 'nullable|numeric|min:0',
            'tax_rate'           => 'nullable|numeric|min:0',
            'total_amount'       => 'required|numeric|min:0',
            'paid_amount'        => 'nullable|numeric|min:0',
            'due_amount'         => 'nullable|numeric|min:0',
            'notes'              => 'nullable|string',
            'metadata'           => 'nullable|array',
        ]);

        $validated['created_by'] = Auth::id();

        Invoice::create($validated);

        return redirect()->route('invoices.index')->with('success', '✅ Invoice created successfully.');
    }

    /**
     * Show the form for editing an invoice.
     */
    public function edit(Invoice $invoice): View
    {
        $clients  = Client::pluck('name', 'id');
        $projects = Project::pluck('title', 'id');
        $terms    = TermAndCondition::pluck('title', 'id');

        return view('invoices.edit', compact('invoice', 'clients', 'projects', 'terms'));
    }

    /**
     * Update an invoice.
     */
    public function update(Request $request, Invoice $invoice): RedirectResponse
    {
        $validated = $request->validate([
            'invoice_number'     => 'required|unique:invoices,invoice_number,' . $invoice->id,
            'invoice_type'       => 'required|in:proforma,final',
            'status'             => 'required|in:draft,sent,paid,overdue',
            'client_id'          => 'required|exists:clients,id',
            'project_id'         => 'nullable|exists:projects,id',
            'terms_id'           => 'nullable|exists:term_and_conditions,id',
            'currency'           => 'required|string|max:10',
            'currency_rate'      => 'required|numeric|min:0',
            'issue_date'         => 'required|date',
            'due_date'           => 'required|date|after_or_equal:issue_date',
            'sub_total'          => 'required|numeric|min:0',
            'discount_type'      => 'nullable|in:flat,percentage',
            'discount_value'     => 'nullable|numeric|min:0',
            'tax_rate'           => 'nullable|numeric|min:0',
            'total_amount'       => 'required|numeric|min:0',
            'paid_amount'        => 'nullable|numeric|min:0',
            'due_amount'         => 'nullable|numeric|min:0',
            'notes'              => 'nullable|string',
            'metadata'           => 'nullable|array',
        ]);

        $validated['updated_by'] = Auth::id();

        $invoice->update($validated);

        return redirect()->route('invoices.index')->with('success', '✅ Invoice updated successfully.');
    }

    /**
     * Delete invoice.
     */
    public function destroy(Invoice $invoice): RedirectResponse
    {
        $invoice->delete();

        return redirect()->route('invoices.index')->with('success', '🗑️ Invoice deleted successfully.');
    }

    /**
     * Print invoice page.
     */
    public function print(Invoice $invoice)
    {
        $invoice->load('client', 'project', 'items'); // load relations
        return view('invoices.print', compact('invoice'));
    }


    /**
     * Download invoice PDF.
     */
    public function download(Invoice $invoice)
    {
        // Placeholder for PDF download logic
        return response()->json(['message' => 'Download feature coming soon.']);
    }
}
