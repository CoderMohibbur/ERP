<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // ✅ Jetstream default fields
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->unsignedBigInteger('current_team_id')->nullable(); // Jetstream Team support
            $table->string('profile_photo_path', 2048)->nullable();

            $table->unsignedBigInteger('role_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->string('timezone', 100)->nullable();
            $table->string('language', 20)->default('en');
            $table->string('ip_address', 45)->nullable();
            $table->string('login_device')->nullable();
            $table->text('user_agent')->nullable();

            // ✅ Meta Fields
            $table->boolean('profile_completed')->default(false);
            $table->boolean('force_password_reset')->default(false);
            $table->timestamp('last_password_change_at')->nullable();
            $table->integer('api_limit')->nullable();
            $table->string('session_token')->nullable();

            // ✅ Audit Tracking
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            // ✅ Soft delete + timestamps
            $table->softDeletes(); // 🔁 soft delete support enabled
            $table->timestamps();

            // ✅ Indexing for performance
            $table->index(['email', 'is_active']);
            $table->index('last_login_at');
        });

        // ✅ Password Reset Tokens
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // ✅ Session Tracking
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
