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
        Schema::create('project_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('file_path');
            $table->string('file_type')->nullable(); // mime type
            $table->integer('file_size')->nullable(); // in bytes
            $table->foreignId('uploaded_by')->nullable()->constrained('employees')->onDelete('set null');
            $table->foreignId('assigned_to')->nullable()->constrained('employees')->onDelete('set null');
            $table->text('description')->nullable();
            $table->string('category')->nullable(); // Design, Document, Code, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_files');
    }
};
