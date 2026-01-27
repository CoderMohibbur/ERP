<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ProjectBoardController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        // Read permission guard (Spatie Permission compatible)
        $user = $request->user();
        if ($user && method_exists($user, 'can')) {
            $allowed = $user->can('project.view')
                || $user->can('project.show')
                || $user->can('project.index')
                || $user->can('project.viewAny');

            if (!$allowed && method_exists($user, 'hasRole')) {
                $allowed = $user->hasRole('Owner');
            }

            abort_unless($allowed, 403);
        }

        $q = trim((string) $request->query('q', ''));

        $tasksQuery = Task::query()
            ->where('project_id', $project->id)
            ->with(['assignee:id,name']);

        if ($q !== '') {
            $tasksQuery->where(function ($w) use ($q) {
                if (Schema::hasColumn('tasks', 'title')) {
                    $w->orWhere('title', 'like', "%{$q}%");
                }
                if (Schema::hasColumn('tasks', 'name')) {
                    $w->orWhere('name', 'like', "%{$q}%");
                }
                if (Schema::hasColumn('tasks', 'note')) {
                    $w->orWhere('note', 'like', "%{$q}%");
                }
            });
        }

        if (Schema::hasColumn('tasks', 'erp_priority')) {
            $tasksQuery->orderBy('erp_priority')->orderBy('id');
        } else {
            $tasksQuery->orderBy('id');
        }

        $tasks = $tasksQuery->get();

        $usesErp = Schema::hasColumn('tasks', 'erp_status');

        $groups = [
            'backlog' => collect(),
            'doing'   => collect(), // spec column "Doing" (we'll also show blocked here)
            'review'  => collect(),
            'done'    => collect(),
        ];

        foreach ($tasks as $task) {
            $raw = $usesErp
                ? (string) ($task->erp_status ?? 'backlog')
                : (string) ($task->status ?? 'pending');

            $normalized = $usesErp ? $raw : $this->legacyToBoardStatus($raw);

            // spec says 4 columns; show blocked inside Doing but highlight
            $isBlocked = ($normalized === 'blocked');
            $task->setAttribute('is_blocked', $isBlocked);

            if ($isBlocked) {
                $groups['doing']->push($task);
                continue;
            }

            if (isset($groups[$normalized])) {
                $groups[$normalized]->push($task);
            } else {
                $groups['backlog']->push($task);
            }
        }

        $counts = [];
        foreach ($groups as $k => $col) {
            $counts[$k] = $col->count();
        }

        return view('projects.board', [
            'project'       => $project,
            'groups'        => $groups,
            'counts'        => $counts,
            'q'             => $q,
            'usesErpStatus' => $usesErp,
        ]);
    }

    private function legacyToBoardStatus(string $legacy): string
    {
        return match ($legacy) {
            'pending'      => 'backlog',
            'in_progress'  => 'doing',
            'completed'    => 'done',
            'blocked'      => 'blocked',
            default        => 'backlog',
        };
    }
}
