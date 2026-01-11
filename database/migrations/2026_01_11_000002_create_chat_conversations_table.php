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
        Schema::create('chat_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chatbot_widget_id')->constrained()->cascadeOnDelete();
            $table->string('visitor_name')->nullable();
            $table->string('visitor_email')->nullable();
            $table->string('visitor_phone')->nullable();
            $table->string('visitor_ip')->nullable();
            $table->json('visitor_metadata')->nullable(); // Browser info, location, etc
            $table->foreignId('assigned_to_employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->enum('status', ['pending', 'active', 'closed'])->default('pending');
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();

            $table->index(['chatbot_widget_id', 'status']);
            $table->index('assigned_to_employee_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_conversations');
    }
};
