<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('task_template_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('task_template_id')
                ->constrained('task_templates')
                ->cascadeOnDelete();

            $table->string('title', 190);
            $table->text('description')->nullable();

            // TaskStatus: backlog, doing, review, done, blocked (we default backlog for templates)
            $table->string('default_status', 20)->default('backlog')->index();

            $table->unsignedSmallInteger('sort_order')->default(0)->index();
            $table->unsignedInteger('estimate_minutes')->nullable();
            $table->string('role_hint', 50)->nullable(); // WPLead/LaravelLead/Owner/etc

            $table->softDeletes();
            $table->timestamps();

            $table->unique(['task_template_id', 'title'], 'tti_template_title_unique');
            $table->index(['task_template_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_template_items');
    }
};
