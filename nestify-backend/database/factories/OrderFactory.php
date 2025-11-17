<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_number' => 'ORD-' . fake()->unique()->numberBetween(10000, 99999),
            'user_id' => \App\Models\User::factory(),
            'total_amount' => fake()->randomFloat(2, 20, 500),
            'status' => fake()->randomElement(['pending', 'processing', 'shipped', 'delivered', 'cancelled']),
            'shipping_address' => [
                'name' => fake()->name(),
                'street' => fake()->streetAddress(),
                'city' => fake()->city(),
                'state' => fake()->state(),
                'zip' => fake()->postcode(),
                'country' => fake()->country(),
            ],
            'billing_address' => [
                'name' => fake()->name(),
                'street' => fake()->streetAddress(),
                'city' => fake()->city(),
                'state' => fake()->state(),
                'zip' => fake()->postcode(),
                'country' => fake()->country(),
            ],
            'shipped_at' => fake()->optional(0.6)->dateTimeBetween('-30 days', 'now'),
            'delivered_at' => fake()->optional(0.4)->dateTimeBetween('-20 days', 'now'),
        ];
    }
}
