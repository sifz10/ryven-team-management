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
        Schema::create('job_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('location')->nullable();
            $table->string('job_type')->default('full-time'); // full-time, part-time, contract, remote
            $table->string('experience_level')->nullable(); // entry, mid, senior
            $table->decimal('salary_min', 10, 2)->nullable();
            $table->decimal('salary_max', 10, 2)->nullable();
            $table->string('salary_currency')->default('BDT');
            $table->text('requirements')->nullable();
            $table->text('responsibilities')->nullable();
            $table->text('benefits')->nullable();
            $table->string('status')->default('draft'); // draft, published, closed
            $table->date('application_deadline')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('department')->nullable();
            $table->integer('positions_available')->default(1);
            $table->boolean('ai_screening_enabled')->default(true);
            $table->text('ai_screening_criteria')->nullable(); // JSON criteria for AI
            $table->foreignId('created_by')->constrained('employees')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_posts');
    }
};
