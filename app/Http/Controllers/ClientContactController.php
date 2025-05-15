<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientContact;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Requests\StoreClientContactRequest;
use App\Http\Requests\UpdateClientContactRequest;

class ClientContactController extends Controller
{
    /**
     * Display a listing of the client contacts.
     */
    public function index(): View
    {
        $contacts = ClientContact::with('client')->latest()->paginate(10);
        return view('client-contacts.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new client contact.
     */
    public function create(): View
    {
        $clients = Client::pluck('name', 'id');
        return view('client-contacts.create', compact('clients'));
    }

    /**
     * Store a newly created client contact in storage.
     */
    public function store(StoreClientContactRequest $request): RedirectResponse
    {
        $data = $request->validated();
        ClientContact::create($data);

        return redirect()->route('client-contacts.index')
                         ->with('success', 'Client contact created successfully.');
    }

    /**
     * Show the form for editing the specified client contact.
     */
    public function edit(ClientContact $clientContact): View
    {
        $clients = Client::pluck('name', 'id');
        return view('client-contacts.edit', compact('clientContact', 'clients'));
    }

    /**
     * Update the specified client contact in storage.
     */
    public function update(UpdateClientContactRequest $request, ClientContact $clientContact): RedirectResponse
    {
        $data = $request->validated();
        $clientContact->update($data);

        return redirect()->route('client-contacts.index')
                         ->with('success', 'Client contact updated successfully.');
    }

    /**
     * Remove the specified client contact from storage.
     */
    public function destroy(ClientContact $clientContact): RedirectResponse
    {
        $clientContact->delete();

        return redirect()->route('client-contacts.index')
                         ->with('success', 'Client contact deleted successfully.');
    }
}
