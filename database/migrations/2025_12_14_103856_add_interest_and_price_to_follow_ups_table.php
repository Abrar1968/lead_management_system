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
            $table->enum('interest', ['Yes', 'No', 'No Response', '50%', 'Phone Off', 'Call Later'])->nullable()->after('notes');
            $table->decimal('price', 10, 2)->nullable()->after('interest');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('follow_ups', function (Blueprint $table) {
            $table->dropColumn(['interest', 'price']);
        });
    }
};
