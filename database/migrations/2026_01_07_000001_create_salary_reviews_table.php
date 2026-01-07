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
        Schema::create('salary_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->date('review_date'); // The date when 6 months complete
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            
            // Salary tracking
            $table->decimal('previous_salary', 12, 2); // Salary before adjustment
            $table->decimal('adjusted_salary', 12, 2)->nullable(); // New salary after adjustment
            $table->decimal('adjustment_amount', 12, 2)->nullable(); // Amount added/deducted
            
            // Review details
            $table->text('performance_notes')->nullable();
            $table->enum('performance_rating', ['poor', 'below_average', 'average', 'good', 'excellent'])->nullable();
            $table->text('adjustment_reason')->nullable();
            
            // Admin info
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            
            $table->timestamps();
            
            $table->index(['employee_id', 'status']);
            $table->index('review_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_reviews');
    }
};
