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
        Schema::create('conversions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained('leads')->onDelete('cascade');
            $table->foreignId('converted_by')->constrained('users')->onDelete('cascade');
            $table->date('conversion_date')->index();
            $table->decimal('deal_value', 10, 2);
            // Immutable commission data - stored at conversion time
            $table->decimal('commission_rate_used', 10, 2);
            $table->enum('commission_type_used', ['fixed', 'percentage']);
            $table->decimal('commission_amount', 10, 2);
            $table->string('package_plan');
            $table->boolean('advance_paid')->default(false);
            $table->string('payment_method', 100)->nullable();
            $table->date('signing_date')->nullable();
            $table->time('signing_time')->nullable();
            $table->date('delivery_deadline')->nullable();
            $table->date('expected_delivery_date')->nullable();
            $table->date('actual_delivery_date')->nullable();
            $table->enum('project_status', ['In Progress', 'Delivered', 'On Hold'])->default('In Progress');
            $table->boolean('commission_paid')->default(false);
            $table->timestamps();
            $table->softDeletes();

            // Index for reports
            $table->index(['converted_by', 'conversion_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversions');
    }
};
