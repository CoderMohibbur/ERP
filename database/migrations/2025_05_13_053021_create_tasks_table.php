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

            // 🔗 Relationships
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('parent_task_id')->nullable()->constrained('tasks')->nullOnDelete();
            $table->foreignId('dependency_task_id')->nullable()->constrained('tasks')->nullOnDelete();

            // 🧾 Task Info
            $table->string('title');
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'blocked'])->default('pending');
            $table->integer('progress')->default(0)->comment('Progress in %');

            // 🕒 Timeline
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->date('end_date')->nullable();

            // ⏱️ Hours
            $table->decimal('estimated_hours', 5, 2)->nullable();
            $table->decimal('actual_hours', 5, 2)->nullable();

            // 📋 Notes
            $table->text('note')->nullable();

            // 🔐 Audit (Jetstream-compatible)
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            // 🗑️ Soft Delete & timestamps
            $table->softDeletes();
            $table->timestamps();

            // ⚡ Indexing
            $table->index(['project_id', 'assigned_to']);
            $table->index(['status', 'priority']);
            $table->index(['start_date', 'due_date', 'end_date']);
            $table->index(['parent_task_id']);
            $table->index(['dependency_task_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
