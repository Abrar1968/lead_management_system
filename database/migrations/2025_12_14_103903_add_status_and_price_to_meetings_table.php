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
        Schema::table('meetings', function (Blueprint $table) {
            $table->enum('meeting_status', ['Positive', 'Negative', 'Confirmed', 'Pending'])->default('Pending')->after('outcome');
            $table->decimal('price', 10, 2)->nullable()->after('meeting_status');
            $table->foreignId('follow_up_id')->nullable()->after('lead_id')->constrained('follow_ups')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meetings', function (Blueprint $table) {
            $table->dropForeign(['follow_up_id']);
            $table->dropColumn(['meeting_status', 'price', 'follow_up_id']);
        });
    }
};
