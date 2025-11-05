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
        Schema::table('daily_work_submissions', function (Blueprint $table) {
            // Add project_id and make project_name nullable for backward compatibility
            $table->foreignId('project_id')->nullable()->after('employee_id')->constrained()->onDelete('cascade');
            $table->string('project_name')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_work_submissions', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropColumn('project_id');
            $table->string('project_name')->nullable(false)->change();
        });
    }
};
