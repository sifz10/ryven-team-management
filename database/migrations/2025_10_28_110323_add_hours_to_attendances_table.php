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
        Schema::table('attendances', function (Blueprint $table) {
            $table->integer('hours_worked')->nullable()->after('status');
            $table->integer('minutes_worked')->nullable()->after('hours_worked');
            $table->decimal('calculated_payment', 10, 2)->nullable()->after('minutes_worked');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['hours_worked', 'minutes_worked', 'calculated_payment']);
        });
    }
};
