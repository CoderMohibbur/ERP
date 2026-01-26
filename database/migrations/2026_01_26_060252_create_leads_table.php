<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();

            $table->string('name', 190);
            $table->string('phone', 30)->index();
            $table->string('email', 190)->nullable();
            $table->string('company', 190)->nullable();

            $table->string('source', 50)->index(); // whatsapp/facebook/website/referral
            $table->string('status', 20)->default('new')->index(); // LeadStatus

            $table->foreignId('owner_id')->constrained('users'); // indexed by default

            $table->dateTime('next_follow_up_at')->nullable()->index();
            $table->dateTime('last_contacted_at')->nullable();
            $table->text('notes')->nullable();

            // nullable + unique + FK (avoid duplicate indexes)
            $table->unsignedBigInteger('converted_client_id')->nullable()->unique();
            $table->foreign('converted_client_id')
                ->references('id')->on('clients')
                ->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
