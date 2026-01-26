<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('service_renewals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('service_id')
                ->constrained('services')
                ->cascadeOnDelete(); // renewal belongs to service

            $table->date('renewal_date')->index();

            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();

            $table->decimal('amount', 12, 2);

            $table->foreignId('invoice_id')->nullable()
                ->constrained('invoices')
                ->nullOnDelete(); // invoice may be removed/voided later

            $table->string('status', 20)->default('pending')->index(); // RenewalStatus
            $table->dateTime('reminded_at')->nullable();

            $table->foreignId('created_by')->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();

            $table->index(['service_id', 'renewal_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_renewals');
    }
};
