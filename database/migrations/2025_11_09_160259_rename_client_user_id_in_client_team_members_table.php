<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing data to avoid conflicts
        DB::table('client_team_members')->truncate();

        // Rename column directly
        DB::statement('ALTER TABLE client_team_members CHANGE client_user_id team_member_client_id BIGINT UNSIGNED NULL');

        // Add new foreign key constraint
        DB::statement('ALTER TABLE client_team_members ADD CONSTRAINT client_team_members_team_member_client_id_foreign FOREIGN KEY (team_member_client_id) REFERENCES clients(id) ON DELETE CASCADE');

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_team_members', function (Blueprint $table) {
            // Drop new foreign key
            $table->dropForeign(['team_member_client_id']);

            // Rename back
            $table->renameColumn('team_member_client_id', 'client_user_id');

            // Add back old foreign key
            $table->foreign('client_user_id')->references('id')->on('client_users')->onDelete('cascade');
        });
    }
};
