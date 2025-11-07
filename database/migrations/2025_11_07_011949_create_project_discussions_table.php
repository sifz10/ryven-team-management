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
        Schema::create('project_discussions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('employees')->onDelete('set null'); // Can be employee
            $table->foreignId('parent_id')->nullable()->constrained('project_discussions')->onDelete('cascade'); // For replies
            $table->text('message');
            $table->json('mentions')->nullable(); // Array of mentioned user IDs
            $table->json('attachments')->nullable(); // Array of file paths
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_discussions');
    }
};
