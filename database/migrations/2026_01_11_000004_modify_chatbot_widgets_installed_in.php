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
        // Check if table exists and column needs to be modified
        if (Schema::hasTable('chatbot_widgets')) {
            Schema::table('chatbot_widgets', function (Blueprint $table) {
                // Modify the column to be nullable
                $table->string('installed_in')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('chatbot_widgets')) {
            Schema::table('chatbot_widgets', function (Blueprint $table) {
                $table->string('installed_in')->nullable(false)->change();
            });
        }
    }
};
