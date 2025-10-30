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
        Schema::table('daily_checklists', function (Blueprint $table) {
            $table->string('email_token', 64)->nullable()->unique()->after('date');
            $table->timestamp('email_sent_at')->nullable()->after('email_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_checklists', function (Blueprint $table) {
            $table->dropColumn(['email_token', 'email_sent_at']);
        });
    }
};
