<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListingContactsTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_page_displays_contacts(): void
    {
        $listing = Listing::factory()->active()->create();
        $listing->contacts()->create([
            'type'       => 'phone',
            'value'      => '+375291234567',
            'is_public'  => true,
        ]);
        $listing->contacts()->create([
            'type'       => 'telegram',
            'value'      => '@seller',
            'is_public'  => true,
        ]);

        $response = $this->get(route('listings.show', $listing->slug));

        $response->assertStatus(200);
        $response->assertSee('+375291234567');
        $response->assertSee('@seller');
    }

    public function test_authenticated_user_can_store_contacts(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $this->actingAs($user)
            ->post(route('my-listings.store'), [
                'type'             => 'sell_business',
                'title'            => 'Test Business With Contacts',
                'description'      => 'This is a test business description that is long enough.',
                'price_on_request' => true,
                'currency'         => 'BYN',
                'category_id'      => $category->id,
                'contacts'         => [
                    'phone'    => '+375299999999',
                    'telegram' => '@testuser',
                ],
                'coordinate'       => [
                    'latitude'  => 53.9045,
                    'longitude' => 27.5615,
                    'address'   => 'Минск',
                ],
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('contacts', [
            'type'  => 'phone',
            'value' => '+375299999999',
        ]);
        $this->assertDatabaseHas('contacts', [
            'type'  => 'telegram',
            'value' => '@testuser',
        ]);
        $this->assertDatabaseHas('listing_coordinates', [
            'latitude'  => 53.9045,
            'longitude' => 27.5615,
        ]);
    }
}
