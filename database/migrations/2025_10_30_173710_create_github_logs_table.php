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
        Schema::create('github_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('event_type'); // push, pull_request, pull_request_review, etc.
            $table->string('action')->nullable(); // opened, closed, merged, synchronize, etc.
            $table->string('repository_name');
            $table->string('repository_url');
            $table->string('branch')->nullable();
            $table->string('ref')->nullable(); // The git ref resource
            $table->text('commit_message')->nullable();
            $table->string('commit_sha')->nullable();
            $table->string('commit_url')->nullable();
            $table->integer('commits_count')->default(0);
            $table->string('pr_number')->nullable(); // Pull request number
            $table->string('pr_title')->nullable();
            $table->text('pr_description')->nullable();
            $table->string('pr_url')->nullable();
            $table->string('pr_state')->nullable(); // open, closed, merged
            $table->boolean('pr_merged')->default(false);
            $table->string('author_username'); // GitHub username
            $table->string('author_avatar_url')->nullable();
            $table->json('payload')->nullable(); // Store full webhook payload for reference
            $table->timestamp('event_at'); // When the event occurred on GitHub
            $table->timestamps();
            
            $table->index(['employee_id', 'event_at']);
            $table->index('event_type');
            $table->index('repository_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('github_logs');
    }
};
