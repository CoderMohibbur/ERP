<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientNote;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Requests\StoreClientNoteRequest;
use App\Http\Requests\UpdateClientNoteRequest;

class ClientNoteController extends Controller
{
    /**
     * Display a listing of the client notes.
     */
    public function index(): View
    {
        $notes = ClientNote::with(['client', 'creator'])->latest()->paginate(10);
        return view('client-notes.index', compact('notes'));
    }

    /**
     * Show the form for creating a new client note.
     */
    public function create(): View
    {
        $clients = Client::pluck('name', 'id');
        return view('client-notes.create', compact('clients'));
    }

    /**
     * Store a newly created client note in storage.
     */
    public function store(StoreClientNoteRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();

        ClientNote::create($data);

        return redirect()->route('client-notes.index')
                         ->with('success', 'Client note created successfully.');
    }

    /**
     * Show the form for editing the specified client note.
     */
    public function edit(ClientNote $clientNote): View
    {
        $clients = Client::pluck('name', 'id');
        return view('client-notes.edit', compact('clientNote', 'clients'));
    }

    /**
     * Update the specified client note in storage.
     */
    public function update(UpdateClientNoteRequest $request, ClientNote $clientNote): RedirectResponse
    {
        $clientNote->update($request->validated());

        return redirect()->route('client-notes.index')
                         ->with('success', 'Client note updated successfully.');
    }

    /**
     * Remove the specified client note from storage.
     */
    public function destroy(ClientNote $clientNote): RedirectResponse
    {
        $clientNote->delete();

        return redirect()->route('client-notes.index')
                         ->with('success', 'Client note deleted successfully.');
    }
}
