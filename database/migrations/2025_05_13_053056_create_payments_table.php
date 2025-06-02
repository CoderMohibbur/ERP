<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // Original fields
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('payment_method_id');
            $table->decimal('amount', 12, 2);
            $table->date('paid_at');

            // 🔁 Extended fields
            $table->string('transaction_id')->nullable();
            $table->string('reference_number')->nullable();
            $table->text('note')->nullable();
            $table->string('attachment_path')->nullable(); // e.g., receipt PDF/image

            $table->unsignedBigInteger('received_by')->nullable();
            $table->enum('payment_status', ['pending', 'approved', 'rejected'])->default('approved');

            // ✅ Audit fields
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();


            $table->index(['invoice_id', 'payment_method_id']);
            $table->index(['payment_status']);
            $table->index(['paid_at']);


            // ✅ Soft deletes and timestamps
            $table->softDeletes();
            $table->timestamps();

            // 🔗 Foreign keys
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('restrict');
            $table->foreign('received_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
