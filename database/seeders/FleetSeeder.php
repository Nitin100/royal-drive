<?php

namespace Database\Seeders;

use App\Models\Fleet;
use App\Models\FleetImage;
use Illuminate\Database\Seeder;

class FleetSeeder extends Seeder
{
    public function run(): void
    {
        Fleet::factory()->count(20)->create()->each(function (Fleet $fleet): void {
            $imagesCount = random_int(2, 6);

            for ($i = 0; $i < $imagesCount; $i++) {
                FleetImage::factory()->create([
                    'fleet_id' => $fleet->id,
                    'sort_order' => $i,
                ]);
            }
        });
    }
}
