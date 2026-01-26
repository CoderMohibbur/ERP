<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('deals', function (Blueprint $table) {
            $table->id();

            $table->string('title', 190);

            $table->foreignId('lead_id')->nullable()
                ->constrained('leads')->nullOnDelete();

            $table->foreignId('client_id')->nullable()
                ->constrained('clients')->nullOnDelete();

            $table->string('stage', 20)->default('new')->index(); // DealStage

            $table->decimal('value_estimated', 12, 2)->default(0);
            $table->string('currency', 3)->default('BDT');

            $table->unsignedTinyInteger('probability')->default(0); // 0-100

            $table->date('expected_close_date')->nullable()->index();

            $table->dateTime('won_at')->nullable()->index();
            $table->dateTime('lost_at')->nullable()->index();
            $table->string('lost_reason', 255)->nullable();

            $table->foreignId('owner_id')->constrained('users'); // indexed by default

            $table->foreignId('created_by')->nullable()
                ->constrained('users')->nullOnDelete();

            $table->foreignId('updated_by')->nullable()
                ->constrained('users')->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();

            // Helpful composite index for pipeline queries
            $table->index(['stage', 'owner_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};
