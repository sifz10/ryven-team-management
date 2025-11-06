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
        Schema::create('employee_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('skill_id')->constrained()->cascadeOnDelete();
            $table->integer('proficiency_level')->default(1); // 1-5 scale
            $table->enum('proficiency_label', ['beginner', 'intermediate', 'advanced', 'expert', 'master'])->default('beginner');
            $table->integer('years_experience')->nullable();
            $table->date('last_assessed_at')->nullable();
            $table->foreignId('assessed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->boolean('is_primary')->default(false); // Primary skill for employee
            $table->timestamps();
            
            $table->unique(['employee_id', 'skill_id']);
            $table->index('proficiency_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_skills');
    }
};
