<?php

namespace Database\Factories;

use App\Models\Chauffeur;
use App\Models\ChauffeurAssignment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ChauffeurAssignment>
 */
class ChauffeurAssignmentFactory extends Factory
{
    /**
     * @var class-string<ChauffeurAssignment>
     */
    protected $model = ChauffeurAssignment::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['assigned', 'completed', 'cancelled'];

        return [
            'chauffeur_id' => Chauffeur::factory(),
            'booking_reference' => 'BK-' . strtoupper(fake()->bothify('??####')),
            'assigned_at' => fake()->dateTimeBetween('-90 days', 'now'),
            'service_date' => fake()->dateTimeBetween('-60 days', '+30 days')->format('Y-m-d'),
            'pickup_time' => fake()->time('H:i:s'),
            'route_name' => fake()->city() . ' - ' . fake()->city(),
            'distance_km' => fake()->randomFloat(2, 5, 450),
            'status' => fake()->randomElement($statuses),
            'notes' => fake()->optional()->sentence(10),
        ];
    }
}
