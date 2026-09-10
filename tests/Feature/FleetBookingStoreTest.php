<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Fleet;
use App\Models\Location;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FleetBookingStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_fleet_booking_form_is_saved_to_bookings_table_and_redirects_to_success_page(): void
    {
        $pickup = Location::factory()->create(['name' => 'Heathrow Airport', 'type' => 'airport']);
        $dropoff = Location::factory()->create(['name' => 'Mayfair', 'type' => 'city']);
        $fleet = Fleet::factory()->create(['slug' => 'mercedes-s-class', 'name' => 'Mercedes S-Class']);
        $service = Service::factory()->create(['slug' => 'airport-transfer', 'title' => 'Airport Transfer']);

        $response = $this->from('/')->post(route('booking.store'), [
            'pickup_location' => 'Heathrow Airport',
            'dropoff_location' => 'Mayfair',
            'pickup_date' => '2026-09-15',
            'pickup_time' => '14:30',
            'return_trip' => '1',
            'return_date' => '2026-09-17',
            'return_time' => '18:00',
            'passengers' => '2',
            'luggage' => '2',
            'vehicle' => 'mercedes-s-class',
            'service_type' => 'airport-transfer',
            'flight_number' => 'EK417',
            'special_requirements' => 'Child seat requested',
        ]);

        $response->assertRedirectRegex('/booking-success\/.+$/');

        $booking = Booking::query()->first();

        $this->assertNotNull($booking);
        $this->assertMatchesRegularExpression('/^RD-\d{8}-\d{4}$/', $booking->booking_number);
        $this->assertSame($pickup->id, $booking->pickup_location_id);
        $this->assertSame($dropoff->id, $booking->dropoff_location_id);
        $this->assertStringStartsWith('2026-09-15', (string) $booking->pickup_date);
        $this->assertSame('14:30', (string) $booking->pickup_time);
        $this->assertSame('2026-09-17', (string) $booking->return_date);
        $this->assertSame('18:00', (string) $booking->return_time);
        $this->assertTrue((bool) $booking->is_return_trip);
        $this->assertSame(2, $booking->passenger_count);
        $this->assertSame(2, $booking->luggage_count);
        $this->assertSame($fleet->id, $booking->fleet_id);
        $this->assertSame($service->id, $booking->service_id);
        $this->assertSame('EK417', $booking->flight_number);
        $this->assertSame('Child seat requested', $booking->special_requests);
        $this->assertSame(1, Booking::query()->count());

        $this->get(route('booking.success', ['bookingNumber' => $booking->booking_number]))
            ->assertOk()
            ->assertSee($booking->booking_number);
    }
}
