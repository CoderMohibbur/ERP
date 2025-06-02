<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_shifts', function (Blueprint $table) {
            $table->id();

            // 🔗 Relationships
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('shift_id')->constrained('shifts')->onDelete('restrict');

            // 📅 Assignment Date
            $table->date('shift_date'); // শিফট যেদিনের জন্য নির্ধারিত

            // 🕒 Custom override support
            $table->time('start_time_override')->nullable(); // যদি default সময় override করতে হয়
            $table->time('end_time_override')->nullable();
            $table->boolean('is_manual_override')->default(false);

            // 🔮 Future-Proof Additions
            $table->enum('status', ['assigned', 'completed', 'cancelled'])->default('assigned');
            $table->text('remarks')->nullable(); // remarks or exception context
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('shift_type_cache')->nullable(); // for reporting/indexing optimization

            // 🔐 Audit
            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();

            // 🗑️ Safe deletion + timestamps
            $table->softDeletes();
            $table->timestamps();

            // ⚡ Indexes
            $table->unique(['employee_id', 'shift_date']); // এক কর্মী এক দিনে একটি শিফটেই থাকতে পারে
            $table->index(['shift_id']);
            $table->index(['assigned_by']);
            $table->index(['verified_by']);
            $table->index(['shift_date']);
            $table->index(['status']);
            $table->index(['shift_type_cache']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_shifts');
    }
};
