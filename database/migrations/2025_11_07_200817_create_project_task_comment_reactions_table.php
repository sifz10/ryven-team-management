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
        Schema::create('project_task_comment_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comment_id')->constrained('project_task_comments')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('reaction_type', 20); // 👍 like, ❤️ love, 😂 laugh, 😮 wow, 😢 sad, 😡 angry
            $table->timestamps();

            // Ensure one reaction type per user per comment (custom short index name)
            $table->unique(['comment_id', 'employee_id', 'reaction_type'], 'task_comment_reaction_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_task_comment_reactions');
    }
};
