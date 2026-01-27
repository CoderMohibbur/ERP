<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskStatusUpdateRequest;
use App\Models\Task;
use Illuminate\Support\Facades\Schema;

class TaskStatusController extends Controller
{
    public function __invoke(TaskStatusUpdateRequest $request, Task $task)
    {
        $status = $request->string('status')->toString();
        $blockedReason = $request->input('blocked_reason');

        $update = [];

        // Prefer spec-compatible erp_status if present, else map to legacy enum
        if (Schema::hasColumn('tasks', 'erp_status')) {
            $update['erp_status'] = $status;
        } else {
            $update['status'] = $this->boardToLegacyStatus($status);
        }

        if (Schema::hasColumn('tasks', 'blocked_reason')) {
            $update['blocked_reason'] = ($status === 'blocked') ? (string) $blockedReason : null;
        }

        if (Schema::hasColumn('tasks', 'started_at')) {
            if ($status === 'doing' && empty($task->started_at)) {
                $update['started_at'] = now();
            }
        }

        if (Schema::hasColumn('tasks', 'completed_at')) {
            if ($status === 'done' && empty($task->completed_at)) {
                $update['completed_at'] = now();
            }
        }

        // Optional legacy end_date support
        if (Schema::hasColumn('tasks', 'end_date')) {
            if ($status === 'done' && empty($task->end_date)) {
                $update['end_date'] = now()->toDateString();
            }
        }

        // Avoid fillable issues safely (validated input)
        $task->forceFill($update)->save();

        return back()->with('success', 'Task status updated.');
    }

    private function boardToLegacyStatus(string $status): string
    {
        return match ($status) {
            'backlog' => 'pending',
            'doing'   => 'in_progress',
            'review'  => 'in_progress', // legacy schema has no "review"
            'done'    => 'completed',
            'blocked' => 'blocked',
            default   => 'pending',
        };
    }
}
