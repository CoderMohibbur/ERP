<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class ProjectBoardController extends Controller
{
    public function __invoke(Project $project, Request $request)
    {
        // tasks relation থাকলে সেটাই ইউজ করবে, না থাকলে project_id দিয়ে fallback করবে
        $tasksQuery = method_exists($project, 'tasks')
            ? $project->tasks()
            : Task::query()->where('project_id', $project->id);

        $tasks = $tasksQuery->get();

        // Optional client-side-like filter (DB কলাম নাম অজানা হলেও safe)
        $q = trim((string) $request->get('q', ''));
        if ($q !== '') {
            $tasks = $tasks->filter(function ($t) use ($q) {
                $title = (string) ($t->title ?? $t->name ?? $t->task_name ?? '');
                return str_contains(mb_strtolower($title), mb_strtolower($q))
                    || str_contains((string) $t->id, $q);
            })->values();
        }

        $statuses = ['backlog', 'doing', 'review', 'done', 'blocked'];

        $grouped = [];
        foreach ($statuses as $s) {
            $grouped[$s] = $tasks->filter(fn ($t) => ($t->status ?? 'backlog') === $s)->values();
        }

        return view('projects.board', [
            'project' => $project,
            'grouped' => $grouped,
            'q' => $q,
        ]);
    }
}
