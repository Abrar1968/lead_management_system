<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Website', 'Software', 'CRM', 'Marketing', 'SEO', 'Design']),
            'description' => $this->faker->sentence(),
            'is_active' => true,
            'display_order' => $this->faker->numberBetween(1, 10),
        ];
    }

    /**
     * Create an inactive service.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
