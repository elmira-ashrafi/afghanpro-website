<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Agency>
 */
class AgencyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cities = [
            'Kabul' => ['latitude' => 34.5553, 'longitude' => 69.2075],
            'Herat' => ['latitude' => 34.3530, 'longitude' => 62.2030],
            'Mazar-i-Sharif' => ['latitude' => 36.7090, 'longitude' => 67.1126],
            'Kandahar' => ['latitude' => 31.6237, 'longitude' => 65.7176],
            'Jalalabad' => ['latitude' => 34.4358, 'longitude' => 70.4369],
            'Kunduz' => ['latitude' => 36.7296, 'longitude' => 68.8678],
            'Ghazni' => ['latitude' => 33.5447, 'longitude' => 68.4131],
        ];
        
        $city = $this->faker->randomElement(array_keys($cities));
        
        return [
            'name' => $this->faker->company . ' Exchange',
            'address' => $this->faker->streetAddress,
            'city' => $city,
            'province' => $city, // In Afghanistan, major cities are often their own province
            'phone' => '937' . $this->faker->numerify('########'),
            'email' => $this->faker->unique()->safeEmail,
            'contact_person' => $this->faker->name,
            'is_active' => true,
            'latitude' => $cities[$city]['latitude'] + $this->faker->randomFloat(4, -0.02, 0.02),
            'longitude' => $cities[$city]['longitude'] + $this->faker->randomFloat(4, -0.02, 0.02),
            'working_hours' => json_encode([
                'monday' => ['open' => '08:00', 'close' => '17:00'],
                'tuesday' => ['open' => '08:00', 'close' => '17:00'],
                'wednesday' => ['open' => '08:00', 'close' => '17:00'],
                'thursday' => ['open' => '08:00', 'close' => '17:00'],
                'friday' => ['open' => '08:00', 'close' => '13:00'],
                'saturday' => ['open' => '08:00', 'close' => '17:00'],
                'sunday' => ['open' => '08:00', 'close' => '17:00'],
            ]),
        ];
    }
} 