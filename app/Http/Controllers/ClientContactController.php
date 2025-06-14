<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientContact;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ClientContactController extends Controller
{
    /**
     * Display a list of contacts for a specific client.
     */
    public function index(Client $client)
    {
        $contacts = $client->contacts()->latest()->paginate(10);
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
    public function store(Request $request, Client $client): RedirectResponse
    {
        $data = $request->validate([
            'type'  => 'required|string|max:50',
            'value' => 'required|string|max:255',
        ]);

        $client->contacts()->create($data);

        return redirect()->route('client-contacts.index', $client->id)
            ->with('success', 'Contact added successfully.');
    }

    /**
     * Show form to edit a contact.
     */
    public function edit(Client $client, ClientContact $clientContact): View
    {
        return view('client-contacts.edit', compact('client', 'clientContact'));
    }

    /**
     * Update the specified contact.
     */
    public function update(Request $request, Client $client, ClientContact $clientContact): RedirectResponse
    {
        $data = $request->validate([
            'type'  => 'required|string|max:50',
            'value' => 'required|string|max:255',
        ]);

        $clientContact->update($data);

        return redirect()->route('client-contacts.index', $client->id)
            ->with('success', 'Contact updated successfully.');
    }

    /**
     * Remove the contact.
     */
    public function destroy(Client $client, ClientContact $clientContact): RedirectResponse
    {
        $clientContact->delete();

        return redirect()->route('client-contacts.index', $client->id)
            ->with('success', 'Contact deleted successfully.');
    }
}
