<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    /**
     * @var class-string<Service>
     */
    protected $model = Service::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);

        return [
            'title' => Str::title($title),
            'slug' => Str::slug($title) . '-' . fake()->unique()->numberBetween(100, 9999),
            'meta_title' => Str::limit(Str::title($title) . ' Service | Royal Drive', 255, ''),
            'meta_description' => fake()->sentence(16),
            'banner_image_path' => 'services/banners/sample-' . fake()->numberBetween(1, 20) . '.jpg',
            'description' => '<p>' . fake()->paragraph(4) . '</p>',
            'pricing_config' => [
                ['label' => 'Base', 'amount' => fake()->numberBetween(75, 200), 'currency' => 'GBP'],
                ['label' => 'Premium', 'amount' => fake()->numberBetween(201, 450), 'currency' => 'GBP'],
            ],
        ];
    }
}
