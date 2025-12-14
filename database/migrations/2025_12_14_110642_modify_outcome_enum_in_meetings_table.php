<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Only run ENUM modifications on MySQL - SQLite doesn't support ENUM
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        // First, modify the column to include ALL values (old and new) so we can update
        DB::statement("ALTER TABLE meetings MODIFY COLUMN outcome ENUM('Positive', 'Neutral', 'Negative', 'Pending', 'Successful', 'Follow-up Needed', 'Rescheduled', 'Cancelled', 'No Show') DEFAULT 'Pending'");

        // Now update existing values to map to new values
        DB::table('meetings')->where('outcome', 'Positive')->update(['outcome' => 'Successful']);
        DB::table('meetings')->where('outcome', 'Neutral')->update(['outcome' => 'Pending']);
        DB::table('meetings')->where('outcome', 'Negative')->update(['outcome' => 'Cancelled']);

        // Finally, modify the column to only include new values
        DB::statement("ALTER TABLE meetings MODIFY COLUMN outcome ENUM('Pending', 'Successful', 'Follow-up Needed', 'Rescheduled', 'Cancelled', 'No Show') DEFAULT 'Pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only run ENUM modifications on MySQL - SQLite doesn't support ENUM
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        // First add all values
        DB::statement("ALTER TABLE meetings MODIFY COLUMN outcome ENUM('Positive', 'Neutral', 'Negative', 'Pending', 'Successful', 'Follow-up Needed', 'Rescheduled', 'Cancelled', 'No Show') NULL");

        // Revert values
        DB::table('meetings')->where('outcome', 'Successful')->update(['outcome' => 'Positive']);
        DB::table('meetings')->where('outcome', 'Pending')->update(['outcome' => 'Neutral']);
        DB::table('meetings')->whereIn('outcome', ['Cancelled', 'No Show'])->update(['outcome' => 'Negative']);
        DB::table('meetings')->where('outcome', 'Follow-up Needed')->update(['outcome' => 'Neutral']);
        DB::table('meetings')->where('outcome', 'Rescheduled')->update(['outcome' => 'Neutral']);

        // Finally restrict to original values
        DB::statement("ALTER TABLE meetings MODIFY COLUMN outcome ENUM('Positive', 'Neutral', 'Negative') NULL");
    }
};
