<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();

            // 🏷️ Shift Identity
            $table->string('name')->unique(); // e.g., Morning Shift
            $table->string('slug')->unique(); // e.g., morning-shift
            $table->string('code', 20)->unique(); // e.g., M1, N2

            // 🕒 Shift Timing
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('crosses_midnight')->default(false); // for night shifts

            // 📋 Meta Info
            $table->string('type')->nullable(); // fixed, flexible, split
            $table->string('color')->nullable(); // optional for UI label (badge)
            $table->text('notes')->nullable(); // remarks or policy references

            // 🔁 Advanced Optional
            $table->json('week_days')->nullable(); // e.g., ["sun", "mon", "tue"] – for rotation control
            $table->boolean('is_active')->default(true); // deactivate without deleting

            // 🔐 Audit Trail
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            // 🗑️ Soft delete + timestamps
            $table->softDeletes();
            $table->timestamps();

            // ⚡ Indexes
            $table->index(['start_time', 'end_time']);
            $table->index(['slug']);
            $table->index(['type']);
            $table->index(['crosses_midnight']);
            $table->index(['is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
