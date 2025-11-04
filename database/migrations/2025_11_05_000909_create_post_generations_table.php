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
        Schema::create('post_generations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('social_post_id')->constrained()->onDelete('cascade');
            $table->enum('platform', ['linkedin', 'facebook', 'twitter']); // Target platform
            $table->string('title'); // Input title
            $table->text('description')->nullable(); // Input description
            $table->text('generated_content'); // AI-generated content
            $table->json('generation_metadata')->nullable(); // AI model, tokens used, etc.
            $table->boolean('is_selected')->default(false); // Whether this version was chosen
            $table->timestamps();
            
            $table->index('social_post_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_generations');
    }
};
