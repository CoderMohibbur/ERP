<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();

            $table->string('subject', 190);
            $table->string('type', 20)->index(); // ActivityType
            $table->text('body')->nullable();

            $table->dateTime('activity_at')->index();
            $table->dateTime('next_follow_up_at')->nullable()->index();

            $table->string('status', 10)->default('open')->index(); // open/done

            $table->foreignId('actor_id')->constrained('users'); // indexed by default

            // polymorphic: lead/deal/client/project/etc
            $table->string('actionable_type', 255);
            $table->unsignedBigInteger('actionable_id');

            $table->softDeletes();
            $table->timestamps();

            $table->index(['actionable_type', 'actionable_id']);
            $table->index(['actor_id', 'next_follow_up_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
    