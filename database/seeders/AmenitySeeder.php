<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    public function run(): void
    {
        $amenities = [
            'Air Conditioning',
            'Bluetooth',
            'Child Seat',
            'GPS Navigation',
            'Leather Seats',
            'Wi-Fi',
            'USB Charging',
            'Sunroof',
            'Premium Audio',
            'Automatic Transmission',
            '24/7 Support',
            'Airport Pickup',
            'Pet Friendly',
            'Luggage Space',
            'Snow Tire',
        ];

        foreach ($amenities as $name) {
            Amenity::firstOrCreate(
                ['slug' => str($name)->slug()->toString()],
                ['name' => $name]
            );
        }
    }
}
