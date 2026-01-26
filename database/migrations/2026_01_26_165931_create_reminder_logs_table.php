<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('reminder_logs')) {
            return;
        }

        Schema::create('reminder_logs', function (Blueprint $table) {
            $table->id();

            $table->string('type', 60)->index(); // e.g. renewal_due_7, invoice_overdue
            $table->string('entity_type');
            $table->unsignedBigInteger('entity_id');
            $table->date('remind_on')->index(); // per-day / per-threshold key
            $table->timestamp('sent_at')->nullable()->index();
            $table->json('meta')->nullable();

            // preference: softDeletes before timestamps
            $table->softDeletes();
            $table->timestamps();

            $table->index(['entity_type', 'entity_id'], 'reminder_logs_entity_idx');
            $table->unique(['type', 'entity_type', 'entity_id', 'remind_on'], 'reminder_logs_unique');
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('reminder_logs')) {
            Schema::drop('reminder_logs');
        }
    }
};
