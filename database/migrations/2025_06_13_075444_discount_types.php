<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('discount_types', function (Blueprint $table) {
            $table->id();

            // 🏷️ Type Info
            $table->string('name')->unique();              // E.g., Seasonal, Loyalty, Clearance
            $table->string('slug')->unique();              // e.g., seasonal, loyalty
            $table->string('color')->nullable();           // UI tag color (e.g., green, red)
            $table->text('description')->nullable();

            // ⚙️ Meta
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);

            // 👤 Audit
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // 🔍 Indexing
            $table->index(['slug']);
            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discount_types');
    }
};
