<?php

namespace Database\Factories;

use App\Models\CommissionType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CommissionType>
 */
class CommissionTypeFactory extends Factory
{
    protected $model = CommissionType::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->randomElement([
            'Sales Commission',
            'Referral Commission',
            'Team Lead Bonus',
            'Performance Bonus',
            'New Client Commission',
            'Upsell Commission',
            'Renewal Commission',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->sentence(),
            'calculation_type' => $this->faker->randomElement(['fixed', 'percentage']),
            'default_rate' => $this->faker->randomFloat(2, 100, 1000),
            'is_active' => true,
            'is_default' => false,
        ];
    }

    /**
     * Fixed commission type
     */
    public function fixed(float $rate = 500): static
    {
        return $this->state(fn () => [
            'calculation_type' => 'fixed',
            'default_rate' => $rate,
        ]);
    }

    /**
     * Percentage commission type
     */
    public function percentage(float $rate = 10): static
    {
        return $this->state(fn () => [
            'calculation_type' => 'percentage',
            'default_rate' => $rate,
        ]);
    }

    /**
     * Default commission type (assigned to new users)
     */
    public function default(): static
    {
        return $this->state(fn () => [
            'is_default' => true,
        ]);
    }

    /**
     * Inactive commission type
     */
    public function inactive(): static
    {
        return $this->state(fn () => [
            'is_active' => false,
        ]);
    }
}
