<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->words(3, true);
        
        return [
            'name' => ucwords($name),
            'slug' => \Illuminate\Support\Str::slug($name),
            'description' => fake()->paragraph(4),
            'price' => fake()->randomFloat(2, 10, 1000),
            'stock_quantity' => fake()->numberBetween(0, 100),
            'sku' => fake()->unique()->bothify('SKU-##??##'),
            'images' => [
                fake()->imageUrl(640, 480, 'technics'),
                fake()->imageUrl(640, 480, 'technics'),
                fake()->imageUrl(640, 480, 'technics'),
            ],
            'category_id' => \App\Models\Category::factory(),
            'is_active' => fake()->boolean(90), // 90% chance of being active
        ];
    }
}
