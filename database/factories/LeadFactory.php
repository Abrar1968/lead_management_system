<?php

namespace Database\Factories;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lead>
 */
class LeadFactory extends Factory
{
    protected $model = Lead::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = $this->faker->dateTimeBetween('-30 days', 'now');
        $dateStr = $date->format('Ymd');

        return [
            'lead_number' => 'LEAD-'.$dateStr.'-'.str_pad($this->faker->unique()->numberBetween(1, 999), 3, '0', STR_PAD_LEFT),
            'source' => $this->faker->randomElement(['WhatsApp', 'Messenger', 'Website']),
            'client_name' => $this->faker->name(),
            'phone_number' => $this->faker->numerify('01#########'),
            'email' => $this->faker->optional()->safeEmail(),
            'company_name' => $this->faker->optional()->company(),
            'service_interested' => $this->faker->randomElement(['Website', 'Software', 'CRM', 'Marketing']),
            'lead_date' => $date,
            'lead_time' => $this->faker->time('H:i'),
            'is_repeat_lead' => false,
            'previous_lead_ids' => null,
            'priority' => $this->faker->randomElement(['High', 'Medium', 'Low']),
            'status' => $this->faker->randomElement(['New', 'Contacted', 'Qualified', 'Converted', 'Lost']),
            'assigned_to' => User::factory(),
        ];
    }

    /**
     * Indicate the lead is a repeat lead.
     */
    public function repeat(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_repeat_lead' => true,
        ]);
    }

    /**
     * Indicate the lead is converted.
     */
    public function converted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Converted',
        ]);
    }

    /**
     * Indicate the lead is new status.
     */
    public function newStatus(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'New',
        ]);
    }

    /**
     * Indicate the lead has high priority.
     */
    public function highPriority(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'High',
        ]);
    }
}
