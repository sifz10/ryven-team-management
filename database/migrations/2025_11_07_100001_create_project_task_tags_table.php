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
        Schema::create('project_task_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('color')->default('gray'); // For visual distinction
            $table->timestamps();
        });

        Schema::create('project_task_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_task_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_task_tag_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_task_tag');
        Schema::dropIfExists('project_task_tags');
    }
};
