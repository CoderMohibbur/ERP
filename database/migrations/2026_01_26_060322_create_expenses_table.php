<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();

            $table->string('title', 190);

            $table->string('category', 20)->index(); // server/tools/salary/office/marketing/other

            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('BDT');

            $table->date('expense_date')->index();

            $table->string('vendor', 190)->nullable();
            $table->string('reference', 190)->nullable();

            $table->text('notes')->nullable();

            $table->foreignId('created_by')->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();

            $table->index(['category', 'expense_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
