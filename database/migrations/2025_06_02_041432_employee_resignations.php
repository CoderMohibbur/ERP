<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_resignations', function (Blueprint $table) {
            $table->id();

            // 🔗 Relationship
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');

            // 📅 Dates
            $table->date('resignation_date'); // Employee's resignation submission date
            $table->date('effective_date'); // Last working day

            // 📝 Reason
            $table->string('reason')->nullable(); // Short reason/title
            $table->text('details')->nullable(); // Full reason text (optional)

            // 📌 Status Tracking
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();

            // 🔐 Audit
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            // 🗑️ Soft Deletes + timestamps (included if needed)
            $table->softDeletes();
            $table->timestamps();

            // ⚡ Indexes
            $table->index(['employee_id']);
            $table->index(['status']);
            $table->index(['resignation_date']);
            $table->index(['effective_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_resignations');
    }
};
