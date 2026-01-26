<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Why separate "ensure" migration?
     * - Existing ERP projects may already have `invoices` table created earlier.
     * - Editing old create migrations can break existing DBs.
     * - This migration safely adds missing finance/status columns + indexes without duplicate errors.
     */
    public function up(): void
    {
        if (!Schema::hasTable('invoices')) {
            return;
        }

        Schema::table('invoices', function (Blueprint $table) {
            // status (InvoiceStatus)
            if (!Schema::hasColumn('invoices', 'status')) {
                $table->string('status', 20)->default('unpaid')->index();
            }

            // due_date (indexed)
            if (!Schema::hasColumn('invoices', 'due_date')) {
                $table->date('due_date')->nullable()->index();
            }

            // totals
            if (!Schema::hasColumn('invoices', 'total')) {
                $table->decimal('total', 12, 2)->default(0);
            }

            if (!Schema::hasColumn('invoices', 'paid_total')) {
                $table->decimal('paid_total', 12, 2)->default(0);
            }

            if (!Schema::hasColumn('invoices', 'balance')) {
                $table->decimal('balance', 12, 2)->default(0);
            }
        });

        // If due_date already existed but index was missing, add it safely.
        if (Schema::hasColumn('invoices', 'due_date') && !$this->indexExists('invoices', 'invoices_due_date_index')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->index('due_date');
            });
        }

        // If status already existed but index was missing, add it safely.
        if (Schema::hasColumn('invoices', 'status') && !$this->indexExists('invoices', 'invoices_status_index')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->index('status');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('invoices')) {
            return;
        }

        // Only rollback what THIS migration may have added
        Schema::table('invoices', function (Blueprint $table) {
            // drop indexes if we added them (safe check by name)
            if ($this->indexExists('invoices', 'invoices_due_date_index')) {
                $table->dropIndex('invoices_due_date_index');
            }

            if ($this->indexExists('invoices', 'invoices_status_index')) {
                $table->dropIndex('invoices_status_index');
            }

            // drop columns if exist (but be careful: if your old system already had them, you may NOT want to drop)
            if (Schema::hasColumn('invoices', 'balance')) {
                $table->dropColumn('balance');
            }
            if (Schema::hasColumn('invoices', 'paid_total')) {
                $table->dropColumn('paid_total');
            }
            if (Schema::hasColumn('invoices', 'total')) {
                $table->dropColumn('total');
            }
            if (Schema::hasColumn('invoices', 'due_date')) {
                $table->dropColumn('due_date');
            }
            if (Schema::hasColumn('invoices', 'status')) {
                $table->dropColumn('status');
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
