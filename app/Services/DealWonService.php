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

class DealWonService
{
    public function handle(Deal $deal): void
    {
        if ((string) $deal->stage !== Deal::STAGE_WON) {
            return;
        }

        DB::transaction(function () use ($deal) {
            // ✅ Lock deal row (prevents 2 tabs = 2 invoices/projects)
            $deal = Deal::query()->whereKey($deal->id)->lockForUpdate()->firstOrFail();

            // Ensure won_at set once (if column exists)
            if (Schema::hasColumn('deals', 'won_at') && empty($deal->won_at)) {
                $deal->won_at = now();
                $deal->saveQuietly();
            }

            $client = $this->ensureClient($deal);
            $project = $this->createProjectIfNeeded($deal, $client);

            $this->createDefaultTasksIfNeeded($project);

            $this->createAdvanceInvoiceIfEligible($deal, $client);

            $this->logDealWonActivityIfMissing($deal);
        }, 3);
    }

    private function ensureClient(Deal $deal): Client
    {
        if (!empty($deal->client_id)) {
            return Client::query()->findOrFail($deal->client_id);
        }

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

        // Create client from lead/deal (column-safe)
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
        if (Schema::hasColumn('deals', 'project_id') && !empty($deal->project_id)) {
            return Project::query()->findOrFail($deal->project_id);
        }

        $project = new Project();

        $data = [
            'client_id'   => $client->id,
            'name'        => $deal->title,
            'title'       => $deal->title,
            'description' => 'Auto-created from Deal #' . $deal->id,
            'status'      => 'active',
            'start_date'  => now()->toDateString(),
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
        if (!Schema::hasColumn('tasks', 'project_id')) {
            return;
        }

        // ✅ idempotent: if any task exists in project, do nothing
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
                'status'     => 'backlog', // TaskStatus per spec
                'priority'   => 3,
            ];

            $this->fillIfColumnsExist($task, $data);
            $task->save();
        }
    }

    private function createAdvanceInvoiceIfEligible(Deal $deal, Client $client): void
    {
        if (!Schema::hasTable('invoices')) {
            return;
        }

        $estimated = (float) ($deal->value_estimated ?? 0);
        if ($estimated <= 0) {
            return;
        }

        // ✅ Strong idempotency: deals.advance_invoice_id is the single source of truth
        if (Schema::hasColumn('deals', 'advance_invoice_id') && !empty($deal->advance_invoice_id)) {
            // If linked invoice exists, stop. (If deleted, you can recreate by manually clearing advance_invoice_id)
            if (Invoice::query()->whereKey($deal->advance_invoice_id)->exists()) {
                return;
            }
        }

        // Secondary idempotency (if you already have invoices.deal_id in your system)
        if (Schema::hasColumn('invoices', 'deal_id')) {
            $exists = Invoice::query()
                ->where('deal_id', $deal->id)
                ->exists();

            if ($exists) {
                return;
            }
        }

        $advance = round($estimated * 0.5, 2);

        $invoice = new Invoice();

        $data = [
            'client_id' => $client->id,
            'due_date'  => now()->addDays(7)->toDateString(),
            'status'    => 'unpaid',
            'currency'  => $deal->currency ?? 'BDT',

            // totals (if columns exist)
            'total'      => $advance,
            'paid_total' => 0,
            'balance'    => $advance,

            // optional linkage
            'deal_id' => $deal->id,
        ];

        $this->fillIfColumnsExist($invoice, $data);
        $invoice->save();

        // Save link on deal for hard idempotency
        if (Schema::hasColumn('deals', 'advance_invoice_id')) {
            $deal->advance_invoice_id = $invoice->id;
            $deal->saveQuietly();
        }

        // Add one invoice item (if model/table exists)
        if (class_exists(InvoiceItem::class) && Schema::hasTable('invoice_items')) {
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

        // Keep invoice totals/status consistent (if service exists)
        if (class_exists(InvoiceStatusService::class)) {
            app(InvoiceStatusService::class)->syncInvoice((int) $invoice->id);
        }
    }

    private function logDealWonActivityIfMissing(Deal $deal): void
    {
        if (!class_exists(Activity::class) || !Schema::hasTable('activities')) {
            return;
        }

        // ✅ idempotent: do not create duplicate "Deal won" activity for same deal
        $exists = Activity::query()
            ->where('actionable_type', Deal::class)
            ->where('actionable_id', $deal->id)
            ->where('subject', 'Deal won')
            ->exists();

        if ($exists) {
            return;
        }

        $activity = new Activity();

        $data = [
            'subject'         => 'Deal won',
            'type'            => 'note',
            'body'            => 'Deal marked as WON. Automations executed (client/project/tasks/invoice).',
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
