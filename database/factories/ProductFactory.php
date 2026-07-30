<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $name = $this->faker->unique()->words(rand(2, 4), true);
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'short_description' => $this->faker->sentence,
            'description' => $this->faker->paragraphs(3, true),
            'thumbnail' => 'products/' . $this->faker->numberBetween(1, 20) . '.jpg',
            'is_variable' => $this->faker->boolean(70), // 70% chance of being variable
            'status' => 'active',
        ];
    }

    /**
     * Indicate that the product is simple (not variable).
     */
    public function simple(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_variable' => false,
        ]);
    }

    /**
     * Indicate that the product is variable.
     */
    public function variable(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_variable' => true,
        ]);
    }
} 