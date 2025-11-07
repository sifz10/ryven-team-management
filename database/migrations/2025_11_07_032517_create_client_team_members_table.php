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
        Schema::create('client_team_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_user_id')->nullable()->constrained()->nullOnDelete(); // Links to ClientUser for authentication
            $table->string('name');
            $table->string('email')->unique();
            $table->string('role')->nullable(); // e.g., 'Manager', 'Developer', 'Designer'
            $table->string('status')->default('pending'); // pending, active, inactive
            $table->string('invitation_token')->unique()->nullable();
            $table->timestamp('invitation_sent_at')->nullable();
            $table->timestamp('joined_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_team_members');
    }
};
