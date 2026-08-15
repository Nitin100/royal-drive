<?php

namespace Database\Factories;

use App\Models\TourTag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<TourTag>
 */
class TourTagFactory extends Factory
{
    /**
     * @var class-string<TourTag>
     */
    protected $model = TourTag::class;

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
