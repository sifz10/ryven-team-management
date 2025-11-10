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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_post_id')->constrained()->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->text('cover_letter')->nullable();
            $table->string('resume_path'); // CV/Resume file
            $table->string('resume_original_name');
            $table->text('resume_text')->nullable(); // Extracted text from CV
            $table->string('ai_status')->default('pending'); // pending, best_match, good_to_go, not_good_fit
            $table->integer('ai_match_score')->nullable(); // 0-100
            $table->text('ai_analysis')->nullable(); // JSON with AI insights
            $table->string('application_status')->default('new'); // new, screening, interview, test_sent, rejected, hired
            $table->text('admin_notes')->nullable();
            $table->boolean('added_to_talent_pool')->default(false);
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('employees')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
