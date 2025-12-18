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
        Schema::create('field_definitions', function (Blueprint $table) {
            $table->id();
            $table->string('model_type'); // 'client' or 'demo'
            $table->string('name'); // field key (snake_case)
            $table->string('label'); // Display label
            $table->enum('type', ['text', 'image', 'link'])->default('text');
            $table->boolean('required')->default(false);
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['model_type', 'is_active']);
        });

        // Create dynamic field values table
        Schema::create('field_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('field_definition_id')->constrained()->cascadeOnDelete();
            $table->morphs('fieldable'); // fieldable_id, fieldable_type
            $table->text('value')->nullable();
            $table->timestamps();

            $table->unique(['field_definition_id', 'fieldable_id', 'fieldable_type'], 'field_values_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('field_values');
        Schema::dropIfExists('field_definitions');
    }
};
