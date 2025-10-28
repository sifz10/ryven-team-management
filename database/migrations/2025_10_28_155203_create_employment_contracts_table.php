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
        Schema::create('employment_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            
            // Contract Details
            $table->string('contract_type')->default('permanent'); // permanent, fixed_term, part_time
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('job_title');
            $table->string('department')->nullable();
            $table->text('job_description')->nullable();
            
            // Compensation
            $table->decimal('salary', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('payment_frequency')->default('monthly'); // monthly, bi-weekly, weekly
            
            // Working Conditions
            $table->integer('working_hours_per_week')->default(40);
            $table->string('work_location')->nullable();
            $table->string('work_schedule')->nullable(); // e.g., "Mon-Fri, 9AM-5PM"
            
            // Probation & Notice
            $table->integer('probation_period_days')->default(90);
            $table->integer('notice_period_days')->default(30);
            
            // Benefits
            $table->text('benefits')->nullable();
            $table->integer('annual_leave_days')->default(20);
            $table->integer('sick_leave_days')->default(10);
            
            // Additional Terms
            $table->text('responsibilities')->nullable();
            $table->text('additional_terms')->nullable();
            
            // Contract Status
            $table->string('status')->default('draft'); // draft, active, terminated, expired
            $table->date('signed_date')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employment_contracts');
    }
};
