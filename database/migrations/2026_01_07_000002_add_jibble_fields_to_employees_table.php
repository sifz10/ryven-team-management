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
        Schema::table('employees', function (Blueprint $table) {
            // Add Jibble integration columns
            $table->string('jibble_id')->nullable()->after('email');
            $table->string('jibble_email')->nullable()->after('jibble_id');
            $table->json('jibble_data')->nullable()->after('jibble_email');
            $table->timestamp('jibble_synced_at')->nullable()->after('jibble_data');
            
            // Indexes for syncing
            $table->index('jibble_id');
            $table->index('jibble_synced_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropIndex(['jibble_id']);
            $table->dropIndex(['jibble_synced_at']);
            $table->dropColumn(['jibble_id', 'jibble_email', 'jibble_data', 'jibble_synced_at']);
        });
    }
};
