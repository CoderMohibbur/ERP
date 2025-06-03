<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invoice_item_fields', function (Blueprint $table) {
            $table->id();

            // 🔗 Relation
            $table->unsignedBigInteger('invoice_item_id');
            $table->foreign('invoice_item_id')->references('id')->on('invoice_items')->onDelete('cascade');

            // 📋 Field Metadata
            $table->string('field_name');            // Example: Warranty, Color
            $table->text('field_value')->nullable(); // Actual value

            // 🧠 Data Management
            $table->enum('data_type', ['text', 'number', 'date', 'boolean', 'select'])->default('text');
            $table->boolean('is_required')->default(false);
            $table->string('group')->nullable();     // UI grouping (e.g., "Specs", "Delivery Info")
            $table->unsignedSmallInteger('sort_order')->default(0);

            // 📦 Index & Optimization
            $table->index(['invoice_item_id']);
            $table->index(['field_name', 'data_type']);
            $table->index(['group', 'sort_order']);

            // 🕓 Audit Trail & Soft Delete
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_item_fields');
    }
};
