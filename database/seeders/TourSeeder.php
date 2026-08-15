<?php

namespace Database\Seeders;

use App\Models\Tour;
use App\Models\TourCategory;
use App\Models\TourTag;
use Illuminate\Database\Seeder;

class TourSeeder extends Seeder
{
    public function run(): void
    {
        $categories = TourCategory::factory()->count(8)->create();
        $tags = TourTag::factory()->count(12)->create();

        Tour::factory()->count(20)->create()->each(function (Tour $tour) use ($categories, $tags): void {
            $tour->categories()->sync(
                $categories->random(random_int(1, min(3, $categories->count())))->pluck('id')->all()
            );

            $tour->tags()->sync(
                $tags->random(random_int(2, min(5, $tags->count())))->pluck('id')->all()
            );
        });
    }
}
