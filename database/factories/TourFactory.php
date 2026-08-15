<?php

namespace Database\Factories;

use App\Models\Tour;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Tour>
 */
class TourFactory extends Factory
{
    /**
     * @var class-string<Tour>
     */
    protected $model = Tour::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(4);

        return [
            'title' => Str::title($title),
            'slug' => Str::slug($title) . '-' . fake()->unique()->numberBetween(100, 9999),
            'meta_title' => Str::limit(Str::title($title) . ' | Royal Drive', 255, ''),
            'meta_description' => fake()->sentence(16),
            'featured_image_path' => 'tours/featured/sample-' . fake()->numberBetween(1, 20) . '.jpg',
            'content' => '<p>' . fake()->paragraph(4) . '</p><p>' . fake()->paragraph(3) . '</p>',
            'is_featured' => fake()->boolean(30),
        ];
    }
}
