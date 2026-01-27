<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tasks')) {
            return;
        }

        // Add missing columns safely
        Schema::table('tasks', function (Blueprint $table) {
            if (!Schema::hasColumn('tasks', 'erp_status')) {
                $table->string('erp_status', 20)->default('backlog');
            }
            if (!Schema::hasColumn('tasks', 'erp_priority')) {
                $table->unsignedTinyInteger('erp_priority')->default(3);
            }
            if (!Schema::hasColumn('tasks', 'blocked_reason')) {
                $table->text('blocked_reason')->nullable();
            }
            if (!Schema::hasColumn('tasks', 'started_at')) {
                $table->dateTime('started_at')->nullable();
            }
            if (!Schema::hasColumn('tasks', 'completed_at')) {
                $table->dateTime('completed_at')->nullable();
            }
            if (!Schema::hasColumn('tasks', 'estimated_minutes')) {
                $table->unsignedInteger('estimated_minutes')->nullable();
            }
        });

        // Add indexes (idempotent)
        if (Schema::hasColumn('tasks', 'erp_status') && !$this->indexExists('tasks', 'tasks_board_status_idx')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->index('erp_status', 'tasks_board_status_idx');
            });
        }

        if (Schema::hasColumn('tasks', 'erp_priority') && !$this->indexExists('tasks', 'tasks_board_priority_idx')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->index('erp_priority', 'tasks_board_priority_idx');
            });
        }

        if (
            Schema::hasColumn('tasks', 'project_id') &&
            Schema::hasColumn('tasks', 'erp_status') &&
            Schema::hasColumn('tasks', 'erp_priority') &&
            !$this->indexExists('tasks', 'tasks_board_project_status_priority_idx')
        ) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->index(['project_id', 'erp_status', 'erp_priority'], 'tasks_board_project_status_priority_idx');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('tasks')) {
            return;
        }

        // Drop indexes first (if exist)
        Schema::table('tasks', function (Blueprint $table) {
            if ($this->indexExists('tasks', 'tasks_board_project_status_priority_idx')) {
                $table->dropIndex('tasks_board_project_status_priority_idx');
            }
            if ($this->indexExists('tasks', 'tasks_board_status_idx')) {
                $table->dropIndex('tasks_board_status_idx');
            }
            if ($this->indexExists('tasks', 'tasks_board_priority_idx')) {
                $table->dropIndex('tasks_board_priority_idx');
            }
        });

        // Drop columns (rollback will remove board columns)
        Schema::table('tasks', function (Blueprint $table) {
            if (Schema::hasColumn('tasks', 'estimated_minutes')) {
                $table->dropColumn('estimated_minutes');
            }
            if (Schema::hasColumn('tasks', 'completed_at')) {
                $table->dropColumn('completed_at');
            }
            if (Schema::hasColumn('tasks', 'started_at')) {
                $table->dropColumn('started_at');
            }
            if (Schema::hasColumn('tasks', 'blocked_reason')) {
                $table->dropColumn('blocked_reason');
            }
            if (Schema::hasColumn('tasks', 'erp_priority')) {
                $table->dropColumn('erp_priority');
            }
            if (Schema::hasColumn('tasks', 'erp_status')) {
                $table->dropColumn('erp_status');
            }
        });
    }

    private function indexExists(string $table, string $indexName): bool
    {
        $dbName = DB::getDatabaseName();

        $row = DB::selectOne(
            "SELECT 1
             FROM information_schema.statistics
             WHERE table_schema = ?
               AND table_name = ?
               AND index_name = ?
             LIMIT 1",
            [$dbName, $table, $indexName]
        );

        return (bool) $row;
    }
};
