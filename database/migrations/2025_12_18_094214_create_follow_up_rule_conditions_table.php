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
        Schema::create('follow_up_rule_conditions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rule_id')->constrained('follow_up_rules')->cascadeOnDelete();
            $table->string('field');
            $table->enum('operator', [
                'equals',
                'not_equals',
                'greater_than',
                'less_than',
                'between',
                'in',
                'not_in',
                'is_null',
                'is_not_null',
            ]);
            $table->json('value')->nullable();
            $table->timestamps();

            $table->index('rule_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follow_up_rule_conditions');
    }
};
