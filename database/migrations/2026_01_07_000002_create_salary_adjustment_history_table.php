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
        Schema::create('salary_adjustment_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('salary_review_id')->nullable()->constrained('salary_reviews')->nullOnDelete();
            
            // Salary tracking
            $table->decimal('old_salary', 12, 2);
            $table->decimal('new_salary', 12, 2);
            $table->decimal('adjustment_amount', 12, 2); // Can be positive or negative
            
            // Details
            $table->enum('type', ['review', 'promotion', 'demotion', 'adjustment', 'manual'])->default('review');
            $table->text('reason')->nullable();
            
            // Admin info
            $table->foreignId('adjusted_by')->constrained('users')->cascadeOnDelete();
            $table->string('currency', 3)->default('USD');
            
            $table->timestamps();
            
            $table->index(['employee_id', 'created_at']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_adjustment_history');
    }
};
