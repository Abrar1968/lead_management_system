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
        Schema::create('client_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversion_id')->constrained('conversions')->onDelete('cascade');
            $table->text('address')->nullable();
            $table->text('billing_info')->nullable();
            $table->string('support_contact_person')->nullable();
            $table->boolean('whatsapp_group_created')->default(false);
            $table->text('feedback')->nullable();
            $table->boolean('remarketing_eligible')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_details');
    }
};
