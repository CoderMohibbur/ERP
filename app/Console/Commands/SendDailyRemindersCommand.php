<?php

namespace App\Console\Commands;

use App\Models\Activity;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\ReminderLog;
use App\Models\Service;
use App\Models\User;
use App\Notifications\DueReminderNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SendDailyRemindersCommand extends Command
{
    protected $signature = 'erp:daily-reminders {--dry-run : Do not write DB, only show counts}';
    protected $description = 'Send daily reminders: renewals due, invoice due, follow-up due (Activity + DB notification).';

    public function handle(): int
    {
        $lock = Cache::lock('erp:daily-reminders-lock', 55 * 60);

        if (! $lock->get()) {
            $this->info('Skipped: another reminder run is in progress.');
            return self::SUCCESS;
        }

        try {
            $today = Carbon::today(config('app.timezone'));

            $owners = $this->ownerUsers();
            $actor  = $owners->first() ?? User::query()->orderBy('id')->first();

            if (! $actor) {
                $this->warn('No users found. Skipping reminders safely.');
                return self::SUCCESS;
            }

            $dryRun = (bool) $this->option('dry-run');

            $renewalCount  = $this->processRenewalReminders($today, $owners, $actor->id, $dryRun);
            $invoiceCount  = $this->processInvoiceReminders($today, $owners, $actor->id, $dryRun);
            $followupCount = $this->processFollowupReminders($today, $owners, $actor->id, $dryRun);

            $this->info("Done. Renewals={$renewalCount}, Invoices={$invoiceCount}, Followups={$followupCount}");
            return self::SUCCESS;
        } finally {
            optional($lock)->release();
        }
    }

    private function processRenewalReminders(Carbon $today, Collection $owners, int $actorId, bool $dryRun): int
    {
        // Spec style reminders: today+7, today+3, today
        $offsets = config('reminders.renewal_days_before', [7, 3, 0]);

        $targetDates = collect($offsets)->map(fn ($d) => $today->copy()->addDays((int) $d)->toDateString())->all();

        $services = Service::query()
            ->with('client')
            ->where('status', 'active')
            ->whereNotNull('next_renewal_at')
            ->whereIn(DB::raw('DATE(next_renewal_at)'), $targetDates)
            ->orderBy('next_renewal_at')
            ->limit(config('reminders.max_per_type', 50))
            ->get();

        $sent = 0;

        foreach ($services as $service) {
            foreach ($offsets as $daysBefore) {
                $remindDate = $today->copy()->addDays((int) $daysBefore)->toDateString();
                if ($service->next_renewal_at?->format('Y-m-d') !== $remindDate) {
                    continue;
                }

                $type = "renewal_due_{$daysBefore}";
                $keyDate = Carbon::parse($remindDate, config('app.timezone'))->toDateString();

                if (! $this->shouldSend($type, Service::class, (int) $service->id, $keyDate)) {
                    continue;
                }

                $clientId = (int) $service->client_id;

                $title = "Renewal due ({$daysBefore}d)";
                $msg   = "Service: {$service->type} - {$service->name} | Client: " . ($service->client->name ?? "#{$clientId}") .
                    " | Renewal: " . ($service->next_renewal_at?->format('Y-m-d') ?? '');

                $url = url("/services/{$service->id}");

                if (! $dryRun) {
                    $this->createActivity([
                        'subject'         => "[REMINDER] Renewal due ({$daysBefore}d)",
                        'type'            => 'note',
                        'body'            => $msg,
                        'activity_at'     => now(),
                        'next_follow_up_at' => null,
                        'status'          => 'open',
                        'actor_id'        => $actorId,
                        'actionable_type' => \App\Models\Client::class,
                        'actionable_id'   => $clientId,
                    ]);

                    $this->notifyOwners($owners, new DueReminderNotification(
                        title: $title,
                        message: $msg,
                        url: $url,
                        meta: [
                            'reminder_type' => $type,
                            'service_id' => (int) $service->id,
                            'client_id' => $clientId,
                            'next_renewal_at' => $service->next_renewal_at?->format('Y-m-d'),
                            'days_before' => (int) $daysBefore,
                        ]
                    ));

                    $this->markSent($type, Service::class, (int) $service->id, $keyDate, [
                        'client_id' => $clientId,
                        'next_renewal_at' => $service->next_renewal_at?->format('Y-m-d'),
                        'days_before' => (int) $daysBefore,
                    ]);
                }

                $sent++;
            }
        }

        return $sent;
    }

    private function processInvoiceReminders(Carbon $today, Collection $owners, int $actorId, bool $dryRun): int
    {
        $offsets = config('reminders.invoice_days_before', [7, 3, 0]);
        $maxDaysAhead = max($offsets);

        $invoices = Invoice::query()
            ->with('client')
            ->whereIn('status', ['unpaid', 'partial'])
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<=', $today->copy()->addDays($maxDaysAhead)->toDateString())
            ->orderBy('due_date')
            ->limit(config('reminders.max_per_type', 50))
            ->get();

        $sent = 0;

        foreach ($invoices as $invoice) {
            $due = $invoice->due_date ? Carbon::parse($invoice->due_date, config('app.timezone')) : null;
            if (! $due) continue;

            $balance = $invoice->balance ?? max(0, (float) ($invoice->total ?? 0) - (float) ($invoice->paid_total ?? 0));
            $clientId = (int) ($invoice->client_id ?? 0);

            // Overdue rule
            if ($due->lt($today)) {
                $type = "invoice_overdue";
                $keyDate = $today->toDateString(); // daily (so it can repeat per day)

                // If you want less spam, change config reminders.overdue_repeat_days and skip on non-match
                $repeatDays = (int) config('reminders.overdue_repeat_days', 1);
                if ($repeatDays > 1) {
                    $daysSince = $due->diffInDays($today);
                    if (($daysSince % $repeatDays) !== 0) {
                        continue;
                    }
                }

                if (! $this->shouldSend($type, Invoice::class, (int) $invoice->id, $keyDate)) {
                    continue;
                }

                $msg = "Invoice #{$invoice->id} overdue | Client: " . ($invoice->client->name ?? "#{$clientId}") .
                    " | Due: {$due->format('Y-m-d')} | Balance: {$balance}";
                $url = url("/invoices/{$invoice->id}");

                if (! $dryRun) {
                    $this->createActivity([
                        'subject'         => "[REMINDER] Invoice overdue",
                        'type'            => 'note',
                        'body'            => $msg,
                        'activity_at'     => now(),
                        'next_follow_up_at' => null,
                        'status'          => 'open',
                        'actor_id'        => $actorId,
                        'actionable_type' => \App\Models\Client::class,
                        'actionable_id'   => $clientId,
                    ]);

                    $this->notifyOwners($owners, new DueReminderNotification(
                        title: "Invoice overdue",
                        message: $msg,
                        url: $url,
                        meta: [
                            'reminder_type' => $type,
                            'invoice_id' => (int) $invoice->id,
                            'client_id' => $clientId,
                            'due_date' => $due->format('Y-m-d'),
                            'balance' => $balance,
                        ]
                    ));

                    $this->markSent($type, Invoice::class, (int) $invoice->id, $keyDate, [
                        'client_id' => $clientId,
                        'due_date' => $due->format('Y-m-d'),
                        'balance' => $balance,
                    ]);
                }

                $sent++;
                continue;
            }

            // Due soon rules: 7/3/0 days before
            foreach ($offsets as $daysBefore) {
                $target = $today->copy()->addDays((int) $daysBefore)->toDateString();
                if ($due->toDateString() !== $target) continue;

                $type = "invoice_due_{$daysBefore}";
                $keyDate = $due->toDateString();

                if (! $this->shouldSend($type, Invoice::class, (int) $invoice->id, $keyDate)) {
                    continue;
                }

                $msg = "Invoice #{$invoice->id} due in {$daysBefore}d | Client: " . ($invoice->client->name ?? "#{$clientId}") .
                    " | Due: {$due->format('Y-m-d')} | Balance: {$balance}";
                $url = url("/invoices/{$invoice->id}");

                if (! $dryRun) {
                    $this->createActivity([
                        'subject'         => "[REMINDER] Invoice due ({$daysBefore}d)",
                        'type'            => 'note',
                        'body'            => $msg,
                        'activity_at'     => now(),
                        'next_follow_up_at' => null,
                        'status'          => 'open',
                        'actor_id'        => $actorId,
                        'actionable_type' => \App\Models\Client::class,
                        'actionable_id'   => $clientId,
                    ]);

                    $this->notifyOwners($owners, new DueReminderNotification(
                        title: "Invoice due ({$daysBefore}d)",
                        message: $msg,
                        url: $url,
                        meta: [
                            'reminder_type' => $type,
                            'invoice_id' => (int) $invoice->id,
                            'client_id' => $clientId,
                            'due_date' => $due->format('Y-m-d'),
                            'days_before' => (int) $daysBefore,
                            'balance' => $balance,
                        ]
                    ));

                    $this->markSent($type, Invoice::class, (int) $invoice->id, $keyDate, [
                        'client_id' => $clientId,
                        'due_date' => $due->format('Y-m-d'),
                        'days_before' => (int) $daysBefore,
                        'balance' => $balance,
                    ]);
                }

                $sent++;
            }
        }

        return $sent;
    }

    private function processFollowupReminders(Carbon $today, Collection $owners, int $actorId, bool $dryRun): int
    {
        $sent = 0;

        // A) Leads follow-up due (Spec: leads.next_follow_up_at <= today)
        $leads = Lead::query()
            ->whereNotNull('next_follow_up_at')
            ->whereDate('next_follow_up_at', '<=', $today->toDateString())
            ->orderBy('next_follow_up_at')
            ->limit(config('reminders.max_per_type', 50))
            ->get();

        foreach ($leads as $lead) {
            $type = 'followup_due_lead';
            $keyDate = $today->toDateString(); // daily until updated

            if (! $this->shouldSend($type, Lead::class, (int) $lead->id, $keyDate)) {
                continue;
            }

            $msg = "Lead #{$lead->id} follow-up due | {$lead->name} | Phone: {$lead->phone} | Next: " .
                Carbon::parse($lead->next_follow_up_at)->format('Y-m-d H:i');

            $url = url("/leads/{$lead->id}");

            if (! $dryRun) {
                $this->createActivity([
                    'subject'         => "[REMINDER] Follow-up due (Lead)",
                    'type'            => 'note',
                    'body'            => $msg,
                    'activity_at'     => now(),
                    'next_follow_up_at' => null,
                    'status'          => 'open',
                    'actor_id'        => $actorId,
                    'actionable_type' => Lead::class,
                    'actionable_id'   => (int) $lead->id,
                ]);

                $this->notifyOwners($owners, new DueReminderNotification(
                    title: "Follow-up due (Lead)",
                    message: $msg,
                    url: $url,
                    meta: [
                        'reminder_type' => $type,
                        'lead_id' => (int) $lead->id,
                        'next_follow_up_at' => Carbon::parse($lead->next_follow_up_at)->toIso8601String(),
                    ]
                ));

                $this->markSent($type, Lead::class, (int) $lead->id, $keyDate, [
                    'next_follow_up_at' => Carbon::parse($lead->next_follow_up_at)->toIso8601String(),
                ]);
            }

            $sent++;
        }

        // B) Activities follow-up due (Spec: activities.next_follow_up_at today / due)
        $activities = Activity::query()
            ->where('status', 'open')
            ->whereNotNull('next_follow_up_at')
            ->whereDate('next_follow_up_at', '<=', $today->toDateString())
            ->orderBy('next_follow_up_at')
            ->limit(config('reminders.max_per_type', 50))
            ->get();

        foreach ($activities as $activity) {
            $type = 'followup_due_activity';
            $keyDate = $today->toDateString();

            if (! $this->shouldSend($type, Activity::class, (int) $activity->id, $keyDate)) {
                continue;
            }

            $msg = "Activity #{$activity->id} follow-up due | {$activity->subject} | Next: " .
                Carbon::parse($activity->next_follow_up_at)->format('Y-m-d H:i');

            $url = null; // if you later add Activity show page, set here

            if (! $dryRun) {
                // create reminder Activity attached to the same actionable model
                $this->createActivity([
                    'subject'         => "[REMINDER] Follow-up due (Activity)",
                    'type'            => 'note',
                    'body'            => $msg,
                    'activity_at'     => now(),
                    'next_follow_up_at' => null,
                    'status'          => 'open',
                    'actor_id'        => $actorId,
                    'actionable_type' => $activity->actionable_type,
                    'actionable_id'   => (int) $activity->actionable_id,
                ]);

                $this->notifyOwners($owners, new DueReminderNotification(
                    title: "Follow-up due (Activity)",
                    message: $msg,
                    url: $url,
                    meta: [
                        'reminder_type' => $type,
                        'activity_id' => (int) $activity->id,
                        'actionable_type' => $activity->actionable_type,
                        'actionable_id' => (int) $activity->actionable_id,
                        'next_follow_up_at' => Carbon::parse($activity->next_follow_up_at)->toIso8601String(),
                    ]
                ));

                $this->markSent($type, Activity::class, (int) $activity->id, $keyDate, [
                    'next_follow_up_at' => Carbon::parse($activity->next_follow_up_at)->toIso8601String(),
                ]);
            }

            $sent++;
        }

        return $sent;
    }

    private function ownerUsers(): Collection
    {
        // Spatie role based (Spec: Owner role exists)
        // If you also want fallback by permission, add ->permission('dashboard.owner')
        if (method_exists(User::class, 'role')) {
            return User::query()->role('Owner')->get();
        }

        // Fallback (should not happen in your spec setup)
        return User::query()->limit(1)->get();
    }

    private function notifyOwners(Collection $owners, DueReminderNotification $notification): void
    {
        foreach ($owners as $owner) {
            $owner->notify($notification);
        }
    }

    private function createActivity(array $data): void
    {
        // Avoid mass-assignment issues: use forceFill
        $activity = new Activity();
        $activity->forceFill($data);
        $activity->save();
    }

    private function shouldSend(string $type, string $entityType, int $entityId, string $remindOn): bool
    {
        return ! ReminderLog::query()
            ->where('type', $type)
            ->where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->whereDate('remind_on', $remindOn)
            ->exists();
    }

    private function markSent(string $type, string $entityType, int $entityId, string $remindOn, array $meta = []): void
    {
        ReminderLog::query()->create([
            'type' => $type,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'remind_on' => $remindOn,
            'sent_at' => now(),
            'meta' => $meta,
        ]);
    }
}
