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
        Schema::create('talent_pool', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_application_id')->nullable()->constrained()->onDelete('set null');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->text('skills')->nullable(); // JSON array
            $table->string('experience_level')->nullable();
            $table->string('resume_path')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('potential'); // potential, contacted, interested, not_interested
            $table->string('source')->default('job_application'); // job_application, manual, referral, etc.
            $table->timestamp('last_contacted_at')->nullable();
            $table->foreignId('added_by')->constrained('employees')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talent_pool');
    }
};
