<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('task_templates', function (Blueprint $table) {
            $table->id();

            $table->string('code', 50)->unique(); // wp, laravel, pos, hosting, general
            $table->string('name', 120);
            $table->string('description', 255)->nullable();

            $table->boolean('is_active')->default(true)->index();
            $table->unsignedSmallInteger('sort_order')->default(0)->index();

            $table->softDeletes();
            $table->timestamps();

            $table->index(['code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_templates');
    }
};
