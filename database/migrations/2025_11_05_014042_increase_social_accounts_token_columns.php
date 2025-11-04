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
        Schema::table('social_accounts', function (Blueprint $table) {
            // Change access_token and refresh_token to TEXT to handle long tokens
            $table->text('access_token')->nullable()->change();
            $table->text('refresh_token')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('social_accounts', function (Blueprint $table) {
            // Revert back to string (255)
            $table->string('access_token')->nullable()->change();
            $table->string('refresh_token')->nullable()->change();
        });
    }
};
