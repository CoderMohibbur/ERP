<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();

            // 🔗 Relation
            $table->unsignedBigInteger('invoice_id');

            // 📦 Item Info
            $table->string('item_code')->nullable(); // optional SKU
            $table->string('item_name');             // main label
            $table->text('description')->nullable(); // optional note

            // 📐 Quantity & Unit
            $table->integer('quantity')->default(1);
            $table->string('unit', 20)->default('pcs'); // pcs, kg, hour, etc.
            $table->unsignedBigInteger('item_category_id')->nullable();

            // 💰 Pricing
            $table->decimal('unit_price', 12, 2);
            $table->decimal('tax_percent', 5, 2)->nullable();
            $table->decimal('total', 14, 2);

            // 🔐 Audit
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            // 🗑️ Soft Delete + Timestamps
            $table->softDeletes();
            $table->timestamps();

            // ⚡ Indexes
            $table->index(['invoice_id']);
            $table->index(['item_category_id']);
            $table->index(['item_code', 'item_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
