<?php

namespace Database\Factories;

use App\Models\Chauffeur;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Chauffeur>
 */
class ChauffeurFactory extends Factory
{
    /**
     * @var class-string<Chauffeur>
     */
    protected $model = Chauffeur::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->name();

        return [
            'name' => $name,
            'contact_number' => fake()->phoneNumber(),
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(100, 9999),
            'photo_path' => 'chauffeurs/photos/sample-' . fake()->numberBetween(1, 20) . '.jpg',
            'license_number' => strtoupper(Str::random(10)),
            'license_expiry_date' => fake()->dateTimeBetween('+6 months', '+6 years')->format('Y-m-d'),
            'experience_years' => fake()->numberBetween(1, 25),
            'languages_spoken' => fake()->randomElement(['English', 'English, French', 'English, Hindi', 'English, Arabic']),
            'rating' => fake()->randomFloat(2, 3.5, 5),
            'availability_status' => fake()->randomElement(['available', 'unavailable', 'on-trip', 'resting']),
            'meta_title' => Str::limit($name . ' Chauffeur | Royal Drive', 255, ''),
            'meta_description' => fake()->sentence(14),
            'bio' => '<p>' . fake()->paragraph(3) . '</p>',
            'availability_calendar' => [
                ['date' => now()->toDateString(), 'status' => 'available'],
                ['date' => now()->addDay()->toDateString(), 'status' => fake()->randomElement(['available', 'booked'])],
            ],
        ];
    }
}
