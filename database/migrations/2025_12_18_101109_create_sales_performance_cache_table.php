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
        Schema::create('sales_performance_cache', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Core performance metrics
            $table->integer('total_leads')->default(0);
            $table->integer('total_conversions')->default(0);
            $table->decimal('conversion_rate', 5, 2)->default(0); // 0-100%
            $table->decimal('total_revenue', 12, 2)->default(0);
            $table->decimal('avg_deal_value', 12, 2)->default(0);

            // Activity metrics
            $table->integer('total_calls')->default(0);
            $table->integer('total_follow_ups')->default(0);
            $table->integer('total_meetings')->default(0);
            $table->decimal('response_rate', 5, 2)->default(0); // % of leads with positive response
            $table->decimal('follow_up_rate', 5, 2)->default(0); // % of leads with follow-ups

            // Current workload
            $table->integer('active_leads')->default(0);
            $table->integer('pending_follow_ups')->default(0);

            // Time metrics
            $table->decimal('avg_conversion_days', 8, 2)->nullable(); // Avg days to convert
            $table->decimal('avg_first_contact_hours', 8, 2)->nullable(); // Avg hours to first contact

            // Calculated score
            $table->decimal('performance_score', 5, 2)->default(0); // 0-100

            // Period tracking
            $table->string('period_type')->default('monthly'); // daily, weekly, monthly
            $table->date('period_start');
            $table->date('period_end');

            $table->timestamps();

            // Unique constraint per user per period
            $table->unique(['user_id', 'period_type', 'period_start']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_performance_cache');
    }
};
