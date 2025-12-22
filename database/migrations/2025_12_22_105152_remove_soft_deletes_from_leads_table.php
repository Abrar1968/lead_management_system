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
        Schema::table('leads', function (Blueprint $table) {
            // Remove soft deletes
            $table->dropSoftDeletes();

            // Add back unique constraint on lead_number since we're using hard deletes now
            $table->unique('lead_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // Remove unique constraint
            $table->dropUnique(['lead_number']);

            // Add back soft deletes
            $table->softDeletes();
        });
    }
};
