<?php

namespace App\Http\Controllers;

use App\Http\Requests\DealStoreRequest;
use App\Http\Requests\DealUpdateRequest;
use App\Http\Requests\DealStageUpdateRequest;
use App\Models\Client;
use App\Models\Deal;
use App\Models\Lead;
use App\Services\DealWonService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DealController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = (int) $request->input('per_page', 15);
        $perPage = max(5, min(100, $perPage));

        $q = trim((string) $request->input('q', ''));
        $stage = $request->input('stage');
        $leadId = $request->input('lead_id');
        $clientId = $request->input('client_id');
        $closeFrom = $request->input('close_from');
        $closeTo = $request->input('close_to');

        $deals = Deal::query()
            ->with([
                'lead:id,name',
                'client:id,name',
                'owner:id,name',
            ])
            ->when($q !== '', function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%");
            })
            ->when($stage, fn ($query) => $query->where('stage', $stage))
            ->when($leadId, fn ($query) => $query->where('lead_id', $leadId))
            ->when($clientId, fn ($query) => $query->where('client_id', $clientId))
            ->when($closeFrom, fn ($query) => $query->whereDate('expected_close_date', '>=', $closeFrom))
            ->when($closeTo, fn ($query) => $query->whereDate('expected_close_date', '<=', $closeTo))
            ->orderByDesc('id')
            ->paginate($perPage)
            ->appends($request->query());

        $stages = Deal::STAGES;

        $leads = Lead::query()->select(['id', 'name'])->orderBy('name')->get();
        $clients = Client::query()->select(['id', 'name'])->orderBy('name')->get();

        return view('deals.index', compact('deals', 'stages', 'leads', 'clients', 'q', 'stage', 'leadId', 'clientId', 'closeFrom', 'closeTo', 'perPage'));
    }

    public function create(): View
    {
        $stages = Deal::STAGES;

        $leads = Lead::query()->select(['id', 'name'])->orderBy('name')->get();
        $clients = Client::query()->select(['id', 'name'])->orderBy('name')->get();

        return view('deals.create', compact('stages', 'leads', 'clients'));
    }

    public function store(DealStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $deal = new Deal();
        $deal->fill($data);

        $this->applyStageSideEffects($deal, (string) $deal->stage, null);
        $deal->save();

        // ✅ Mandatory: deal won automation (idempotent service)
        if ((string) $deal->stage === Deal::STAGE_WON) {
            app(DealWonService::class)->handle($deal->fresh());
        }

        return redirect()
            ->route('deals.show', $deal)
            ->with('success', 'Deal created successfully.');
    }

    public function show(Deal $deal): View
    {
        $deal->load([
            'lead:id,name',
            'client:id,name',
            'owner:id,name',
            'project:id,name,title,client_id',
            'advanceInvoice:id,client_id,status,total,due_date',
        ]);

        return view('deals.show', compact('deal'));
    }

    public function edit(Deal $deal): View
    {
        $stages = Deal::STAGES;

        $leads = Lead::query()->select(['id', 'name'])->orderBy('name')->get();
        $clients = Client::query()->select(['id', 'name'])->orderBy('name')->get();

        return view('deals.edit', compact('deal', 'stages', 'leads', 'clients'));
    }

    public function update(DealUpdateRequest $request, Deal $deal): RedirectResponse
    {
        $data = $request->validated();

        $deal->fill($data);

        $this->applyStageSideEffects($deal, (string) $deal->stage, null);
        $deal->save();

        // ✅ Mandatory: deal won automation (idempotent)
        if ((string) $deal->stage === Deal::STAGE_WON) {
            app(DealWonService::class)->handle($deal->fresh());
        }

        return redirect()
            ->route('deals.show', $deal)
            ->with('success', 'Deal updated successfully.');
    }

    public function destroy(Deal $deal): RedirectResponse
    {
        $deal->delete();

        return redirect()
            ->route('deals.index')
            ->with('success', 'Deal deleted successfully.');
    }

    public function pipeline(Request $request): View
    {
        $q = trim((string) $request->input('q', ''));
        $leadId = $request->input('lead_id');
        $clientId = $request->input('client_id');

        $stages = Deal::STAGES;

        $all = Deal::query()
            ->with(['lead:id,name', 'client:id,name', 'owner:id,name'])
            ->when($q !== '', fn ($query) => $query->where('title', 'like', "%{$q}%"))
            ->when($leadId, fn ($query) => $query->where('lead_id', $leadId))
            ->when($clientId, fn ($query) => $query->where('client_id', $clientId))
            ->orderByDesc('updated_at')
            ->get();

        $grouped = $all->groupBy('stage');

        $pipeline = collect($stages)->mapWithKeys(function (string $stage) use ($grouped) {
            return [$stage => $grouped->get($stage, collect())];
        });

        $leads = Lead::query()->select(['id', 'name'])->orderBy('name')->get();
        $clients = Client::query()->select(['id', 'name'])->orderBy('name')->get();

        return view('deals.pipeline', compact('pipeline', 'stages', 'leads', 'clients', 'q', 'leadId', 'clientId'));
    }

    public function updateStage(DealStageUpdateRequest $request, Deal $deal)
    {
        $data = $request->validated();

        $stage = (string) $data['stage'];
        $lostReason = $data['lost_reason'] ?? null;

        $deal->stage = $stage;
        $this->applyStageSideEffects($deal, $stage, $lostReason);
        $deal->save();

        // ✅ Mandatory: deal won automation (idempotent)
        if ((string) $deal->stage === Deal::STAGE_WON) {
            app(DealWonService::class)->handle($deal->fresh());
        }

        if ($request->wantsJson()) {
            return response()->json([
                'ok' => true,
                'id' => $deal->id,
                'stage' => $deal->stage,
                'won_at' => $deal->won_at,
                'lost_at' => $deal->lost_at,
                'lost_reason' => $deal->lost_reason,
            ]);
        }

        return back()->with('success', 'Deal stage updated successfully.');
    }

    /**
     * Side-effects for stage transitions:
     * - won  => set won_at once (do not overwrite if already set), clear lost fields
     * - lost => set lost_at once (do not overwrite if already set), clear won_at, set lost_reason
     * - else => clear won/lost fields
     */
    private function applyStageSideEffects(Deal $deal, string $stage, ?string $lostReason): void
    {
        if ($stage === Deal::STAGE_WON) {
            if (empty($deal->won_at)) {
                $deal->won_at = Carbon::now();
            }
            $deal->lost_at = null;
            $deal->lost_reason = null;
            return;
        }

        if ($stage === Deal::STAGE_LOST) {
            if (empty($deal->lost_at)) {
                $deal->lost_at = Carbon::now();
            }
            $deal->won_at = null;
            $deal->lost_reason = $lostReason;
            return;
        }

        $deal->won_at = null;
        $deal->lost_at = null;
        $deal->lost_reason = null;
    }
}
