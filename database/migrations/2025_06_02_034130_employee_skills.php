<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_skills', function (Blueprint $table) {
            $table->id();

            // 🔗 Core Relationships
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('skill_id')->constrained('skills')->onDelete('cascade');

            // 📊 Optional Extra
            $table->tinyInteger('proficiency_level')->nullable(); // 1–10 or enum (future extendable)
            $table->text('notes')->nullable(); // Optional comments about the skill

            // 🔐 Audit
            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();

            // 🗑️ Soft Delete & timestamps
            $table->softDeletes();
            $table->timestamps();

            // ⚡ Indexing
            $table->unique(['employee_id', 'skill_id']); // Prevent duplicate assignment
            $table->index(['proficiency_level']);
            $table->index(['assigned_by']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_skills');
    }
};
