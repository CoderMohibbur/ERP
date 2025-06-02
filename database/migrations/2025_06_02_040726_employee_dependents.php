<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_dependents', function (Blueprint $table) {
            $table->id();

            // 🔗 Employee Link
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');

            // 👨‍👩‍👧‍👦 Dependent Info
            $table->string('name');
            $table->enum('relation', ['spouse', 'child', 'father', 'mother', 'sibling', 'other']);
            $table->date('dob')->nullable();
            $table->string('phone')->nullable();
            $table->string('nid_number')->nullable()->unique();
            $table->boolean('is_emergency_contact')->default(false);

            // 📋 Meta
            $table->text('notes')->nullable();

            // 🔐 Audit
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            // 🕒 Timestamps only (no soft delete unless required)
            $table->timestamps();

            // ⚡ Indexes
            $table->index(['employee_id']);
            $table->index(['relation']);
            $table->index(['is_emergency_contact']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_dependents');
    }
};
