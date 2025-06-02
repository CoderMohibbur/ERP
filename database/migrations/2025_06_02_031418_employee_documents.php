<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_documents', function (Blueprint $table) {
            $table->id();

            // 🔗 Relationships
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');

            // 📄 Document Info
            $table->string('type'); // e.g., cv, nid, contract, certificate
            $table->string('title')->nullable(); // e.g., "Bachelor's Certificate"
            $table->string('file_path'); // storage path
            $table->string('file_type', 50)->nullable(); // pdf, jpeg, docx, etc.
            $table->integer('file_size')->nullable(); // in KB

            // 🔐 Security & Control
            $table->string('file_hash', 128)->nullable(); // SHA-256 hash to detect duplicates
            $table->enum('visibility', ['admin', 'employee', 'private'])->default('employee');

            // 📅 Expiry
            $table->date('expires_at')->nullable(); // for contract or NID validity

            // 📝 Meta Info
            $table->boolean('is_verified')->default(false);
            $table->text('notes')->nullable();

            // 🔐 Audit
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();

            // 🗑️ Soft delete + timestamps
            $table->softDeletes();
            $table->timestamps();

            // ⚡ Indexes
            $table->index(['employee_id', 'type']);
            $table->index(['uploaded_by', 'verified_by']);
            $table->index(['is_verified']);
            $table->index(['expires_at']);
            $table->index(['file_hash']);
            $table->index(['visibility']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_documents');
    }
};
