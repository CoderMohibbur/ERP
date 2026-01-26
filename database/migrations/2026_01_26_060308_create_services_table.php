<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();

            $table->foreignId('client_id')->constrained('clients'); // indexed by default

            $table->string('type', 30)->index(); // shared_hosting/dedicated/domain/ssl/maintenance
            $table->string('name', 190); // plan/package

            $table->string('billing_cycle', 20)->index(); // monthly/quarterly/half_yearly/yearly/custom

            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('BDT');

            $table->date('started_at')->index();
            $table->date('expires_at')->index();
            $table->date('next_renewal_at')->index();

            $table->string('status', 20)->default('active')->index(); // ServiceStatus
            $table->boolean('auto_invoice')->default(false);

            $table->text('notes')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->index(['status', 'next_renewal_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
