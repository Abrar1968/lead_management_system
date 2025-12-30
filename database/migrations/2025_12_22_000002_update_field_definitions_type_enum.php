<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw SQL for MySQL only (ENUM modification), skip for SQLite in tests
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE field_definitions MODIFY COLUMN type ENUM('text', 'image', 'link', 'document') NOT NULL DEFAULT 'text'");
        } else {
            // For SQLite, we need to recreate the table or use string type
            // Since SQLite doesn't support ENUM, we'll add a check constraint or just use string
            Schema::table('field_definitions', function (Blueprint $table) {
                $table->string('type', 20)->default('text')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE field_definitions MODIFY COLUMN type ENUM('text', 'image', 'link') NOT NULL DEFAULT 'text'");
        } else {
            Schema::table('field_definitions', function (Blueprint $table) {
                $table->string('type', 20)->default('text')->change();
            });
        }
    }
};
