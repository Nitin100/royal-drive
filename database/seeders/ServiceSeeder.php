<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceImage;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        Service::factory()->count(20)->create()->each(function (Service $service): void {
            $imagesCount = random_int(2, 5);

            for ($i = 0; $i < $imagesCount; $i++) {
                ServiceImage::factory()->create([
                    'service_id' => $service->id,
                    'sort_order' => $i,
                ]);
            }
        });
    }
}
