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
     * Show all client contacts
     */
    public function index(): View
    {
        $contacts = ClientContact::with('client')->latest()->paginate(10);
        return view('client-contacts.index', compact('contacts'));
    }

    /**
     * Show the form to create a client contact
     */
    public function create(): View
    {
        $clients = Client::pluck('name', 'id');
        return view('client-contacts.create', compact('clients'));
    }

    /**
     * Store a single client contact based on selected type & value
     */
    public function store(StoreClientContactRequest $request): RedirectResponse
    {
        $clientId = $request->input('client_id');
        $type = $request->input('type');
        $value = $request->input('value');

        if ($type && $value) {
            ClientContact::create([
                'client_id' => $clientId,
                'type' => $type,
                'value' => $value,
            ]);
        }

        return redirect()->route('client-contacts.index')
            ->with('success', 'Client contact created successfully.');
    }

    /**
     * Show the form to edit a client contact
     */
    public function edit(ClientContact $clientContact): View
    {
        $clients = Client::pluck('name', 'id');
        return view('client-contacts.edit', compact('clientContact', 'clients'));
    }

    /**
     * Update a client contact
     */
    public function update(UpdateClientContactRequest $request, ClientContact $clientContact): RedirectResponse
    {
        $clientContact->update($request->validated());

        return redirect()->route('client-contacts.index')
            ->with('success', 'Client contact updated successfully.');
    }

    /**
     * Delete a client contact
     */
    public function destroy(ClientContact $clientContact): RedirectResponse
    {
        $clientContact->delete();

        return redirect()->route('client-contacts.index')
            ->with('success', 'Client contact deleted successfully.');
    }
}
