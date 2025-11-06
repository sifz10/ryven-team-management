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
        Schema::create('performance_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('review_cycle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('template_id')->nullable()->constrained('review_templates')->nullOnDelete();
            $table->enum('status', ['pending', 'in_progress', 'submitted', 'completed', 'acknowledged'])->default('pending');
            $table->decimal('overall_rating', 3, 2)->nullable(); // e.g., 4.25 out of 5
            $table->json('ratings')->nullable(); // Detailed ratings per section
            $table->text('strengths')->nullable();
            $table->text('areas_for_improvement')->nullable();
            $table->text('achievements')->nullable();
            $table->text('manager_comments')->nullable();
            $table->text('employee_comments')->nullable(); // Self-assessment
            $table->boolean('requires_pip')->default(false); // Performance Improvement Plan
            $table->date('acknowledged_at')->nullable();
            $table->foreignId('reviewer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->index(['employee_id', 'review_cycle_id']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_reviews');
    }
};
