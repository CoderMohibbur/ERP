<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class ClientController extends Controller
{
    /**
     * Display a listing of clients.
     */
    public function index(Request $request): View
    {
        $query = Client::query();

        // 🔍 Optional search by name/email/company
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('company_name', 'like', "%$search%");
            });
        }

        $clients = $query->latest()->paginate(10)->withQueryString();

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
    public function store(Request $request): RedirectResponse
    {
        // ✅ Convert custom_fields JSON string to array (if present)
        if ($request->filled('custom_fields')) {
            $decoded = json_decode($request->input('custom_fields'), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withErrors([
                    'custom_fields' => '❌ Invalid JSON format in custom fields.'
                ])->withInput();
            }

            // overwrite request input
            $request->merge(['custom_fields' => $decoded]);
        }

        // ✅ Validation
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'nullable|email|unique:clients,email',
            'phone'          => 'nullable|string|max:20',
            'address'        => 'nullable|string|max:255',
            'company_name'   => 'nullable|string|max:255',
            'industry_type'  => 'nullable|string|max:100',
            'website'        => 'nullable|url|max:255',
            'tax_id'         => 'nullable|string|max:100',
            'status'         => 'required|in:active,inactive',
            'custom_fields'  => 'nullable|array', // ✅ now it's safe
        ]);

        $data['created_by'] = Auth::id();

        Client::create($data);

        return redirect()->route('clients.index')->with('success', 'Client created successfully.');
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
    public function update(Request $request, Client $client): RedirectResponse
    {
        // ✅ Convert JSON to array first
        if ($request->filled('custom_fields')) {
            $decoded = json_decode($request->input('custom_fields'), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withErrors([
                    'custom_fields' => '❌ Invalid JSON format in custom fields.'
                ])->withInput();
            }

            $request->merge(['custom_fields' => $decoded]);
        }

        // ✅ Validation
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'nullable|email|unique:clients,email,' . $client->id,
            'phone'          => 'nullable|string|max:20',
            'address'        => 'nullable|string|max:255',
            'company_name'   => 'nullable|string|max:255',
            'industry_type'  => 'nullable|string|max:100',
            'website'        => 'nullable|url|max:255',
            'tax_id'         => 'nullable|string|max:100',
            'status'         => 'required|in:active,inactive',
            'custom_fields'  => 'nullable|array',
        ]);

        $data['updated_by'] = Auth::id();

        $client->update($data);

        return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
    }


    /**
     * Remove the specified client from storage.
     */
    public function destroy(Client $client): RedirectResponse
    {
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
    }
}
