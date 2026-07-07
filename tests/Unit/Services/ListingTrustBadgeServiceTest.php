<?php

namespace Tests\Unit\Services;

use App\Enums\VerificationStatus;
use App\Enums\VerificationType;
use App\Models\Listing;
use App\Models\ListingVerification;
use App\Models\User;
use App\Services\ListingTrustBadgeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ListingTrustBadgeServiceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_returns_phone_badge_for_verified_phone(): void
    {
        $user = User::factory()->create(['phone_verified_at' => now()]);
        $listing = Listing::factory()->create(['user_id' => $user->id]);

        $badges = (new ListingTrustBadgeService())->forListing($listing);

        $this->assertCount(1, $badges);
        $this->assertSame(VerificationType::Phone->value, $badges[0]['key']);
    }

    #[Test]
    public function it_returns_identity_badge_for_verified_identity(): void
    {
        $user = User::factory()->create(['identity_verified_at' => now()]);
        $listing = Listing::factory()->create(['user_id' => $user->id]);

        $badges = (new ListingTrustBadgeService())->forListing($listing);

        $this->assertTrue(
            collect($badges)->contains('key', VerificationType::Identity->value)
        );
    }

    #[Test]
    public function it_returns_business_docs_badge_when_approved(): void
    {
        $listing = Listing::factory()->create();
        ListingVerification::create([
            'listing_id' => $listing->id,
            'user_id' => $listing->user_id,
            'type' => VerificationType::BusinessDocs,
            'status' => VerificationStatus::Approved,
        ]);

        $badges = (new ListingTrustBadgeService())->forListing($listing);

        $this->assertTrue(
            collect($badges)->contains('key', VerificationType::BusinessDocs->value)
        );
    }

    #[Test]
    public function it_ignores_pending_verifications(): void
    {
        $listing = Listing::factory()->create();
        ListingVerification::create([
            'listing_id' => $listing->id,
            'user_id' => $listing->user_id,
            'type' => VerificationType::Vetted,
            'status' => VerificationStatus::Pending,
        ]);

        $badges = (new ListingTrustBadgeService())->forListing($listing);

        $this->assertFalse(
            collect($badges)->contains('key', VerificationType::Vetted->value)
        );
    }

    #[Test]
    public function approve_creates_or_updates_verification(): void
    {
        $listing = Listing::factory()->create();
        $reviewer = User::factory()->create();

        (new ListingTrustBadgeService())->approve($listing, $reviewer, VerificationType::Financials, 'Скриншеры предоставлены');

        $this->assertDatabaseHas('listing_verifications', [
            'listing_id' => $listing->id,
            'type' => VerificationType::Financials->value,
            'status' => VerificationStatus::Approved->value,
            'reviewed_by' => $reviewer->id,
            'notes' => 'Скриншеры предоставлены',
        ]);
    }
}
