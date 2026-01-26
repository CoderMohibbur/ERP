<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActivityStoreRequest;
use App\Http\Requests\ActivityUpdateRequest;
use App\Models\Activity;
use App\Models\Lead;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    public function store(ActivityStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $actionableClass = Relation::getMorphedModel($data['actionable_type']) ?? $data['actionable_type'];
        abort_unless(class_exists($actionableClass), 422, 'Invalid actionable type.');

        $actionable = $actionableClass::query()->findOrFail((int) $data['actionable_id']);

        DB::transaction(function () use ($request, $data, $actionable, $actionableClass) {
            $activity = new Activity();
            $activity->subject = $data['subject'];
            $activity->type = $data['type'];
            $activity->body = $data['body'] ?? null;

            $activity->activity_at = !empty($data['activity_at'])
                ? Carbon::parse($data['activity_at'])
                : now();

            $activity->next_follow_up_at = !empty($data['next_follow_up_at'])
                ? Carbon::parse($data['next_follow_up_at'])
                : null;

            $activity->status = Activity::STATUS_OPEN;
            $activity->actor_id = $request->user()->id;

            $activity->actionable()->associate($actionable);
            $activity->save();

            // Lead follow-up workflow sync (lead.next_follow_up_at)
            if ($actionable instanceof Lead) {
                $actionable->last_contacted_at = $activity->activity_at;
                $this->syncLeadNextFollowUp($actionable);
            }
        });

        return back()->with('success', 'Activity saved successfully.');
    }

    public function update(ActivityUpdateRequest $request, Activity $activity): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $activity) {
            if (array_key_exists('subject', $data)) {
                $activity->subject = $data['subject'];
            }
            if (array_key_exists('type', $data)) {
                $activity->type = $data['type'];
            }
            if (array_key_exists('body', $data)) {
                $activity->body = $data['body'];
            }
            if (array_key_exists('activity_at', $data)) {
                $activity->activity_at = !empty($data['activity_at']) ? Carbon::parse($data['activity_at']) : $activity->activity_at;
            }
            if (array_key_exists('next_follow_up_at', $data)) {
                $activity->next_follow_up_at = !empty($data['next_follow_up_at']) ? Carbon::parse($data['next_follow_up_at']) : null;
            }
            if (array_key_exists('status', $data)) {
                $activity->status = $data['status'];
            }

            $activity->save();

            $actionable = $activity->actionable;

            // Lead follow-up workflow sync (lead.next_follow_up_at)
            if ($actionable instanceof Lead) {
                $this->syncLeadNextFollowUp($actionable);
            }
        });

        return back()->with('success', 'Activity updated successfully.');
    }

    public function destroy(Activity $activity): RedirectResponse
    {
        DB::transaction(function () use ($activity) {
            $actionable = $activity->actionable;

            $activity->delete();

            // Lead follow-up workflow sync (lead.next_follow_up_at)
            if ($actionable instanceof Lead) {
                $this->syncLeadNextFollowUp($actionable);
            }
        });

        return back()->with('success', 'Activity deleted successfully.');
    }

    private function syncLeadNextFollowUp(Lead $lead): void
    {
        $next = Activity::query()
            ->where('actionable_type', Lead::class)
            ->where('actionable_id', $lead->id)
            ->where('status', Activity::STATUS_OPEN)
            ->whereNotNull('next_follow_up_at')
            ->orderBy('next_follow_up_at')
            ->value('next_follow_up_at');

        $lead->next_follow_up_at = $next;
        $lead->save();
    }
}
