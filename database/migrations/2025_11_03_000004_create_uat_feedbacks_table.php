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
        Schema::create('uat_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('uat_test_case_id')->constrained()->onDelete('cascade');
            $table->foreignId('uat_user_id')->constrained()->onDelete('cascade');
            $table->text('comment')->nullable();
            $table->enum('status', ['pending', 'passed', 'failed', 'blocked'])->default('pending');
            $table->text('attachment_path')->nullable();
            $table->timestamps();
            
            $table->unique(['uat_test_case_id', 'uat_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uat_feedbacks');
    }
};

