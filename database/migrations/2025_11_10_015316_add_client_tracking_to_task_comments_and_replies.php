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
        // Add client tracking to comments
        Schema::table('project_task_comments', function (Blueprint $table) {
            $table->boolean('commented_by_client')->default(false)->after('comment');
            $table->string('client_name')->nullable()->after('commented_by_client');
        });

        // Add client tracking to replies
        Schema::table('project_task_comment_replies', function (Blueprint $table) {
            $table->boolean('replied_by_client')->default(false)->after('reply');
            $table->string('client_name')->nullable()->after('replied_by_client');
        });

        // Add client tracking to reactions
        Schema::table('project_task_comment_reactions', function (Blueprint $table) {
            $table->boolean('reacted_by_client')->default(false)->after('reaction_type');
            $table->unsignedBigInteger('reacted_by_client_id')->nullable()->after('reacted_by_client');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_task_comments', function (Blueprint $table) {
            $table->dropColumn(['commented_by_client', 'client_name']);
        });

        Schema::table('project_task_comment_replies', function (Blueprint $table) {
            $table->dropColumn(['replied_by_client', 'client_name']);
        });

        Schema::table('project_task_comment_reactions', function (Blueprint $table) {
            $table->dropColumn(['reacted_by_client', 'reacted_by_client_id']);
        });
    }
};
