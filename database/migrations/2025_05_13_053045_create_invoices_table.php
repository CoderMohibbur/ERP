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

            // 🔹 Invoice Details (OLD - kept)
            $table->string('invoice_number')->unique();
            $table->enum('invoice_type', ['proforma', 'final'])->default('final');
            $table->enum('status', ['draft', 'sent', 'paid', 'overdue'])->default('draft');

            // 🔗 Relations (OLD columns kept)
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('terms_id')->nullable();
            $table->unsignedBigInteger('issued_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();

            // 💵 Currency & Amounts (OLD - kept)
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

            // 📉 Notes & Metadata (OLD - kept)
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();

            // 🔐 Audit (OLD - kept)
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            /**
             * ✅ NEW (Minimum ERP compatible) - without removing old fields
             */
            $table->string('erp_status', 20)->default('draft'); // indexed later
            $table->decimal('total', 14, 4)->default(0);
            $table->decimal('paid_total', 14, 4)->default(0);
            $table->decimal('balance', 14, 4)->default(0);

            // ✅ Keep deal_id column + index (NO FK now)
            $table->unsignedBigInteger('deal_id')->nullable();

            // 🗃️ Cleanup & Timestamps
            $table->softDeletes();
            $table->timestamps();

            // ⚡ Indexes (OLD - kept)
            $table->index(['client_id', 'project_id']);
            $table->index(['status', 'invoice_type']);
            $table->index(['issue_date', 'due_date']);
            $table->index(['issued_by', 'approved_by']);

            // ✅ NEW Indexes for ERP
            $table->index(['erp_status', 'due_date'], 'invoices_erp_status_due_idx');
            $table->index(['deal_id'], 'invoices_deal_id_idx');

            // ✅ Foreign Keys (existing tables only)
            $table->foreign('client_id')->references('id')->on('clients')->cascadeOnDelete();
            $table->foreign('project_id')->references('id')->on('projects')->nullOnDelete();

            $table->foreign('issued_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();

            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();

            // ❌ deal_id FK intentionally skipped (deals table not yet created)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
