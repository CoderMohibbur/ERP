<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientContactDestroyRequest;
use App\Http\Requests\StoreClientContactRequest;
use App\Http\Requests\UpdateClientContactRequest;
use App\Models\Client;
use App\Models\ClientContact;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClientContactController extends Controller
{
    public function __construct()
    {
        // Read (client contacts list)
        $this->middleware('permission:client.view|client.*')->only(['index']);

        // Write (contacts)
        $this->middleware('permission:client_contact.create|client_contact.*')->only(['create', 'store']);
        $this->middleware('permission:client_contact.edit|client_contact.*')->only(['edit', 'update']);
        $this->middleware('permission:client_contact.delete|client_contact.*')->only(['destroy']);
    }

    /**
     * Display a list of contacts for a specific client.
     */
    public function index(Client $client): View
    {
        $contacts = $client->contacts()->latest()->paginate(10)->withQueryString();

        return view('client-contacts.index', compact('client', 'contacts'));
    }

    /**
     * Show form to create a new contact for a client.
     */
    public function create(Client $client): View
    {
        return view('client-contacts.create', compact('client'));
    }

    /**
     * Store a newly created contact.
     */
    public function store(StoreClientContactRequest $request, Client $client): RedirectResponse
    {
        $client->contacts()->create($request->validated());

        return redirect()->route('client-contacts.index', $client->id)
            ->with('success', 'Contact added successfully.');
    }

    /**
     * Show form to edit a contact.
     */
    public function edit(Client $client, ClientContact $clientContact): View
    {
        $this->abortIfContactNotBelongsToClient($client, $clientContact);

        return view('client-contacts.edit', compact('client', 'clientContact'));
    }

    /**
     * Update the specified contact.
     */
    public function update(UpdateClientContactRequest $request, Client $client, ClientContact $clientContact): RedirectResponse
    {
        $this->abortIfContactNotBelongsToClient($client, $clientContact);

        $clientContact->update($request->validated());

        return redirect()->route('client-contacts.index', $client->id)
            ->with('success', 'Contact updated successfully.');
    }

    /**
     * Remove the contact.
     */
    public function destroy(ClientContactDestroyRequest $request, Client $client, ClientContact $clientContact): RedirectResponse
    {
        $this->abortIfContactNotBelongsToClient($client, $clientContact);

        $clientContact->delete();

        return redirect()->route('client-contacts.index', $client->id)
            ->with('success', 'Contact deleted successfully.');
    }

    private function abortIfContactNotBelongsToClient(Client $client, ClientContact $clientContact): void
    {
        abort_unless((int) $clientContact->client_id === (int) $client->id, 404);
    }
}
