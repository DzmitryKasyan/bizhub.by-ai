<?php

namespace Tests\Feature\Listings;

use App\Enums\DealStage;
use App\Enums\DealStageStatus;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ListingDealFlowTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function buyer_can_sign_nda_and_access_data_room(): void
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $listing = Listing::factory()->create(['user_id' => $seller->id]);

        $this->actingAs($buyer)
            ->post(route('listings.nda.sign', $listing), ['agree' => '1'])
            ->assertRedirect();

        $this->assertDatabaseHas('nda_signatures', [
            'listing_id' => $listing->id,
            'buyer_id' => $buyer->id,
        ]);
    }

    #[Test]
    public function seller_can_update_deal_stage_for_buyer_with_nda(): void
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $listing = Listing::factory()->create(['user_id' => $seller->id]);

        // Buyer signs NDA first — deal progress is only tracked for NDA-signed buyers.
        $this->actingAs($buyer)
            ->post(route('listings.nda.sign', $listing), ['agree' => '1'])
            ->assertRedirect();

        $this->actingAs($seller)
            ->post(route('listings.deal-stage.update', $listing), [
                'stage' => DealStage::Meeting->value,
                'status' => DealStageStatus::Done->value,
                'buyer_id' => $buyer->id,
                'notes' => 'Встреча состоялась',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('listing_deal_stages', [
            'listing_id' => $listing->id,
            'buyer_id' => $buyer->id,
            'stage' => DealStage::Meeting->value,
            'status' => DealStageStatus::Done->value,
            'notes' => 'Встреча состоялась',
        ]);
    }

    #[Test]
    public function seller_cannot_update_deal_stage_without_nda(): void
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $listing = Listing::factory()->create(['user_id' => $seller->id]);

        $this->actingAs($seller)
            ->post(route('listings.deal-stage.update', $listing), [
                'stage' => DealStage::Meeting->value,
                'status' => DealStageStatus::Done->value,
                'buyer_id' => $buyer->id,
                'notes' => 'Встреча состоялась',
            ])
            ->assertForbidden();
    }

    #[Test]
    public function guest_cannot_sign_nda(): void
    {
        $listing = Listing::factory()->create();

        $this->post(route('listings.nda.sign', $listing), ['agree' => '1'])
            ->assertRedirect(route('login'));
    }
}
