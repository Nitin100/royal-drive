<?php

namespace Tests\Feature;

use App\Models\PromotionPopup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PromotionPopupModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_promotion_popup(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('admin.promotion-popups.store'), [
                'title' => 'Spring Special',
                'subtitle' => 'Book today and save',
                'body' => 'Enjoy a limited offer this month.',
                'button_label' => 'Reserve Now',
                'button_url' => 'https://example.com/offers',
                'is_active' => true,
            ])
            ->assertRedirect(route('admin.promotion-popups.index'));

        $this->assertDatabaseHas('promotion_popups', [
            'title' => 'Spring Special',
            'button_label' => 'Reserve Now',
            'is_active' => true,
        ]);
    }

    public function test_active_promotion_popup_is_rendered_on_homepage(): void
    {
        PromotionPopup::create([
            'title' => 'Weekend Escape',
            'subtitle' => 'Luxury rides at special prices',
            'body' => 'Take advantage of this limited-time offer.',
            'button_label' => 'Book A Ride',
            'button_url' => '/services',
            'is_active' => true,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Weekend Escape')
            ->assertSee('Book A Ride');
    }
}
