<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            if (!Schema::hasColumn('leads', 'converted_at')) {
                $table->timestamp('converted_at')->nullable()->index()->after('converted_client_id');
            }

            // Ensure status indexed (if not already)
            // (We can't reliably check index existence without DB-specific logic; safe to leave if already indexed)
            if (Schema::hasColumn('leads', 'status')) {
                // no-op
            }
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            if (Schema::hasColumn('leads', 'converted_at')) {
                $table->dropColumn('converted_at');
            }
        });
    }
};
