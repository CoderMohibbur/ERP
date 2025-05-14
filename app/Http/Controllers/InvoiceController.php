<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the invoices.
     */
    public function index(): View
    {
        $invoices = Invoice::with(['client', 'project', 'creator'])->latest()->paginate(10);
        return view('invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new invoice.
     */
    public function create(): View
    {
        $clients = Client::pluck('name', 'id');
        $projects = Project::pluck('name', 'id');
        return view('invoices.create', compact('clients', 'projects'));
    }

    /**
     * Store a newly created invoice in storage.
     */
    public function store(StoreInvoiceRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();

        Invoice::create($data);

        return redirect()->route('invoices.index')
                         ->with('success', 'Invoice created successfully.');
    }

    /**
     * Show the form for editing the specified invoice.
     */
    public function edit(Invoice $invoice): View
    {
        $clients = Client::pluck('name', 'id');
        $projects = Project::pluck('name', 'id');
        return view('invoices.edit', compact('invoice', 'clients', 'projects'));
    }

    /**
     * Update the specified invoice in storage.
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice): RedirectResponse
    {
        $invoice->update($request->validated());

        return redirect()->route('invoices.index')
                         ->with('success', 'Invoice updated successfully.');
    }

    /**
     * Remove the specified invoice from storage.
     */
    public function destroy(Invoice $invoice): RedirectResponse
    {
        $invoice->delete();

        return redirect()->route('invoices.index')
                         ->with('success', 'Invoice deleted successfully.');
    }
}
