<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientDestroyRequest;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:client.view|client.*')->only(['index', 'show']);
        $this->middleware('permission:client.create|client.*')->only(['create', 'store']);
        $this->middleware('permission:client.edit|client.*')->only(['edit', 'update']);
        $this->middleware('permission:client.delete|client.*')->only(['destroy']);
    }

    /**
     * Display a listing of clients.
     */
    public function index(Request $request): View
    {
        $query = Client::query();

        // 🔍 Optional search by name/email/company_name
        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%");
            });
        }

        $clients = $query->latest()->paginate(15)->withQueryString();

        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new client.
     */
    public function create(): View
    {
        return view('clients.create');
    }

    /**
     * Store a newly created client in storage.
     */
    public function store(StoreClientRequest $request): RedirectResponse
    {
        $data = $this->normalizeClientPayload($request->validated());

        $data['created_by'] = Auth::id();

        Client::create($data);

        return redirect()->route('clients.index')->with('success', 'Client created successfully.');
    }

    /**
     * Show a single client (optional if you have route/view).
     */
    public function show(Client $client): View
    {
        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified client.
     */
    public function edit(Client $client): View
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified client in storage.
     */
    public function update(UpdateClientRequest $request, Client $client): RedirectResponse
    {
        $data = $this->normalizeClientPayload($request->validated());

        $data['updated_by'] = Auth::id();

        $client->update($data);

        return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
    }

    /**
     * Remove the specified client from storage.
     */
    public function destroy(ClientDestroyRequest $request, Client $client): RedirectResponse
    {
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
    }

    /**
     * Normalize payload:
     * - accepts custom_fields as JSON string or array
     * - backward compat: company -> company_name
     */
    private function normalizeClientPayload(array $data): array
    {
        // Backward-compat: old field `company` -> new `company_name`
        if (empty($data['company_name']) && !empty($data['company'])) {
            $data['company_name'] = $data['company'];
        }

        // Normalize custom_fields
        if (array_key_exists('custom_fields', $data)) {
            if ($data['custom_fields'] === null) {
                return $data;
            }

            // If UI sends JSON string, decode it
            if (is_string($data['custom_fields'])) {
                $decoded = json_decode($data['custom_fields'], true);
                $data['custom_fields'] = (json_last_error() === JSON_ERROR_NONE && is_array($decoded))
                    ? $decoded
                    : null;
            }

            // Ensure array; otherwise null
            if (!is_array($data['custom_fields'])) {
                $data['custom_fields'] = null;
            }
        }

        return $data;
    }
}
