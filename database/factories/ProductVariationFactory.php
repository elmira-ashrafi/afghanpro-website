<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariation>
 */
class ProductVariationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'attributes' => [
                'color' => $this->faker->randomElement(['Red', 'Blue', 'Green', 'Black', 'White']),
                'size' => $this->faker->randomElement(['Small', 'Medium', 'Large', 'X-Large']),
            ],
            'price' => $this->faker->numberBetween(100, 5000),
            'stock' => $this->faker->numberBetween(5, 100),
            'sku' => 'SKU-' . Str::random(8),
        ];
    }
} 