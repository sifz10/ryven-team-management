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
        if (Schema::hasTable('review_feedbacks')) {
            return; // Table already exists, skip creation
        }

        Schema::create('review_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('performance_review_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reviewer_id')->constrained('users')->cascadeOnDelete();
            $table->enum('feedback_type', ['self', 'manager', 'peer', 'subordinate', 'client'])->default('peer');
            $table->decimal('rating', 3, 2)->nullable();
            $table->json('ratings')->nullable(); // Detailed ratings per criteria
            $table->text('strengths')->nullable();
            $table->text('areas_for_improvement')->nullable();
            $table->text('comments')->nullable();
            $table->enum('status', ['pending', 'submitted', 'acknowledged'])->default('pending');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            
            $table->index(['performance_review_id', 'feedback_type']);
            $table->unique(['performance_review_id', 'reviewer_id', 'feedback_type'], 'review_feedback_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_feedbacks');
    }
};
