<?php

namespace Tests\Feature;

use App\Models\Fleet;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeServicesDynamicTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_displays_services_from_database(): void
    {
        $service = Service::factory()->create([
            'title' => 'Airport Transfers',
            'slug' => 'airport-transfers',
            'description' => '<p>Private pickups for every arrival.</p>',
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSeeText('Airport Transfers');
        $response->assertSee('data-service-modal', false);
    }

    public function test_homepage_displays_top_three_fleets_from_database(): void
    {
        $fleetA = Fleet::factory()->create([
            'name' => 'Mercedes S-Class',
            'slug' => 'mercedes-s-class',
            'passenger_capacity' => 4,
            'luggage_capacity' => 3,
            'pricing_config' => [['label' => 'Hourly', 'amount' => 65, 'currency' => 'USD']],
        ]);

        $fleetB = Fleet::factory()->create([
            'name' => 'BMW 7 Series',
            'slug' => 'bmw-7-series',
            'passenger_capacity' => 4,
            'luggage_capacity' => 3,
            'pricing_config' => [['label' => 'Hourly', 'amount' => 75, 'currency' => 'USD']],
        ]);

        $fleetC = Fleet::factory()->create([
            'name' => 'Rolls Royce Ghost',
            'slug' => 'rolls-royce-ghost',
            'passenger_capacity' => 4,
            'luggage_capacity' => 2,
            'pricing_config' => [['label' => 'Hourly', 'amount' => 220, 'currency' => 'USD']],
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSeeText('Mercedes S-Class');
        $response->assertSeeText('BMW 7 Series');
        $response->assertSeeText('Rolls Royce Ghost');
    }

    public function test_homepage_fleet_details_endpoint_returns_json(): void
    {
        $fleet = Fleet::factory()->create([
            'name' => 'BMW 7 Series',
            'slug' => 'bmw-7-series',
            'passenger_capacity' => 4,
            'luggage_capacity' => 3,
            'pricing_config' => [['label' => 'Hourly', 'amount' => 75, 'currency' => 'USD']],
        ]);

        $response = $this->getJson(route('fleet.details', ['fleet' => $fleet->slug]));

        $response->assertOk();
        $response->assertJsonPath('name', 'BMW 7 Series');
        $response->assertJsonPath('price', 75);
    }
}
