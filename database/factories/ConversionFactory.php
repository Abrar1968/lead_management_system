<?php

namespace Database\Factories;

use App\Models\Conversion;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Conversion>
 */
class ConversionFactory extends Factory
{
    protected $model = Conversion::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dealValue = $this->faker->randomFloat(2, 5000, 100000);
        $commissionRate = $this->faker->randomFloat(2, 500, 1000);
        $commissionType = $this->faker->randomElement(['fixed', 'percentage']);

        if ($commissionType === 'fixed') {
            $commissionAmount = $commissionRate;
        } else {
            $commissionRate = $this->faker->randomFloat(2, 5, 15);
            $commissionAmount = ($dealValue * $commissionRate) / 100;
        }

        return [
            'lead_id' => Lead::factory()->converted(),
            'converted_by' => User::factory(),
            'conversion_date' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'deal_value' => $dealValue,
            'commission_rate_used' => $commissionRate,
            'commission_type_used' => $commissionType,
            'commission_amount' => $commissionAmount,
            'package_plan' => $this->faker->randomElement(['Basic', 'Standard', 'Premium', 'Enterprise']),
            'advance_paid' => $this->faker->boolean(70),
            'payment_method' => $this->faker->randomElement(['Cash', 'Bank Transfer', 'bKash', 'Nagad']),
            'project_status' => $this->faker->randomElement(['In Progress', 'Delivered']),
            'commission_paid' => $this->faker->boolean(50),
        ];
    }

    /**
     * Indicate the commission is paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'commission_paid' => true,
        ]);
    }

    /**
     * Indicate the commission is unpaid.
     */
    public function unpaid(): static
    {
        return $this->state(fn (array $attributes) => [
            'commission_paid' => false,
        ]);
    }
}
