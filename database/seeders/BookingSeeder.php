<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Fleet;
use App\Models\Location;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $existingCount = Booking::query()->count();
        $targetCount = 10;

        if ($existingCount >= $targetCount) {
            return;
        }

        $this->ensureDependencies();

        $locationIds = Location::query()->pluck('id')->all();
        $fleetIds = Fleet::query()->pluck('id')->all();
        $serviceIds = Service::query()->pluck('id')->all();

        $toCreate = $targetCount - $existingCount;

        for ($i = 0; $i < $toCreate; $i++) {
            $pickupLocation = Location::query()->inRandomOrder()->first();
            $dropoffLocation = Location::query()->inRandomOrder()->first();

            if (! $pickupLocation || ! $dropoffLocation) {
                continue;
            }

            if ($pickupLocation->id === $dropoffLocation->id && count($locationIds) > 1) {
                $alternateIds = array_values(array_filter($locationIds, fn ($id) => $id !== $pickupLocation->id));
                $dropoffLocation = Location::query()->whereKey(fake()->randomElement($alternateIds))->first() ?? $dropoffLocation;
            }

            $isAirportTransfer = $pickupLocation->type === 'airport' || $dropoffLocation->type === 'airport';

            $name = fake()->name();

            Booking::create([
                'name' => $name,
                'email' => fake()->safeEmail(),
                'contact' => fake()->phoneNumber(),
                'pickup_location_id' => $pickupLocation->id,
                'dropoff_location_id' => $dropoffLocation->id,
                'pickup_date' => fake()->dateTimeBetween('now', '+45 days')->format('Y-m-d'),
                'pickup_time' => fake()->time('H:i:s'),
                'is_return_trip' => fake()->boolean(35),
                'passenger_count' => fake()->numberBetween(1, 8),
                'luggage_count' => fake()->numberBetween(0, 6),
                'fleet_id' => fake()->randomElement($fleetIds),
                'service_id' => fake()->randomElement($serviceIds),
                'status' => fake()->randomElement(Booking::STATUSES),
                'flight_number' => $isAirportTransfer ? strtoupper(fake()->bothify('??###')) : null,
                'special_requests' => fake()->boolean(45) ? fake()->sentence(10) : null,
            ]);
        }
    }

    private function ensureDependencies(): void
    {
        if (Location::query()->count() === 0) {
            $typeKeys = array_keys(Location::typeOptions());

            for ($i = 1; $i <= 12; $i++) {
                $name = fake()->city() . ' Location ' . $i;

                Location::create([
                    'name' => $name,
                    'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(100, 9999),
                    'type' => fake()->randomElement($typeKeys),
                    'is_active' => true,
                ]);
            }
        }

        if (Fleet::query()->count() === 0) {
            Fleet::factory()->count(8)->create();
        }

        if (Service::query()->count() === 0) {
            Service::factory()->count(8)->create();
        }
    }
}
