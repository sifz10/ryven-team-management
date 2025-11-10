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
        Schema::create('application_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_application_id')->constrained()->onDelete('cascade');
            $table->string('test_title');
            $table->text('test_description')->nullable();
            $table->text('test_instructions')->nullable();
            $table->string('test_file_path')->nullable(); // Test document/questions
            $table->string('submission_file_path')->nullable(); // Candidate's submission
            $table->string('submission_original_name')->nullable();
            $table->string('status')->default('sent'); // sent, submitted, reviewed
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('deadline')->nullable();
            $table->integer('score')->nullable();
            $table->text('feedback')->nullable();
            $table->foreignId('sent_by')->constrained('employees')->onDelete('cascade');
            $table->foreignId('reviewed_by')->nullable()->constrained('employees')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_tests');
    }
};
