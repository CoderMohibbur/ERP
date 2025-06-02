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
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');

            // 📦 Item Info
            $table->string('item_code')->nullable(); // optional SKU
            $table->string('item_name');             // main label
            $table->text('description')->nullable(); // optional note

            // 📐 Quantity & Unit
            $table->integer('quantity')->default(1);
            $table->string('unit', 20)->default('pcs'); // pcs, kg, hour, etc.
            $table->foreignId('item_category_id')->nullable()->constrained('item_categories')->nullOnDelete();

            // 💰 Pricing
            $table->decimal('unit_price', 12, 2);
            $table->decimal('tax_percent', 5, 2)->nullable();
            $table->decimal('total', 14, 2);

            // 🔐 Audit
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

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
