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
        Schema::create('jibble_leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('jibble_request_id')->unique();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status'); // pending, approved, rejected, cancelled
            $table->string('leave_type'); // vacation, sick, personal, etc.
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('days_count', 5, 2)->nullable();
            $table->json('jibble_data')->nullable(); // Store raw Jibble data
            $table->timestamp('synced_at');
            $table->timestamps();
            
            $table->index('employee_id');
            $table->index('status');
            $table->index('start_date');
            $table->index('jibble_request_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jibble_leave_requests');
    }
};
