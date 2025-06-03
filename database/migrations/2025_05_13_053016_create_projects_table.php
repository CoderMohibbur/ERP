<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            // 🧾 Project Info
            $table->string('title');
            $table->text('description')->nullable();

            // 📅 Dates
            $table->date('deadline')->nullable();
            $table->date('started_at')->nullable();
            $table->date('completed_at')->nullable();

            // 💰 Budget Tracking
            $table->decimal('budget', 14, 2)->nullable();
            $table->decimal('actual_cost', 14, 2)->nullable();

            // 🏷️ Identity & Status
            $table->string('project_code')->unique(); // for internal/external ref
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');

            // 🛡️ Audit
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();


            // 🗑️ Soft delete + timestamps
            $table->softDeletes();
            $table->timestamps();

            // ⚡ Indexing
            $table->index(['status', 'priority']);
            $table->index(['client_id']);
            $table->index(['deadline']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
