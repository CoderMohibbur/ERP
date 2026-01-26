<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            // 🧾 Task Info (OLD - kept)
            $table->string('title');

            // OLD enums kept (do not remove)
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'blocked'])->default('pending');

            $table->integer('progress')->default(0)->comment('Progress in %');

            // 🕒 Timeline (OLD - kept)
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->date('end_date')->nullable();

            // ⏱️ Hours (OLD - kept)
            $table->decimal('estimated_hours', 5, 2)->nullable();
            $table->decimal('actual_hours', 5, 2)->nullable();

            // 📋 Notes (OLD - kept)
            $table->text('note')->nullable();

            // 🔐 Audit / Relations (OLD columns kept)
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->unsignedBigInteger('parent_task_id')->nullable();
            $table->unsignedBigInteger('dependency_task_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            /**
             * ✅ NEW (Minimum ERP compatible) - without removing old fields
             * TaskStatus: backlog/doing/review/done/blocked (we keep old status enum, add erp_status)
             */
            $table->string('erp_status', 20)->default('backlog'); // indexed later
            $table->unsignedTinyInteger('erp_priority')->default(3); // indexed later

            // Spec-like timestamps fields (keep old date fields too)
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();

            $table->unsignedInteger('estimated_minutes')->nullable();
            $table->text('blocked_reason')->nullable();

            // 🗑️ Soft Delete & timestamps (rule: softDeletes before timestamps)
            $table->softDeletes();
            $table->timestamps();

            // ⚡ Indexing (OLD - kept)
            $table->index(['project_id', 'assigned_to']);
            $table->index(['status', 'priority']);
            $table->index(['start_date', 'due_date', 'end_date']);
            $table->index(['parent_task_id']);
            $table->index(['dependency_task_id']);

            // ✅ NEW Indexing for ERP
            $table->index(['erp_status', 'erp_priority'], 'tasks_erp_status_priority_idx');
            $table->index(['due_date'], 'tasks_due_date_idx'); // single-column due filter fast

            // ✅ Foreign Keys (professional, no missing)
            $table->foreign('project_id')->references('id')->on('projects')->cascadeOnDelete();

            $table->foreign('assigned_to')->references('id')->on('users')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();

            // self relations (optional)
            $table->foreign('parent_task_id')->references('id')->on('tasks')->nullOnDelete();
            $table->foreign('dependency_task_id')->references('id')->on('tasks')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
