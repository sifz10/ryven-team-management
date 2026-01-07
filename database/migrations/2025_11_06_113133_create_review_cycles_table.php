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
        if (Schema::hasTable('review_cycles')) {
            return; // Table already exists, skip creation
        }

        Schema::create('review_cycles', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Q1 2025 Review, Annual 2025, etc.
            $table->enum('type', ['quarterly', 'annual', 'mid-year', 'probation', 'custom'])->default('quarterly');
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->date('review_due_date'); // Deadline for completing reviews
            $table->enum('status', ['planning', 'active', 'in_review', 'completed', 'archived'])->default('planning');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            
            $table->index(['status', 'start_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_cycles');
    }
};
