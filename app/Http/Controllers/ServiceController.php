<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceStoreRequest;
use App\Http\Requests\ServiceUpdateRequest;
use App\Models\Client;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = (int) $request->input('per_page', 15);
        $perPage = max(5, min(100, $perPage));

        $q = trim((string) $request->input('q', ''));
        $status = $request->input('status');
        $type = $request->input('type');
        $clientId = $request->input('client_id');
        $renewalFrom = $request->input('renewal_from');
        $renewalTo = $request->input('renewal_to');

        $services = Service::query()
            ->with(['client'])
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('name', 'like', "%{$q}%")
                        ->orWhere('type', 'like', "%{$q}%");
                });
            })
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($type, fn ($query) => $query->where('type', $type))
            ->when($clientId, fn ($query) => $query->where('client_id', $clientId))
            ->when($renewalFrom, fn ($query) => $query->whereDate('next_renewal_at', '>=', $renewalFrom))
            ->when($renewalTo, fn ($query) => $query->whereDate('next_renewal_at', '<=', $renewalTo))
            ->orderByDesc('id')
            ->paginate($perPage)
            ->appends($request->query());

        $statuses = ['active', 'suspended', 'cancelled', 'expired'];
        $types = ['shared_hosting', 'dedicated', 'domain', 'ssl', 'maintenance'];
        $billingCycles = ['monthly', 'quarterly', 'half_yearly', 'yearly', 'custom'];

        $clients = Client::query()->select(['id', 'name'])->orderBy('name')->get();

        return view('services.index', compact('services', 'statuses', 'types', 'billingCycles', 'clients', 'q', 'status', 'type', 'clientId', 'renewalFrom', 'renewalTo', 'perPage'));
    }

    public function create(): View
    {
        $statuses = ['active', 'suspended', 'cancelled', 'expired'];
        $types = ['shared_hosting', 'dedicated', 'domain', 'ssl', 'maintenance'];
        $billingCycles = ['monthly', 'quarterly', 'half_yearly', 'yearly', 'custom'];

        $clients = Client::query()->select(['id', 'name'])->orderBy('name')->get();

        return view('services.create', compact('statuses', 'types', 'billingCycles', 'clients'));
    }

    public function store(ServiceStoreRequest $request): RedirectResponse
    {
        $service = Service::create($request->validated());

        return redirect()
            ->route('services.show', $service)
            ->with('success', 'Service created successfully.');
    }

    public function show(Service $service): View
    {
        $service->load(['client']);

        return view('services.show', compact('service'));
    }

    public function edit(Service $service): View
    {
        $statuses = ['active', 'suspended', 'cancelled', 'expired'];
        $types = ['shared_hosting', 'dedicated', 'domain', 'ssl', 'maintenance'];
        $billingCycles = ['monthly', 'quarterly', 'half_yearly', 'yearly', 'custom'];

        $clients = Client::query()->select(['id', 'name'])->orderBy('name')->get();

        return view('services.edit', compact('service', 'statuses', 'types', 'billingCycles', 'clients'));
    }

    public function update(ServiceUpdateRequest $request, Service $service): RedirectResponse
    {
        $service->update($request->validated());

        return redirect()
            ->route('services.show', $service)
            ->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()
            ->route('services.index')
            ->with('success', 'Service deleted successfully.');
    }
}
