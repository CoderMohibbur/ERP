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
    /**
     * Main entry: call after deal stage update.
     */
    public function handle(Deal $deal): void
    {
        // ✅ Only run when stage is WON (use model constant)
        if ((string) $deal->stage !== Deal::STAGE_WON) {
            return;
        }

        DB::transaction(function () use ($deal) {
            // ✅ Lock deal row (prevents 2 tabs = 2 invoices/projects)
            $deal = Deal::query()
                ->whereKey($deal->id)
                ->lockForUpdate()
                ->firstOrFail();

            // ✅ Ensure won_at set once (if column exists)
            if (Schema::hasColumn('deals', 'won_at') && empty($deal->won_at)) {
                $deal->won_at = now();
                $deal->saveQuietly();
            }

            $client  = $this->ensureClient($deal);
            $project = $this->createProjectIfNeeded($deal, $client);

            $this->createDefaultTasksIfNeeded($project);

            $this->createAdvanceInvoiceIfEligible($deal, $client);

            $this->logDealWonActivityIfMissing($deal);
        }, 3);
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

        // Create client from lead/deal (column-safe)
        $client = new Client();

        $data = [
            'name'         => $lead?->name ?? $deal->title ?? ('Client for Deal #' . $deal->id),
            'phone'        => $lead?->phone ?? null,
            'email'        => $lead?->email ?? null,

            // some schemas use company_name, some use company
            'company_name' => $lead?->company ?? null,
            'company'      => $lead?->company ?? null,

            // optional audit fields
            'created_by'   => auth()->id(),
        ];

        $this->fillIfColumnsExist($client, $data);
        $client->save();

        // back-link to lead (if supported)
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
            'title'        => $deal->title,
            'description'  => 'Auto-created from Deal #' . $deal->id,
            'status'       => 'active',
            'start_date'   => now()->toDateString(),

            // optional audit
            'created_by'   => auth()->id(),
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
        if (!Schema::hasTable('tasks') || !Schema::hasColumn('tasks', 'project_id')) {
            return;
        }

        // ✅ Idempotent: if any task exists in project, do nothing
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

                // status should match your Task status enum(s)
                'status'     => 'backlog',
                'priority'   => 3,

                // optional audit
                'created_by' => auth()->id(),
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
            if (Invoice::query()->whereKey($deal->advance_invoice_id)->exists()) {
                return;
            }
            // If linked invoice was deleted, recreate only if you manually clear advance_invoice_id
            return;
        }

        // Secondary idempotency (if invoices.deal_id exists)
        if (Schema::hasColumn('invoices', 'deal_id')) {
            $exists = Invoice::query()->where('deal_id', $deal->id)->exists();
            if ($exists) {
                return;
            }
        }

        $advance = round($estimated * 0.5, 2);

        $invoice = new Invoice();

        // invoice_number only if required by schema
        $generatedNumber = 'INV-' . now()->format('Ymd-His') . '-' . Str::upper(Str::random(4));

        $data = [
            'client_id' => $client->id,
            'deal_id'   => $deal->id,

            'currency'  => $deal->currency ?? 'BDT',
            'due_date'  => now()->addDays(7)->toDateString(),

            // ✅ support both field styles
            'status'       => 'unpaid',
            'erp_status'   => 'unpaid',

            'total'        => $advance,
            'total_amount' => $advance,

            'paid_total'   => 0,
            'paid_amount'  => 0,

            'balance'      => $advance,
            'due_amount'   => $advance,

            // optional "old schema" fields (only if exist)
            'invoice_number' => $generatedNumber,
            'issue_date'     => now()->toDateString(),
            'invoice_type'   => 'advance',
            'created_by'     => auth()->id(),
        ];

        $this->fillIfColumnsExist($invoice, $data);
        $invoice->save();

        // Save link on deal for hard idempotency
        if (Schema::hasColumn('deals', 'advance_invoice_id')) {
            $deal->advance_invoice_id = $invoice->id;
            $deal->saveQuietly();
        }

        // Add one invoice item (if table exists)
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
        if (class_exists(\App\Services\InvoiceStatusService::class)) {
            app(\App\Services\InvoiceStatusService::class)->syncInvoice((int) $invoice->id);
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
            'type'            => Activity::TYPE_NOTE ?? 'note',
            'body'            => 'Deal marked as WON. Automations executed (client/project/tasks/invoice).',
            'activity_at'     => now(),
            'status'          => Activity::STATUS_DONE ?? 'done',
            'actor_id'        => auth()->id() ?? ($deal->updated_by ?? $deal->owner_id),
            'actionable_type' => Deal::class,
            'actionable_id'   => $deal->id,
        ];

        $this->fillIfColumnsExist($activity, $data);
        $activity->save();
    }

    /**
     * Safe assignment: set only columns that exist in DB table
     */
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
