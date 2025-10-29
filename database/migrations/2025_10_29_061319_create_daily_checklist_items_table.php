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
        Schema::create('daily_checklist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_checklist_id')->constrained()->onDelete('cascade');
            $table->foreignId('checklist_template_item_id')->constrained()->onDelete('cascade');
            $table->string('title'); // Copy of template item title for historical reference
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_checklist_items');
    }
};
