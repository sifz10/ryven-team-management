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
        Schema::table('notifications', function (Blueprint $table) {
            // Add Laravel notification system columns
            $table->string('notifiable_type')->after('id');
            $table->unsignedBigInteger('notifiable_id')->after('notifiable_type');

            // Keep existing columns for backward compatibility
            // user_id, type, title, message, icon, data, read_at, timestamps already exist

            // Add index for polymorphic relationship
            $table->index(['notifiable_type', 'notifiable_id']);
        });

        // Migrate existing notifications: copy user_id to notifiable_id
        DB::table('notifications')->whereNotNull('user_id')->update([
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id' => DB::raw('user_id')
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex(['notifiable_type', 'notifiable_id']);
            $table->dropColumn(['notifiable_type', 'notifiable_id']);
        });
    }
};
