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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status')->default('active'); // active, completed, on-hold, cancelled
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            
            // Client Information
            $table->string('client_name')->nullable();
            $table->string('client_email')->nullable();
            $table->string('client_phone')->nullable();
            $table->string('client_company')->nullable();
            $table->text('client_address')->nullable();
            
            // Project Details
            $table->decimal('budget', 12, 2)->nullable();
            $table->string('currency', 10)->default('USD');
            $table->integer('priority')->default(2); // 1=Low, 2=Medium, 3=High, 4=Critical
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
