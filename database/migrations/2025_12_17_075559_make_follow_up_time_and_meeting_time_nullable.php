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
        Schema::table('follow_ups', function (Blueprint $table) {
            $table->time('follow_up_time')->nullable()->change();
        });

        Schema::table('meetings', function (Blueprint $table) {
            $table->time('meeting_time')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('follow_ups', function (Blueprint $table) {
            $table->time('follow_up_time')->nullable(false)->change();
        });

        Schema::table('meetings', function (Blueprint $table) {
            $table->time('meeting_time')->nullable(false)->change();
        });
    }
};
