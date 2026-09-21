<?php

namespace Tests\Feature;

use App\Models\Fleet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FleetSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_results_page_lists_matching_fleets(): void
    {
        Fleet::factory()->create([
            'name' => 'Executive Sedan',
            'slug' => 'executive-sedan',
            'passenger_capacity' => 4,
            'luggage_capacity' => 3,
            'pricing_config' => [['label' => 'Hourly', 'price' => 65, 'currency' => 'OMR']],
        ]);

        Fleet::factory()->create([
            'name' => 'Luxury SUV',
            'slug' => 'luxury-suv',
            'passenger_capacity' => 7,
            'luggage_capacity' => 5,
            'pricing_config' => [['label' => 'Hourly', 'price' => 120, 'currency' => 'OMR']],
        ]);

        $response = $this->get('/fleet-search?pickup_location=London&dropoff_location=Heathrow&passengers=5');

        $response->assertOk();
        $response->assertSee('Luxury SUV');
        $response->assertDontSee('Executive Sedan');
    }
}
