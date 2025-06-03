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

            // 🔹 Invoice Details
            $table->string('invoice_number')->unique();
            $table->enum('invoice_type', ['proforma', 'final'])->default('final');
            $table->enum('status', ['draft', 'sent', 'paid', 'overdue'])->default('draft');

            // 🔗 Relations
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('terms_id')->nullable();
            $table->unsignedBigInteger('issued_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();

            // 💵 Currency & Amounts
            $table->string('currency', 10)->default('BDT');
            $table->decimal('currency_rate', 10, 4)->default(1);
            $table->date('issue_date');
            $table->date('due_date');
            $table->decimal('sub_total', 14, 4)->default(0);
            $table->enum('discount_type', ['flat', 'percentage'])->nullable();
            $table->decimal('discount_value', 14, 4)->nullable();
            $table->decimal('tax_rate', 5, 2)->nullable();
            $table->decimal('total_amount', 14, 4)->default(0);
            $table->decimal('paid_amount', 14, 4)->default(0);
            $table->decimal('due_amount', 14, 4)->default(0);

            // 📉 Notes & Metadata
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();

            // 🔐 Audit
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            // 🗃️ Cleanup & Timestamps
            $table->softDeletes();
            $table->timestamps();

            // ⚡ Indexes
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
