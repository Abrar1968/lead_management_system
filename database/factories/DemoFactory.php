<?php

namespace Database\Factories;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Demo>
 */
class DemoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lead_id' => Lead::factory(),
            'created_by' => User::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'demo_date' => fake()->dateTimeBetween('now', '+1 month'),
            'demo_time' => fake()->time('H:i'),
            'type' => fake()->randomElement(['Online', 'Physical']),
            'status' => 'Scheduled',
            'outcome_notes' => null,
            'meeting_link' => fake()->optional()->url(),
            'location' => fake()->optional()->address(),
        ];
    }

    /**
     * Demo completed state.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Completed',
            'outcome_notes' => fake()->paragraph(),
        ]);
    }

    /**
     * Online demo state.
     */
    public function online(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'Online',
            'meeting_link' => fake()->url(),
            'location' => null,
        ]);
    }

    /**
     * Physical demo state.
     */
    public function physical(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'Physical',
            'meeting_link' => null,
            'location' => fake()->address(),
        ]);
    }
}
