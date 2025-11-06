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
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('review_cycle_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['okr', 'kpi', 'smart', 'development', 'project'])->default('smart');
            $table->enum('category', ['individual', 'team', 'company'])->default('individual');
            $table->integer('weight')->default(1); // Importance weighting
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->enum('status', ['not_started', 'in_progress', 'at_risk', 'completed', 'cancelled'])->default('not_started');
            $table->integer('progress')->default(0); // 0-100%
            $table->json('key_results')->nullable(); // For OKRs - array of key results
            $table->json('metrics')->nullable(); // KPI metrics
            $table->text('notes')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            
            $table->index(['employee_id', 'status']);
            $table->index('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goals');
    }
};
