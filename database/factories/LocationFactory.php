<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Location>
 */
class LocationFactory extends Factory
{
    protected $model = Location::class;

    public function definition(): array
    {
        $name = fake()->city() . ' ' . fake()->randomElement(['Airport', 'Hotel', 'Center', 'Station']);

        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(100, 9999),
            'type' => fake()->randomElement(array_keys(Location::typeOptions())),
            'is_active' => true,
        ];
    }
}
