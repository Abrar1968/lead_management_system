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

        // Modify the status ENUM to include 'Negotiation'
        DB::statement("ALTER TABLE leads MODIFY COLUMN status ENUM('New', 'Contacted', 'Qualified', 'Negotiation', 'Converted', 'Lost') DEFAULT 'New'");
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

        // Convert Negotiation back to Qualified before removing it
        DB::table('leads')->where('status', 'Negotiation')->update(['status' => 'Qualified']);

        // Revert to original ENUM
        DB::statement("ALTER TABLE leads MODIFY COLUMN status ENUM('New', 'Contacted', 'Qualified', 'Converted', 'Lost') DEFAULT 'New'");
    }
};
