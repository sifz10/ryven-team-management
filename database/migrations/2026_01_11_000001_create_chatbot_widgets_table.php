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
        Schema::create('chatbot_widgets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('domain')->unique(); // Domain where widget is installed (e.g., crm.yourapp.com)
            $table->string('api_token')->unique(); // For authentication
            $table->string('installed_in')->nullable()->comment('CRM, Team Management, etc');
            $table->text('welcome_message')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable(); // Widget colors, position, etc
            $table->timestamps();

            $table->index('api_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_widgets');
    }
};
