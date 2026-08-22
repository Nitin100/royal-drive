<?php

namespace Tests\Feature;

use App\Models\Slider;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SliderModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_slider(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('admin.sliders.store'), [
                'title' => 'Luxury Adventures',
                'subtitle' => 'Best experiences in Oman',
                'description' => 'Travel across iconic desert routes with premium comfort.',
                'is_active' => true,
            ])
            ->assertRedirect(route('admin.sliders.index'));

        $this->assertDatabaseHas('sliders', [
            'title' => 'Luxury Adventures',
            'subtitle' => 'Best experiences in Oman',
            'is_active' => true,
        ]);
    }

    public function test_active_slider_is_rendered_on_homepage(): void
    {
        Slider::create([
            'title' => 'Seamless Travel',
            'subtitle' => 'Ride with confidence',
            'description' => 'From city trips to desert escapes.',
            'is_active' => true,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Seamless Travel')
            ->assertSee('Ride with confidence');
    }
}
