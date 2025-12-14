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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('lead_number')->unique();
            $table->enum('source', ['WhatsApp', 'Messenger', 'Website']);
            $table->string('client_name');
            $table->string('phone_number', 20)->index();
            $table->string('email')->nullable();
            $table->string('company_name')->nullable();
            $table->enum('service_interested', ['Website', 'Software', 'CRM', 'Marketing']);
            $table->date('lead_date')->index();
            $table->time('lead_time');
            $table->boolean('is_repeat_lead')->default(false);
            $table->json('previous_lead_ids')->nullable();
            $table->enum('priority', ['High', 'Medium', 'Low'])->default('Medium');
            $table->foreignId('assigned_to')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();

            // Additional indexes for performance
            $table->index(['lead_date', 'assigned_to']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
