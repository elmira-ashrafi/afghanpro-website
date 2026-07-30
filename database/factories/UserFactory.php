<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => '937' . $this->faker->numerify('########'),
            'telegram_number' => '@' . $this->faker->userName,
            'city' => $this->faker->randomElement(['Kabul', 'Herat', 'Mazar-i-Sharif', 'Kandahar', 'Jalalabad', 'Kunduz', 'Ghazni']),
            'province' => $this->faker->randomElement(['Kabul', 'Herat', 'Balkh', 'Kandahar', 'Nangarhar', 'Kunduz', 'Ghazni']),
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'is_verified' => true,
            'is_admin' => false,
            'is_support' => false,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the user is an admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_admin' => true,
            'is_support' => false,
        ]);
    }

    /**
     * Indicate that the user is support staff.
     */
    public function support(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_admin' => false,
            'is_support' => true,
        ]);
    }
}
