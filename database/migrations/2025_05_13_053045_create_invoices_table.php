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

            $table->string('invoice_number')->unique(); // e.g., INV-1001

            // ❌ No inline foreign constraint here
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('project_id')->nullable();


            $table->enum('status', ['draft', 'sent', 'paid', 'overdue'])->default('draft');
            $table->string('currency', 10)->default('BDT');

            $table->date('issue_date');
            $table->date('due_date');

            $table->decimal('sub_total', 10, 2)->default(0);
            $table->enum('discount_type', ['flat', 'percentage'])->nullable();
            $table->decimal('discount_value', 10, 2)->nullable();
            $table->decimal('tax_rate', 5, 2)->nullable(); // Tax in %

            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('due_amount', 10, 2)->default(0);

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
