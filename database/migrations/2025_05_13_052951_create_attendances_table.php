<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            // 🔗 Employee Relation
            $table->unsignedBigInteger('employee_id');
            


            // 📅 Attendance Info
            $table->date('date');
            $table->enum('status', ['present', 'late', 'absent', 'leave'])->default('present');
            $table->string('note')->nullable();

            // 🕒 Timing Details
            $table->time('in_time')->nullable();
            $table->time('out_time')->nullable();
            $table->decimal('worked_hours', 5, 2)->nullable(); // e.g., 7.75 hours

            // ⏱ Deviation
            $table->integer('late_by_minutes')->nullable();
            $table->integer('early_leave_minutes')->nullable();

            // 🌍 Source & Device Info
            $table->string('location')->nullable(); // e.g., "Dhaka Office"
            $table->enum('device_type', ['web', 'mobile', 'kiosk'])->nullable();

            // 🔐 Optional Audit
            $table->unsignedBigInteger('verified_by')->nullable();

            // 🗑️ Soft Deletes + Timestamps
            $table->softDeletes();
            $table->timestamps();

            // ⚡ Indexing
            $table->index(['employee_id', 'date']);
            $table->index(['status']);
            $table->index(['device_type']);
            $table->index(['verified_by']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
