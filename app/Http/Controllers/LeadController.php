<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadConvertRequest;
use App\Http\Requests\LeadStoreRequest;
use App\Http\Requests\LeadUpdateRequest;
use App\Models\Lead;
use App\Models\User;
use App\Services\LeadConversionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        /**
         * Permission strategy (Spatie):
         * - Maintain existing CRUD protection pattern like other controllers (client.* etc).
         * - Add convert permission explicitly.
         */
        $this->middleware('permission:lead.view|lead.*')->only(['index', 'show']);
        $this->middleware('permission:lead.create|lead.*')->only(['create', 'store']);
        $this->middleware('permission:lead.edit|lead.*')->only(['edit', 'update']);
        $this->middleware('permission:lead.delete|lead.*')->only(['destroy']);
        $this->middleware('permission:lead.convert|lead.*')->only(['convert']);
    }

    public function index(Request $request): View
    {
        $perPage = (int) $request->input('per_page', 15);
        $perPage = max(5, min(100, $perPage));

        $q = trim((string) $request->input('q', ''));
        $status = $request->input('status');
        $source = $request->input('source');
        $ownerId = $request->input('owner_id');
        $followUpFrom = $request->input('follow_up_from');
        $followUpTo = $request->input('follow_up_to');

        $leads = Lead::query()
            // N+1 safe + lighter payload (only id,name from owner)
            ->with(['owner:id,name'])
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('name', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($source, fn ($query) => $query->where('source', $source))
            ->when($ownerId, fn ($query) => $query->where('owner_id', $ownerId))
            ->when($followUpFrom, fn ($query) => $query->whereDate('next_follow_up_at', '>=', $followUpFrom))
            ->when($followUpTo, fn ($query) => $query->whereDate('next_follow_up_at', '<=', $followUpTo))
            ->orderByDesc('id')
            ->paginate($perPage)
            ->appends($request->query());

        $statuses = $this->leadStatuses();
        $sources = $this->leadSources();

        $owners = $this->leadOwners();

        return view('leads.index', compact(
            'leads',
            'statuses',
            'sources',
            'owners',
            'q',
            'status',
            'source',
            'ownerId',
            'followUpFrom',
            'followUpTo',
            'perPage'
        ));
    }

    public function create(): View
    {
        $statuses = $this->leadStatuses();
        $sources = $this->leadSources();
        $owners = $this->leadOwners();

        return view('leads.create', compact('statuses', 'sources', 'owners'));
    }

    public function store(LeadStoreRequest $request): RedirectResponse
    {
        $lead = Lead::create($request->validated());

        return redirect()
            ->route('leads.show', $lead)
            ->with('success', 'Lead created successfully.');
    }

    public function show(Lead $lead): View
    {
        // N+1 safe for lead show page (if view lists activities/deals/clients/actor names)
        $lead->loadMissing([
            'owner:id,name',
            'convertedClient:id,name',
            'deals' => function ($q) {
                $q->with([
                    'client:id,name',
                    'owner:id,name',
                ])->orderByDesc('id');
            },
            'activities' => function ($q) {
                $q->with(['actor:id,name'])
                    ->orderByDesc('activity_at')
                    ->orderByDesc('id');
            },
        ]);

        return view('leads.show', compact('lead'));
    }

    public function edit(Lead $lead): View
    {
        $statuses = $this->leadStatuses();
        $sources = $this->leadSources();
        $owners = $this->leadOwners();

        return view('leads.edit', compact('lead', 'statuses', 'sources', 'owners'));
    }

    public function update(LeadUpdateRequest $request, Lead $lead): RedirectResponse
    {
        $lead->update($request->validated());

        return redirect()
            ->route('leads.show', $lead)
            ->with('success', 'Lead updated successfully.');
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $lead->delete(); // soft delete if model/table uses SoftDeletes

        return redirect()
            ->route('leads.index')
            ->with('success', 'Lead deleted successfully.');
    }

    /**
     * Convert a Lead into a Client (create new OR link existing).
     */
    public function convert(
        LeadConvertRequest $request,
        Lead $lead,
        LeadConversionService $service
    ): RedirectResponse {
        $client = $service->convert(
            lead: $lead,
            payload: $request->validated(),
            actorId: (int) $request->user()->id
        );

        return redirect()
            ->route('clients.show', $client)
            ->with('success', 'Lead converted to client successfully.');
    }

    /**
     * Keep enums centralized.
     * If Lead model has constants, use them; fallback to current known lists.
     */
    private function leadStatuses(): array
    {
        if (defined(Lead::class . '::STATUSES')) {
            /** @phpstan-ignore-next-line */
            $statuses = Lead::STATUSES;
            if (is_array($statuses) && !empty($statuses)) {
                return $statuses;
            }
        }

        return ['new', 'contacted', 'qualified', 'unqualified'];
    }

    private function leadSources(): array
    {
        if (defined(Lead::class . '::SOURCES')) {
            /** @phpstan-ignore-next-line */
            $sources = Lead::SOURCES;
            if (is_array($sources) && !empty($sources)) {
                return $sources;
            }
        }

        return ['whatsapp', 'facebook', 'website', 'referral'];
    }

    private function leadOwners()
    {
        return User::query()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();
    }
}
