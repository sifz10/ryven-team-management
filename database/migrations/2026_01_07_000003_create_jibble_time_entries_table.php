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
        Schema::create('jibble_time_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('jibble_entry_id')->unique();
            $table->timestamp('clock_in_time');
            $table->timestamp('clock_out_time')->nullable();
            $table->integer('duration_minutes')->nullable(); // Total duration in minutes
            $table->text('notes')->nullable();
            $table->string('location')->nullable();
            $table->json('jibble_data')->nullable(); // Store raw Jibble data
            $table->timestamp('synced_at');
            $table->timestamps();
            
            $table->index('employee_id');
            $table->index('clock_in_time');
            $table->index('jibble_entry_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jibble_time_entries');
    }
};
