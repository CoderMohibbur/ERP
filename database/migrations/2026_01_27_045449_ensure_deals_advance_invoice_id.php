<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('deals')) {
            return;
        }

        // Add column + index (safe)
        Schema::table('deals', function (Blueprint $table) {
            if (!Schema::hasColumn('deals', 'advance_invoice_id')) {
                $table->unsignedBigInteger('advance_invoice_id')->nullable()->after('project_id');
                $table->index('advance_invoice_id', 'deals_advance_invoice_id_index');
            }
        });

        // Add FK only if invoices table exists
        if (!Schema::hasTable('invoices')) {
            return;
        }

        // FK safety: avoid duplicate constraint error
        $fkName = 'deals_advance_invoice_id_foreign';
        if (!$this->foreignKeyExists('deals', $fkName) && Schema::hasColumn('deals', 'advance_invoice_id')) {
            Schema::table('deals', function (Blueprint $table) use ($fkName) {
                $table->foreign('advance_invoice_id', $fkName)
                    ->references('id')->on('invoices')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('deals')) {
            return;
        }

        $fkName = 'deals_advance_invoice_id_foreign';

        if ($this->foreignKeyExists('deals', $fkName)) {
            Schema::table('deals', function (Blueprint $table) use ($fkName) {
                $table->dropForeign($fkName);
            });
        }

        if (Schema::hasColumn('deals', 'advance_invoice_id')) {
            Schema::table('deals', function (Blueprint $table) {
                // index name safe drop
                if ($this->indexExists('deals', 'deals_advance_invoice_id_index')) {
                    $table->dropIndex('deals_advance_invoice_id_index');
                }
                $table->dropColumn('advance_invoice_id');
            });
        }
    }

    private function foreignKeyExists(string $table, string $constraintName): bool
    {
        $dbName = DB::getDatabaseName();

        $row = DB::selectOne(
            "SELECT 1
             FROM information_schema.table_constraints
             WHERE constraint_schema = ?
               AND table_name = ?
               AND constraint_name = ?
               AND constraint_type = 'FOREIGN KEY'
             LIMIT 1",
            [$dbName, $table, $constraintName]
        );

        return (bool) $row;
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
