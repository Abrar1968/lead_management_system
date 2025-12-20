<?php

namespace Database\Factories;

use App\Models\Lead;
use App\Models\LeadContact;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LeadContact>
 */
class LeadContactFactory extends Factory
{
    protected $model = LeadContact::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lead_id' => Lead::factory(),
            'daily_call_made' => true,
            'call_date' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'call_time' => $this->faker->time('H:i'),
            'caller_id' => User::factory(),
            'response_status' => $this->faker->randomElement(['Interested', '50%', 'Yes', 'Call Later', 'No Response', 'No', 'Phone off']),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
