<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Client;
use App\Models\Deal;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Lead;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DealWonService
{
    public function handle(Deal $deal): void
    {
        if ((string) $deal->stage !== 'won') {
            return;
        }

        DB::transaction(function () use ($deal) {
            // Ensure won_at if column exists
            if (Schema::hasColumn('deals', 'won_at') && empty($deal->won_at)) {
                $deal->won_at = now();
                $deal->saveQuietly();
            }

            $client = $this->ensureClient($deal);

            $project = $this->createProjectIfNeeded($deal, $client);

            $this->createDefaultTasksIfNeeded($project);

            $this->createAdvanceInvoiceIfEligible($deal, $client);

            $this->logDealWonActivity($deal);
        });
    }

    private function ensureClient(Deal $deal): Client
    {
        // If already has client
        if (!empty($deal->client_id)) {
            return Client::query()->findOrFail($deal->client_id);
        }

        // Try from lead conversion
        $lead = null;
        if (!empty($deal->lead_id)) {
            $lead = Lead::query()->find($deal->lead_id);
            if ($lead && !empty($lead->converted_client_id)) {
                $client = Client::query()->findOrFail($lead->converted_client_id);
                $deal->client_id = $client->id;
                $deal->saveQuietly();
                return $client;
            }
        }

        // Create client from deal/lead (safe column mapping)
        $client = new Client();

        $data = [
            'name'    => $lead?->name ?? $deal->title ?? ('Client for Deal #' . $deal->id),
            'phone'   => $lead?->phone ?? null,
            'email'   => $lead?->email ?? null,
            'company' => $lead?->company ?? null,
        ];

        $this->fillIfColumnsExist($client, $data);
        $client->save();

        if ($lead && Schema::hasColumn('leads', 'converted_client_id')) {
            $lead->converted_client_id = $client->id;
            $lead->saveQuietly();
        }

        $deal->client_id = $client->id;
        $deal->saveQuietly();

        return $client;
    }

    private function createProjectIfNeeded(Deal $deal, Client $client): Project
    {
        // If deals.project_id exists and already set, reuse
        if (Schema::hasColumn('deals', 'project_id') && !empty($deal->project_id)) {
            return Project::query()->findOrFail($deal->project_id);
        }

        $project = new Project();

        $data = [
            'client_id'    => $client->id,
            'name'         => $deal->title,
            'title'        => $deal->title, // some schemas use title
            'description'  => 'Auto-created from Deal #' . $deal->id,
            'status'       => 'active',
            'start_date'   => now()->toDateString(),
        ];

        $this->fillIfColumnsExist($project, $data);
        $project->save();

        if (Schema::hasColumn('deals', 'project_id')) {
            $deal->project_id = $project->id;
            $deal->saveQuietly();
        }

        return $project;
    }

    private function createDefaultTasksIfNeeded(Project $project): void
    {
        // Prevent duplicates: if project already has tasks, skip
        if (Task::query()->where('project_id', $project->id)->exists()) {
            return;
        }

        $template = [
            'Requirement & Scope',
            'Design / UI',
            'Development',
            'Testing & QA',
            'Delivery & Handover',
        ];

        foreach ($template as $title) {
            $task = new Task();

            $data = [
                'project_id' => $project->id,
                'title'      => $title,
                'name'       => $title,
                'status'     => 'backlog',
                'priority'   => 3,
            ];

            $this->fillIfColumnsExist($task, $data);
            $task->save();
        }
    }

    private function createAdvanceInvoiceIfEligible(Deal $deal, Client $client): void
    {
        if (empty($deal->value_estimated) || (float) $deal->value_estimated <= 0) {
            return;
        }

        // Optional: only if you want always create advance invoice
        $advance = round(((float) $deal->value_estimated) * 0.5, 2);

        $invoice = new Invoice();

        $data = [
            'client_id' => $client->id,
            'due_date'  => now()->addDays(7)->toDateString(),
            'status'    => 'unpaid',
            'currency'  => $deal->currency ?? 'BDT',
            'total'     => $advance,
            'paid_total'=> 0,
            'balance'   => $advance,
            'deal_id'   => $deal->id,
        ];

        $this->fillIfColumnsExist($invoice, $data);
        $invoice->save();

        // Add one invoice item (if table exists)
        if (class_exists(InvoiceItem::class)) {
            $item = new InvoiceItem();

            $itemData = [
                'invoice_id'  => $invoice->id,
                'description' => 'Advance payment for Deal #' . $deal->id,
                'title'       => 'Advance Payment',
                'quantity'    => 1,
                'unit_price'  => $advance,
                'amount'      => $advance,
                'line_total'  => $advance,
            ];

            $this->fillIfColumnsExist($item, $itemData);
            $item->save();
        }

        // Ensure status/totals are consistent
        app(InvoiceStatusService::class)->syncInvoice((int) $invoice->id);
    }

    private function logDealWonActivity(Deal $deal): void
    {
        if (!class_exists(Activity::class)) {
            return;
        }

        $activity = new Activity();

        $data = [
            'subject'         => 'Deal won',
            'type'            => 'note',
            'body'            => 'Deal marked as WON. Automations executed.',
            'activity_at'     => now(),
            'status'          => 'done',
            'actor_id'        => auth()->id() ?? ($deal->updated_by ?? $deal->owner_id),
            'actionable_type' => Deal::class,
            'actionable_id'   => $deal->id,
        ];

        $this->fillIfColumnsExist($activity, $data);
        $activity->save();
    }

    private function fillIfColumnsExist($model, array $data): void
    {
        $table = $model->getTable();

        foreach ($data as $key => $value) {
            if (Schema::hasColumn($table, $key)) {
                $model->{$key} = $value;
            }
        }
    }
}
