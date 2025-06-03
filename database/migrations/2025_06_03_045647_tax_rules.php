<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tax_rules', function (Blueprint $table) {
            $table->id();

            // 📋 Tax Definition
            $table->string('name');                 // e.g., VAT 15%, Service Tax
            $table->decimal('rate_percent', 6, 3);  // 15.000%
            $table->enum('scope', ['global', 'category', 'item', 'project'])->default('global');
            $table->boolean('is_active')->default(true);

            // 🕓 Applicability
            $table->date('applicable_from')->nullable(); // When it starts
            $table->date('applicable_to')->nullable();   // Optional expiry

            // 🧠 Optimization Fields
            $table->string('country_code', 5)->nullable();  // For international use
            $table->string('region')->nullable();           // e.g., Dhaka Zone

            // 🧾 Meta
            $table->text('description')->nullable();

            // 🔐 Audit & Control
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // 🔍 Indexing
            $table->index(['scope', 'is_active']);
            $table->index(['country_code', 'region']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tax_rules');
    }
};
