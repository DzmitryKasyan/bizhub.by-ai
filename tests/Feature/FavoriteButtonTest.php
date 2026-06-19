<?php

namespace Tests\Feature;

use App\Enums\ListingStatus;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteButtonTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_sees_working_favorite_button_on_listing_page(): void
    {
        $owner = User::factory()->create();
        $viewer = User::factory()->create();

        $listing = Listing::create([
            'user_id' => $owner->id,
            'title' => 'Test Business',
            'slug' => 'test-business',
            'description' => 'A great business opportunity.',
            'status' => ListingStatus::Active->value,
        ]);

        $response = $this->actingAs($viewer)
            ->get(route('listings.show', $listing->slug));

        $response->assertStatus(200);
        $response->assertSee('В избранное');
        $response->assertSee(route('api.listings.favorite', $listing));
        $response->assertSee('data-favorite-url');
    }

    public function test_api_favorite_toggle_creates_and_removes_favorite(): void
    {
        $owner = User::factory()->create();
        $viewer = User::factory()->create();

        $listing = Listing::create([
            'user_id' => $owner->id,
            'title' => 'Test Business',
            'slug' => 'test-business-2',
            'description' => 'A great business opportunity.',
            'status' => ListingStatus::Active->value,
        ]);

        $this->actingAs($viewer)
            ->postJson(route('api.listings.favorite', $listing))
            ->assertOk()
            ->assertJson([
                'favorited' => true,
                'count' => 1,
            ]);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $viewer->id,
            'listing_id' => $listing->id,
        ]);

        $this->assertDatabaseHas('listings', [
            'id' => $listing->id,
            'favorites_count' => 1,
        ]);

        $this->actingAs($viewer)
            ->postJson(route('api.listings.favorite', $listing))
            ->assertOk()
            ->assertJson([
                'favorited' => false,
                'count' => 0,
            ]);

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $viewer->id,
            'listing_id' => $listing->id,
        ]);
    }
}
