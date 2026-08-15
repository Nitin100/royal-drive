<?php

namespace Database\Factories;

use App\Models\Fleet;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Fleet>
 */
class FleetFactory extends Factory
{
    /**
     * @var class-string<Fleet>
     */
    protected $model = Fleet::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->company() . ' ' . fake()->randomElement(['Sedan', 'SUV', 'Van', 'Coach']);

        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(100, 9999),
            'category' => fake()->randomElement(['Economy', 'Business', 'Executive', 'Luxury', 'SUV', 'Van', 'Coach']),
            'brand' => fake()->randomElement(['Mercedes', 'BMW', 'Audi', 'Tesla', 'Toyota', 'Volvo', 'Lexus']),
            'passenger_capacity' => fake()->numberBetween(2, 40),
            'luggage_capacity' => fake()->numberBetween(1, 16),
            'availability_status' => fake()->randomElement(['available', 'unavailable', 'maintenance', 'booked']),
            'meta_title' => Str::limit($name . ' Fleet | Royal Drive', 255, ''),
            'meta_description' => fake()->sentence(15),
            'banner_image_path' => 'fleets/banners/sample-' . fake()->numberBetween(1, 20) . '.jpg',
            'description' => '<p>' . fake()->paragraph(3) . '</p>',
            'pricing_config' => [
                ['label' => 'Hourly', 'amount' => fake()->numberBetween(45, 120), 'currency' => 'GBP'],
                ['label' => 'Airport', 'amount' => fake()->numberBetween(90, 260), 'currency' => 'GBP'],
            ],
        ];
    }
}
