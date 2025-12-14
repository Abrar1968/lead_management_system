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
        Schema::create('extra_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('commission_type', 100); // Bonus, Incentive, Target Achievement, Referral, etc.
            $table->decimal('amount', 10, 2);
            $table->text('description');
            $table->date('date_earned')->index();
            $table->foreignId('related_conversion_id')->nullable()->constrained('conversions')->onDelete('set null');
            $table->enum('status', ['Pending', 'Approved', 'Paid'])->default('Pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            // Index for user commission queries
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extra_commissions');
    }
};
