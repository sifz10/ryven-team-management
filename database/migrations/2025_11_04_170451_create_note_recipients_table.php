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
        Schema::create('note_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_note_id')->constrained()->cascadeOnDelete();
            $table->string('email');
            $table->timestamps();
            
            $table->index(['personal_note_id', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('note_recipients');
    }
};
