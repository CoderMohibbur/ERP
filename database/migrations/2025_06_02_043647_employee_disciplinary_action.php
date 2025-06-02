<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_disciplinary_actions', function (Blueprint $table) {
            $table->id();

            // 🔗 Relationships
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');

            // 📅 Core Dates
            $table->date('incident_date'); // অপরাধ সংঘটিত হওয়ার তারিখ
            $table->date('action_date');   // অ্যাকশন নেওয়ার তারিখ

            // 📝 Description
            $table->string('violation_type'); // e.g. "absenteeism", "misconduct", "policy breach"
            $table->text('description')->nullable(); // বিস্তারিত

            // 📌 Action Taken
            $table->enum('action_taken', ['verbal_warning', 'written_warning', 'suspension', 'termination', 'other']);
            $table->integer('severity_level')->nullable(); // e.g. 1 (minor) – 5 (major)

            // 📂 Attachments (Optional)
            $table->string('attachment_path')->nullable(); // e.g. scanned warning letter

            // 🔐 Audit trail
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();

            // 🗑️ Soft delete + timestamps
            $table->softDeletes();
            $table->timestamps();

            // ⚡ Indexes
            $table->index(['employee_id']);
            $table->index(['violation_type']);
            $table->index(['action_taken']);
            $table->index(['incident_date']);
            $table->index(['severity_level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_disciplinary_actions');
    }
};
