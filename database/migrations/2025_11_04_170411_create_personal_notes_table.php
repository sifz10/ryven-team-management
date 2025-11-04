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
        Schema::create('personal_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // website_link, password, backup_code, text, file
            $table->string('title');
            $table->text('content')->nullable();
            $table->string('url')->nullable(); // For website links
            $table->string('file_path')->nullable(); // For file uploads
            $table->timestamp('reminder_time')->nullable();
            $table->boolean('reminder_sent')->default(false);
            $table->timestamps();
            
            $table->index(['user_id', 'created_at']);
            $table->index('reminder_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_notes');
    }
};
