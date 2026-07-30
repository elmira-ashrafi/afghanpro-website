<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductCategory>
 */
class ProductCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->words(rand(1, 2), true);
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph,
            'icon' => 'fa-' . $this->faker->randomElement(['shopping-cart', 'laptop', 'mobile-alt', 'tshirt', 'shoe-prints', 'gem', 'book', 'utensils', 'home', 'tools']),
            'parent_id' => null,
            'sort_order' => $this->faker->numberBetween(0, 100),
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the category is a child category.
     */
    public function child(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'parent_id' => \App\Models\ProductCategory::inRandomOrder()->first()?->id,
            ];
        });
    }
} 