<?php

namespace Tests\Unit\Models;

use App\Models\Listing;
use App\Models\ListingVerification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListingVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_listing_verification(): void
    {
        $listing = Listing::factory()->create();
        $user = User::factory()->create();

        $verification = ListingVerification::create([
            'listing_id' => $listing->id,
            'user_id' => $user->id,
            'type' => 'business_docs',
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('listing_verifications', [
            'id' => $verification->id,
            'type' => 'business_docs',
        ]);
    }
}
