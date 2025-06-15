<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_categories', function (Blueprint $table) {
            $table->id();

            // 🏷️ Basic Info
            $table->string('name')->index();               // e.g., Accessories, Electronics
            $table->string('slug')->unique();              // SEO / programmatic access (e.g., accessories)

            // 🧬 Hierarchy Support (for nested categories if needed)
            $table->unsignedBigInteger('parent_id')->nullable(); // null = top-level
            $table->foreign('parent_id')->references('id')->on('item_categories')->nullOnDelete();

            // 📄 Optional Details
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);

            // 🔐 Audit Trail
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            // 🗑️ Soft Delete + Timestamps
            $table->softDeletes();
            $table->timestamps();

            // ⚡ Indexing for fast lookup
            $table->index(['is_active', 'parent_id']);
            $table->index(['created_by', 'updated_by']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_categories');
    }
};
