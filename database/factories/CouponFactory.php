<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coupon>
 */
class CouponFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $discountType = $this->faker->randomElement(['percentage', 'fixed']);
        $discountValue = $discountType === 'percentage' 
            ? $this->faker->numberBetween(5, 30) 
            : $this->faker->numberBetween(100, 1000);
        
        return [
            'code' => 'DISC' . Str::upper(Str::random(6)),
            'discount_type' => $discountType,
            'discount_value' => $discountValue,
            'min_order_amount' => $this->faker->numberBetween(500, 2000),
            'max_discount_amount' => $discountType === 'percentage' ? $this->faker->numberBetween(500, 2000) : null,
            'starts_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'expires_at' => $this->faker->dateTimeBetween('now', '+3 months'),
            'usage_limit' => $this->faker->numberBetween(10, 100),
            'usage_count' => 0,
            'max_uses_per_user' => $this->faker->numberBetween(1, 3),
            'is_active' => true,
        ];
    }
} 