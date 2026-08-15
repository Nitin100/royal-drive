<?php

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    /**
     * @var class-string<Page>
     */
    protected $model = Page::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(4);

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . fake()->unique()->numberBetween(100, 9999),
            'meta_title' => Str::limit($title . ' | Royal Drive', 255, ''),
            'meta_description' => fake()->sentence(18),
            'banner_image_path' => 'pages/banners/sample-' . fake()->numberBetween(1, 20) . '.jpg',
            'content' => '<p>' . fake()->paragraph(5) . '</p><p>' . fake()->paragraph(4) . '</p>',
        ];
    }
}
