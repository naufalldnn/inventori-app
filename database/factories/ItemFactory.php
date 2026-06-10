<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'code' => 'BRG-'.fake()->unique()->numberBetween(1000, 9999),
            'name' => fake()->words(3, true),
            'unit' => fake()->randomElement(['pcs', 'box', 'unit', 'pack']),
            'stock' => fake()->numberBetween(0, 75),
            'minimum_stock' => fake()->numberBetween(5, 15),
            'description' => fake()->sentence(),
        ];
    }
}
