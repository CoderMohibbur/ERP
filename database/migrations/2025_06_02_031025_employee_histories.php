<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_histories', function (Blueprint $table) {
            $table->id();

            // 🔗 Relationships
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('designation_id')->constrained('designations')->onDelete('restrict');
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');

            // 📅 Time Tracking
            $table->date('effective_from');
            $table->date('effective_to')->nullable(); // NULL = current role

            // 📝 Optional Info
            $table->enum('change_type', ['promotion', 'transfer', 'reinstatement', 'demotion', 'joining'])->default('joining');
            $table->text('remarks')->nullable(); // Optional admin note

            // 🔐 Audit
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();

            // 🗑️ Soft delete + timestamps
            $table->softDeletes();
            $table->timestamps();

            // ⚡ Indexes
            $table->index(['employee_id', 'designation_id']);
            $table->index(['department_id']);
            $table->index('effective_from');
            $table->index('change_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_histories');
    }
};
