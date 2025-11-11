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
        Schema::table('application_tests', function (Blueprint $table) {
            $table->string('submission_token', 64)->unique()->nullable()->after('job_application_id');
            $table->text('submission_notes')->nullable()->after('submission_original_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_tests', function (Blueprint $table) {
            $table->dropColumn(['submission_token', 'submission_notes']);
        });
    }
};
