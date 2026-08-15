<?php

namespace Database\Factories;

use App\Models\TourCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<TourCategory>
 */
class TourCategoryFactory extends Factory
{
    /**
     * @var class-string<TourCategory>
     */
    protected $model = TourCategory::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => Str::title($name),
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(100, 9999),
        ];
    }
}
