<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\ServiceImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ServiceImage>
 */
class ServiceImageFactory extends Factory
{
    /**
     * @var class-string<ServiceImage>
     */
    protected $model = ServiceImage::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'service_id' => Service::factory(),
            'image_path' => 'services/gallery/sample-' . fake()->numberBetween(1, 30) . '.jpg',
            'sort_order' => fake()->numberBetween(0, 8),
        ];
    }
}
