<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('discount_schemes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('discount_type_id')
                ->nullable()
                ->constrained('discount_types')
                ->nullOnDelete();

            // 📋 Discount Info
            $table->string('name');                        // e.g., Eid Offer, 15% Off
            $table->enum('type', ['flat', 'percentage']);  // Flat amount or percentage
            $table->decimal('value', 10, 2);               // 100.00 or 15.00

            // 🧩 Scope
            $table->enum('applies_to', ['invoice', 'item', 'category', 'client'])->default('item');
            $table->unsignedBigInteger('reference_id')->nullable(); // optional relation

            // ⏱️ Validity
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->boolean('is_active')->default(true);

            // 🧾 Meta
            $table->text('conditions')->nullable();        // Optional JSON or plain note
            $table->text('description')->nullable();

            // 🔐 Audit & Control
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            // preference: softDeletes আগে, তারপর timestamps
            $table->softDeletes();
            $table->timestamps();

            // 🔍 Indexing
            $table->index(['type', 'applies_to', 'reference_id']);
            $table->index(['valid_from', 'valid_to']);
            $table->index(['is_active']);
            $table->index(['discount_type_id']); // FK column indexing (helpful)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discount_schemes');
    }
};
