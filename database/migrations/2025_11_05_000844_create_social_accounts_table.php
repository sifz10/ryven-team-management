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
        Schema::create('social_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('platform', ['linkedin', 'facebook', 'twitter']); // Social media platform
            $table->string('platform_user_id')->nullable(); // Platform-specific user/page ID
            $table->string('platform_username')->nullable(); // Display name
            $table->string('access_token')->nullable(); // OAuth access token (encrypted)
            $table->string('refresh_token')->nullable(); // OAuth refresh token (encrypted)
            $table->timestamp('token_expires_at')->nullable(); // Token expiration
            $table->json('platform_data')->nullable(); // Additional platform-specific data
            $table->boolean('is_active')->default(true); // Account status
            $table->timestamps();
            
            $table->unique(['user_id', 'platform', 'platform_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_accounts');
    }
};
