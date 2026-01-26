<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Why separate "ensure" migration?
     * - service_renewals may already exist in the ERP.
     * - We safely add invoice_id FK+index only if missing, without duplicate errors.
     */
    public function up(): void
    {
        if (!Schema::hasTable('service_renewals') || !Schema::hasTable('invoices')) {
            return;
        }

        Schema::table('service_renewals', function (Blueprint $table) {
            if (!Schema::hasColumn('service_renewals', 'invoice_id')) {
                $table->foreignId('invoice_id')
                    ->nullable()
                    ->constrained('invoices')
                    ->nullOnDelete();
            }
        });

        // If column exists but index missing, add index safely
        if (Schema::hasColumn('service_renewals', 'invoice_id') && !$this->indexExists('service_renewals', 'service_renewals_invoice_id_index')) {
            Schema::table('service_renewals', function (Blueprint $table) {
                $table->index('invoice_id');
            });
        }

        // If column exists but FK missing, add FK safely
        if (
            Schema::hasColumn('service_renewals', 'invoice_id')
            && !$this->foreignKeyExists('service_renewals', 'service_renewals_invoice_id_foreign')
        ) {
            Schema::table('service_renewals', function (Blueprint $table) {
                $table->foreign('invoice_id', 'service_renewals_invoice_id_foreign')
                    ->references('id')
                    ->on('invoices')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('service_renewals')) {
            return;
        }

        Schema::table('service_renewals', function (Blueprint $table) {
            if (Schema::hasColumn('service_renewals', 'invoice_id')) {
                // drop FK first
                if ($this->foreignKeyExists('service_renewals', 'service_renewals_invoice_id_foreign')) {
                    $table->dropForeign('service_renewals_invoice_id_foreign');
                }

                // drop index if exists
                if ($this->indexExists('service_renewals', 'service_renewals_invoice_id_index')) {
                    $table->dropIndex('service_renewals_invoice_id_index');
                }

                $table->dropColumn('invoice_id');
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

    private function foreignKeyExists(string $table, string $fkName): bool
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
            [$dbName, $table, $fkName]
        );

        return (bool) $row;
    }
};
