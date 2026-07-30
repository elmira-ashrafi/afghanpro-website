<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductAttribute>
 */
class ProductAttributeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Predefined attribute types and their possible values
        $attributes = [
            'size' => ['Small', 'Medium', 'Large', 'X-Large', 'XX-Large'],
            'color' => ['Red', 'Blue', 'Green', 'Black', 'White', 'Yellow', 'Purple', 'Orange', 'Brown'],
            'material' => ['Cotton', 'Leather', 'Polyester', 'Wool', 'Silk', 'Nylon', 'Metal', 'Wood', 'Plastic'],
            'style' => ['Casual', 'Formal', 'Sports', 'Vintage', 'Modern', 'Traditional'],
            'weight' => ['Light', 'Medium', 'Heavy'],
            'capacity' => ['8GB', '16GB', '32GB', '64GB', '128GB', '256GB', '512GB', '1TB'],
            'screen_size' => ['5"', '5.5"', '6"', '6.5"', '7"', '10"', '13"', '15"', '17"', '21"', '24"', '27"', '32"'],
            'processor' => ['Core i3', 'Core i5', 'Core i7', 'Core i9', 'Ryzen 3', 'Ryzen 5', 'Ryzen 7', 'Ryzen 9'],
        ];
        
        $attributeName = $this->faker->randomElement(array_keys($attributes));
        $attributeValues = $attributes[$attributeName];
        
        // Take a random subset of values (at least 2)
        $valueCount = min(count($attributeValues), max(2, rand(2, 5)));
        $selectedValues = $this->faker->randomElements($attributeValues, $valueCount);
        
        return [
            'product_id' => Product::factory(),
            'name' => $attributeName,
            'values' => $selectedValues,
        ];
    }
} 