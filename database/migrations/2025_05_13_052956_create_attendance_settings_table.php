<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_settings', function (Blueprint $table) {
            $table->id();

            // 🕒 Office Timing Rules
            $table->time('office_start');               // অফিস শুরুর সময়
            $table->time('start_time')->nullable();     // কার্যকর শুরুর সময় (flexible)
            $table->time('end_time')->nullable();       // কার্যকর শেষ সময়
            $table->integer('grace_minutes')->default(10); // ছাড়ের সময় (late tolerated)
            $table->integer('half_day_after')->nullable(); // কত মিনিট পর হাফডে হবে
            $table->integer('working_days')->default(26);  // মাসে মোট কার্যদিবস
            $table->json('weekend_days')->nullable();      // [ "Friday", "Saturday" ]

            // 🌍 Remote Work Support
            $table->string('timezone', 100)->default('Asia/Dhaka');
            $table->boolean('allow_remote_attendance')->default(false);

            // 📝 Notes
            $table->string('note')->nullable();

            // 🔐 Audit (Jetstream-compatible)
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            // 🗑️ Safe Delete + Timestamps
            $table->softDeletes();
            $table->timestamps();

            // ⚡ Indexes
            $table->index('timezone');
            $table->index('allow_remote_attendance');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_settings');
    }
};
