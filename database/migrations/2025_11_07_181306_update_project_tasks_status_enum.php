<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, modify the enum column to include all old and new statuses temporarily
        DB::statement("ALTER TABLE project_tasks MODIFY COLUMN status ENUM('todo', 'in-progress', 'in-review', 'completed', 'blocked', 'on-hold', 'awaiting-feedback', 'staging', 'live') NOT NULL DEFAULT 'todo'");

        // Update any existing 'in-review' tasks to 'awaiting-feedback' (closest match)
        DB::table('project_tasks')
            ->where('status', 'in-review')
            ->update(['status' => 'awaiting-feedback']);

        // Update any existing 'blocked' tasks to 'on-hold'
        DB::table('project_tasks')
            ->where('status', 'blocked')
            ->update(['status' => 'on-hold']);

        // Finally, remove old statuses from the enum
        DB::statement("ALTER TABLE project_tasks MODIFY COLUMN status ENUM('todo', 'on-hold', 'in-progress', 'awaiting-feedback', 'staging', 'live', 'completed') NOT NULL DEFAULT 'todo'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert new statuses to old ones
        DB::table('project_tasks')
            ->where('status', 'awaiting-feedback')
            ->update(['status' => 'in-review']);

        DB::table('project_tasks')
            ->where('status', 'on-hold')
            ->update(['status' => 'blocked']);

        DB::table('project_tasks')
            ->whereIn('status', ['staging', 'live'])
            ->update(['status' => 'completed']);

        // Revert the enum column to old statuses
        DB::statement("ALTER TABLE project_tasks MODIFY COLUMN status ENUM('todo', 'in-progress', 'in-review', 'completed', 'blocked') NOT NULL DEFAULT 'todo'");
    }
};
