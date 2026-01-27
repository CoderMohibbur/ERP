<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\TimeLogService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TimeLogController extends Controller
{
    public function start(Request $request, Task $task, TimeLogService $service)
    {
        abort_unless(auth()->check(), 401);
        abort_unless(auth()->user()->can('timelog.create') || auth()->user()->can('timelog.*'), 403);

        $request->validate([
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $service->start($task, auth()->user(), $request->string('note')->toString());
            return back()->with('success', 'Timer started.');
        } catch (ValidationException $e) {
            $msg = data_get($e->errors(), 'timer.0') ?? 'Unable to start timer.';
            return back()->with('error', $msg);
        }
    }

    public function stop(Request $request, Task $task, TimeLogService $service)
    {
        abort_unless(auth()->check(), 401);
        abort_unless(auth()->user()->can('timelog.update') || auth()->user()->can('timelog.*'), 403);

        $request->validate([
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $log = $service->stopRunning(auth()->user(), $task, $request->string('note')->toString());

        if (!$log) {
            return back()->with('error', 'No running timer found for this task.');
        }

        return back()->with('success', 'Timer stopped.');
    }
}
