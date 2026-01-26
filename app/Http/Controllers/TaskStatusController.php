<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskStatusUpdateRequest;
use App\Models\Task;
use Illuminate\Support\Facades\Schema;

class TaskStatusController extends Controller
{
    public function __invoke(TaskStatusUpdateRequest $request, Task $task)
    {
        $validated = $request->validated();
        $newStatus = $validated['status'];
        $oldStatus = $task->status ?? null;

        // status column না থাকলে board কাজই সম্ভব না—তবুও graceful
        if (!Schema::hasColumn('tasks', 'status')) {
            return back()->with('error', 'tasks টেবিলে status কলাম নেই। আগে status কলাম ensure করুন।');
        }

        $task->status = $newStatus;

        // Optional columns safely set (কলাম থাকলেই)
        if (Schema::hasColumn('tasks', 'blocked_reason') && array_key_exists('blocked_reason', $validated)) {
            $task->blocked_reason = $validated['blocked_reason'];
        }

        $now = now();

        if (Schema::hasColumn('tasks', 'started_at')) {
            if ($newStatus === 'doing' && empty($task->started_at)) {
                $task->started_at = $now;
            }
        }

        if (Schema::hasColumn('tasks', 'completed_at')) {
            if ($newStatus === 'done') {
                $task->completed_at = $now;
            } elseif ($oldStatus === 'done' && $newStatus !== 'done') {
                // done থেকে অন্য status এ গেলে completed_at clear
                $task->completed_at = null;
            }
        }

        $task->save();

        return back()->with('success', 'Task status updated.');
    }
}
