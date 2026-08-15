<?php

namespace Database\Factories;

use App\Models\Fleet;
use App\Models\FleetImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FleetImage>
 */
class FleetImageFactory extends Factory
{
    /**
     * @var class-string<FleetImage>
     */
    protected $model = FleetImage::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fleet_id' => Fleet::factory(),
            'image_path' => 'fleets/gallery/sample-' . fake()->numberBetween(1, 30) . '.jpg',
            'sort_order' => fake()->numberBetween(0, 8),
        ];
    }
}
