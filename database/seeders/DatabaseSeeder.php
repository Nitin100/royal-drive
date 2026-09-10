<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            PageSeeder::class,
            BlogSeeder::class,
            ServiceSeeder::class,
            AmenitySeeder::class,
            FleetSeeder::class,
            ChauffeurSeeder::class,
            TourSeeder::class,
            BookingSeeder::class,
            EnquirySeeder::class,
        ]);

        User::firstOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
    }
}
