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
        Schema::create('lead_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained('leads')->onDelete('cascade');
            $table->boolean('daily_call_made')->default(false);
            $table->date('call_date')->index();
            $table->time('call_time');
            $table->foreignId('caller_id')->constrained('users')->onDelete('cascade');
            $table->enum('response_status', ['Interested', '50%', 'Yes', 'Call Later', 'No Response', 'No', 'Phone off']);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_contacts');
    }
};
