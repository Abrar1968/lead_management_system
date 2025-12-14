<?php

namespace Database\Factories;

use App\Models\ExtraCommission;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExtraCommission>
 */
class ExtraCommissionFactory extends Factory
{
    protected $model = ExtraCommission::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'commission_type' => $this->faker->randomElement(['Bonus', 'Incentive', 'Target Achievement', 'Referral', 'Other']),
            'amount' => $this->faker->randomFloat(2, 500, 10000),
            'description' => $this->faker->sentence(),
            'date_earned' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'status' => $this->faker->randomElement(['Pending', 'Approved', 'Paid']),
        ];
    }

    /**
     * Indicate the commission is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Pending',
        ]);
    }

    /**
     * Indicate the commission is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Approved',
            'approved_by' => User::factory(),
        ]);
    }

    /**
     * Indicate the commission is paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Paid',
            'approved_by' => User::factory(),
        ]);
    }
}
