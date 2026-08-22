<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AmenityModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_amenity(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('admin.amenities.store'), [
                'name' => 'Wi-Fi',
                'slug' => 'wi-fi',
            ])
            ->assertRedirect(route('admin.amenities.index'));

        $this->assertDatabaseHas('amenities', [
            'name' => 'Wi-Fi',
            'slug' => 'wi-fi',
        ]);
    }
}
