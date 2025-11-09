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
        Schema::table('project_members', function (Blueprint $table) {
            // Add client_team_member_id to link to client_team_members table
            $table->foreignId('client_team_member_id')->nullable()->after('employee_id')->constrained('client_team_members')->onDelete('cascade');

            // Update member_type enum to include 'client_team'
            $table->enum('member_type', ['internal', 'client', 'client_team'])->default('internal')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_members', function (Blueprint $table) {
            $table->dropForeign(['client_team_member_id']);
            $table->dropColumn('client_team_member_id');

            // Restore original enum values
            $table->enum('member_type', ['internal', 'client'])->default('internal')->change();
        });
    }
};
