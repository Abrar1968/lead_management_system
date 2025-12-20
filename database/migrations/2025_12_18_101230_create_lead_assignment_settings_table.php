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
        Schema::create('lead_assignment_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('value');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('lead_assignment_settings')->insert([
            [
                'key' => 'scoring_weights',
                'value' => json_encode([
                    'conversion_rate' => 30,
                    'response_rate' => 20,
                    'follow_up_rate' => 15,
                    'avg_deal_value' => 15,
                    'workload_balance' => 20,
                ]),
                'description' => 'Weight percentages for performance score calculation (must sum to 100)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'max_active_leads',
                'value' => json_encode(20),
                'description' => 'Maximum active leads per sales person before workload penalty',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'assignment_mode',
                'value' => json_encode('balanced'),
                'description' => 'Assignment mode: performance (best performer), balanced (score + workload), round_robin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'auto_assign_enabled',
                'value' => json_encode(false),
                'description' => 'Whether to automatically assign leads based on smart suggestions',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'calculation_period',
                'value' => json_encode('monthly'),
                'description' => 'Period for performance calculations: daily, weekly, monthly',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_assignment_settings');
    }
};
