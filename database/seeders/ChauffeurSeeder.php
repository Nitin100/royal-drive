<?php

namespace Database\Seeders;

use App\Models\Chauffeur;
use App\Models\ChauffeurAssignment;
use Illuminate\Database\Seeder;

class ChauffeurSeeder extends Seeder
{
    public function run(): void
    {
        Chauffeur::factory()->count(20)->create()->each(function (Chauffeur $chauffeur): void {
            $assignmentsCount = random_int(1, 4);

            for ($i = 0; $i < $assignmentsCount; $i++) {
                ChauffeurAssignment::factory()->create([
                    'chauffeur_id' => $chauffeur->id,
                ]);
            }
        });
    }
}
