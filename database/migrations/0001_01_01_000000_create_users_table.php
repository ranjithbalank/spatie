<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| Users, Password Resets & Sessions Migration
|--------------------------------------------------------------------------
|
| This migration creates three tables:
| 1. users                 - Stores all user information
| 2. password_reset_tokens - Stores password reset tokens for users
| 3. sessions              - Stores user session data for authentication
|
| Author: Ranjithbalan K
| Date: 2025-09-17
| Version: v1.0.0
|
*/

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create 'users' table
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // User full name
            $table->string('email')->unique(); // Unique email for login
            $table->timestamp('email_verified_at')->nullable(); // Email verification timestamp
            $table->string('password'); // Encrypted password
            $table->rememberToken(); // Remember me token for persistent login
            $table->timestamps(); // created_at & updated_at
        });

        // Create 'password_reset_tokens' table
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary(); // Email as primary key
            $table->string('token'); // Reset token
            $table->timestamp('created_at')->nullable(); // Token creation time
        });

        // Create 'sessions' table
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary(); // Session ID
            $table->foreignId('user_id')->nullable()->index(); // Linked user ID
            $table->string('ip_address', 45)->nullable(); // IP address of session
            $table->text('user_agent')->nullable(); // Browser/user agent
            $table->longText('payload'); // Serialized session data
            $table->integer('last_activity')->index(); // Last activity timestamp
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop tables in reverse order
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
