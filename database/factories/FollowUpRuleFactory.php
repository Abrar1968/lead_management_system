<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FollowUpRule>
 */
class FollowUpRuleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->words(3, true).' Rule',
            'description' => $this->faker->sentence(),
            'priority' => $this->faker->numberBetween(0, 10),
            'is_active' => true,
            'logic_type' => $this->faker->randomElement(['AND', 'OR']),
        ];
    }

    /**
     * Create a global rule (no user).
     */
    public function global(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => null,
        ]);
    }

    /**
     * Create an inactive rule.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
