<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendance_settings', function (Blueprint $table) {
            $table->id();

            // সময় ও নিয়মাবলী
            $table->time('office_start');             // অফিস শুরু সময়
            $table->time('start_time')->nullable();   // কার্যকর শুরু সময়
            $table->time('end_time')->nullable();     // কার্যকর শেষ সময়
            $table->integer('grace_minutes');         // ছাড়ের সময়
            $table->integer('half_day_after')->nullable(); // কত মিনিট পরে হাফডে হবে

            // অন্যান্য সেটিংস
            $table->integer('working_days');          // মাসে মোট কার্যদিবস
            $table->json('weekend_days')->nullable(); // সাপ্তাহিক ছুটি (array)
            $table->string('note')->nullable();       // নোট

            // টাইমস্ট্যাম্প
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_settings');
    }
};
