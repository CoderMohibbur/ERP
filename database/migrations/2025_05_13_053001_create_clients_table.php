<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();

            // 🧑 Primary Info
            $table->string('name');                   // Client name (person/contact)
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();

            // 🏢 Company Info
            $table->string('company_name')->nullable();
            $table->string('industry_type')->nullable(); // e.g., IT, Manufacturing
            $table->string('website')->nullable();
            $table->string('tax_id')->nullable();

            // 🟢 Status
            $table->enum('status', ['active', 'inactive'])->default('active');

            // 🧩 Dynamic Custom Fields
            $table->json('custom_fields')->nullable(); // Optional: { "LinkedIn": "...", "Notes": "..." }

            // 🔐 Audit
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();


            // 🗑️ Safe delete + timestamps
            $table->softDeletes();
            $table->timestamps();

            // ⚡ Indexing
            $table->index(['status']);
            $table->index(['company_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
