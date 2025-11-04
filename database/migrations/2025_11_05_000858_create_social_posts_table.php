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
        Schema::create('social_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('social_account_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('title'); // Post title/topic
            $table->text('description')->nullable(); // Brief description/instructions
            $table->text('content')->nullable(); // Generated or manual content
            $table->text('final_content')->nullable(); // Final edited content before posting
            $table->enum('status', ['draft', 'scheduled', 'posted', 'failed'])->default('draft');
            $table->timestamp('scheduled_at')->nullable(); // When to post
            $table->timestamp('posted_at')->nullable(); // When actually posted
            $table->string('platform_post_id')->nullable(); // ID from social media platform
            $table->json('platform_response')->nullable(); // Response from platform API
            $table->text('error_message')->nullable(); // Error if posting failed
            $table->boolean('auto_generate')->default(false); // Whether to use AI generation
            $table->integer('retry_count')->default(0); // Number of retry attempts
            $table->timestamps();
            
            $table->index(['user_id', 'scheduled_at']);
            $table->index(['status', 'scheduled_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_posts');
    }
};
