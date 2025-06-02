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
        Schema::create('designations', function (Blueprint $table) {
            $table->id();

            // 🔰 Core Info
            $table->string('name')->unique();
            $table->string('code', 10)->unique(); // e.g. SE, PM, TL
            $table->text('description')->nullable();

            // 🧱 Hierarchy
            $table->tinyInteger('level')->nullable(); // e.g. 1 = CEO → 5 = Staff

            // 🔐 Audit (Jetstream compatible)
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            // 🗑️ Soft Delete + timestamps
            $table->softDeletes();
            $table->timestamps();

            // ⚡ Indexes
            $table->index(['code']);
            $table->index(['level']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('designations');
    }
};
