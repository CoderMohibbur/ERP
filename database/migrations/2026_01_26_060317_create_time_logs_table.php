<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('time_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('task_id')
                ->constrained('tasks')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->dateTime('started_at')->index();
            $table->dateTime('ended_at')->nullable()->index();

            $table->unsignedBigInteger('seconds')->default(0)->index();

            $table->text('note')->nullable();
            $table->string('source', 20)->default('manual');

            $table->softDeletes();
            $table->timestamps();

            // fast lookup: running timer (ended_at is null) + user reports
            $table->index(['user_id', 'ended_at']);
            $table->index(['task_id', 'started_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('time_logs');
    }
};
