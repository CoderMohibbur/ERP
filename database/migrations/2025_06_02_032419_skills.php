<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->id();

            // 🏷️ Skill Name & Tag
            $table->string('name')->unique(); // e.g., Laravel, Excel, Leadership
            $table->string('slug')->unique(); // e.g., laravel, excel

            // 📊 Optional Categorization (if needed later)
            $table->string('category')->nullable(); // e.g., Technical, Soft Skill, Language

            // 📋 Description (optional for UI/help)
            $table->text('description')->nullable();

            // ✅ Status
            $table->boolean('is_active')->default(true);

            // 🧠 Audit Trail
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            // 🗑️ Soft Delete + timestamps
            $table->softDeletes();
            $table->timestamps();

            // ⚡ Indexes
            $table->index(['name', 'slug']);
            $table->index(['category']);
            $table->index(['is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};
