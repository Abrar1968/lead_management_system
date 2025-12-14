<?php

namespace Database\Factories;

use App\Models\FollowUp;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FollowUp>
 */
class FollowUpFactory extends Factory
{
    protected $model = FollowUp::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lead_id' => Lead::factory(),
            'follow_up_date' => $this->faker->dateTimeBetween('now', '+7 days'),
            'follow_up_time' => $this->faker->time('H:i'),
            'notes' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['Pending', 'Completed', 'Cancelled']),
            'created_by' => User::factory(),
        ];
    }

    /**
     * Indicate the follow-up is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Pending',
        ]);
    }

    /**
     * Indicate the follow-up is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Completed',
        ]);
    }
}
