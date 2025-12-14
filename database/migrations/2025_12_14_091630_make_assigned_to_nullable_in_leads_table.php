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
        // Drop the foreign key first
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign(['assigned_to']);
        });

        // Recreate the column as nullable with foreign key
        Schema::table('leads', function (Blueprint $table) {
            $table->unsignedBigInteger('assigned_to')->nullable()->change();
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the foreign key first
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign(['assigned_to']);
        });

        // Recreate the column as not nullable with cascade delete
        Schema::table('leads', function (Blueprint $table) {
            $table->unsignedBigInteger('assigned_to')->nullable(false)->change();
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
