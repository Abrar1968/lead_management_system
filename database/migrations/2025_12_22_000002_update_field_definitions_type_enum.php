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
        // Use raw SQL to modify ENUM column as Doctrine doesn't support ENUM modifications well
        DB::statement("ALTER TABLE field_definitions MODIFY COLUMN type ENUM('text', 'image', 'link', 'document') NOT NULL DEFAULT 'text'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE field_definitions MODIFY COLUMN type ENUM('text', 'image', 'link') NOT NULL DEFAULT 'text'");
    }
};
