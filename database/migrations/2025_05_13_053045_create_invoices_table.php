<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();

            // 🔢 Invoice Core
            $table->string('invoice_number')->unique(); // e.g., INV-1001
            $table->enum('invoice_type', ['proforma', 'final'])->default('final');
            $table->enum('status', ['draft', 'sent', 'paid', 'overdue'])->default('draft');

            // 🔗 Relations
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->foreignId('terms_id')->nullable()->constrained('terms_and_conditions')->nullOnDelete(); // reusable terms
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();

            // 💵 Currency
            $table->string('currency', 10)->default('BDT');
            $table->decimal('currency_rate', 10, 4)->default(1); // exchange rate

            // 📅 Dates
            $table->date('issue_date');
            $table->date('due_date');

            // 💰 Amounts
            $table->decimal('sub_total', 12, 2)->default(0);
            $table->enum('discount_type', ['flat', 'percentage'])->nullable();
            $table->decimal('discount_value', 12, 2)->nullable();
            $table->decimal('tax_rate', 5, 2)->nullable();
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('due_amount', 12, 2)->default(0);

            // 📝 Extra
            $table->text('notes')->nullable();

            // 🔐 Audit
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            // 🗑️ Soft delete + timestamps
            $table->softDeletes();
            $table->timestamps();

            // ⚡ Indexing
            $table->index(['client_id', 'project_id']);
            $table->index(['status', 'invoice_type']);
            $table->index(['issue_date', 'due_date']);
            $table->index(['issued_by', 'approved_by']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
