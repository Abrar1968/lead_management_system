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
        Schema::create('commission_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Sales Commission", "Referral Bonus"
            $table->string('slug')->unique(); // URL-friendly identifier
            $table->text('description')->nullable();
            $table->enum('calculation_type', ['fixed', 'percentage'])->default('fixed');
            $table->decimal('default_rate', 10, 2)->default(0); // Default rate for this type
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false); // If true, assigned to new users automatically
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_types');
    }
};
