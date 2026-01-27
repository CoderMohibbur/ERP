<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // If app/Jetstream/Laravel already created notifications table, do nothing.
        if (Schema::hasTable('notifications')) {
            return;
        }

        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');

            // notifiable_type + notifiable_id + index
            $table->morphs('notifiable');

            $table->text('data');
            $table->timestamp('read_at')->nullable()->index();

            // Laravel core notifications table typically uses only timestamps
            $table->timestamps();
        });
    }

    public function down(): void
    {
        /**
         * ROLLBACK-SAFE (P0):
         * This is an "if missing" migration. The notifications table may have existed
         * before this migration (Laravel core / earlier migrations).
         *
         * ✅ Therefore, DO NOT drop the table on rollback.
         */
        return;
    }
};
