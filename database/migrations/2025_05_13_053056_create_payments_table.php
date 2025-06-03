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
           
            
            $table->decimal('amount', 12, 2);
            $table->date('paid_at');

            // 🔁 Extended fields
            $table->string('transaction_id')->nullable();
            $table->string('reference_number')->nullable();
            $table->text('note')->nullable();
            $table->string('attachment_path')->nullable(); // e.g., receipt PDF/image

            $table->unsignedBigInteger('received_by')->nullable();
            $table->enum('payment_status', ['pending', 'approved', 'rejected'])->default('approved');

            


            $table->index(['invoice_id', 'payment_method_id']);
            $table->index(['payment_status']);
            $table->index(['paid_at']);


            // ✅ Soft deletes and timestamps
            $table->softDeletes();
            $table->timestamps();

            // 🔗 Foreign keys
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('payment_method_id');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
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
