<?php

namespace Tests\Unit\Models;

use App\Models\Listing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListingTrustFieldsTest extends TestCase
{
    use RefreshDatabase;

    public function test_listing_has_price_on_request_flag(): void
    {
        $listing = Listing::factory()->create(['price_on_request' => true]);

        $this->assertTrue($listing->price_on_request);
        $this->assertIsBool($listing->price_on_request);
        $this->assertDatabaseHas('listings', [
            'id' => $listing->id,
            'price_on_request' => true,
        ]);
    }

    public function test_listing_has_representative_flag(): void
    {
        $listing = Listing::factory()->create(['is_representative' => true]);

        $this->assertTrue($listing->is_representative);
        $this->assertIsBool($listing->is_representative);
        $this->assertDatabaseHas('listings', [
            'id' => $listing->id,
            'is_representative' => true,
        ]);
    }

    public function test_listing_has_representative_note(): void
    {
        $listing = Listing::factory()->create([
            'is_representative' => true,
            'representative_note' => 'Represented by Acme Brokers',
        ]);

        $this->assertSame('Represented by Acme Brokers', $listing->representative_note);
        $this->assertDatabaseHas('listings', [
            'id' => $listing->id,
            'representative_note' => 'Represented by Acme Brokers',
        ]);
    }

    public function test_listing_has_address_public_flag(): void
    {
        $listing = Listing::factory()->create(['address_public' => true]);

        $this->assertTrue($listing->address_public);
        $this->assertIsBool($listing->address_public);
        $this->assertDatabaseHas('listings', [
            'id' => $listing->id,
            'address_public' => true,
        ]);
    }
}
