<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Listing;
use App\Models\ListingCoordinate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListingMapTest extends TestCase
{
    use RefreshDatabase;

    public function test_map_page_loads_successfully(): void
    {
        $response = $this->get(route('listings.map'));

        $response->assertStatus(200);
        $response->assertSee('Объявления на карте');
    }

    public function test_map_shows_only_listings_with_coordinates(): void
    {
        $listingWithCoord = Listing::factory()->active()->create();
        $listingWithCoord->coordinate()->create([
            'latitude'  => 53.9045,
            'longitude' => 27.5615,
            'address'   => 'Минск',
        ]);

        Listing::factory()->active()->create();

        $response = $this->get(route('listings.map'));

        $response->assertStatus(200);
        $points = $response->viewData('mapPoints');
        $this->assertCount(1, $points);
        $this->assertEquals($listingWithCoord->id, $points->first()['id']);
    }

    public function test_map_filters_by_type(): void
    {
        $franchise = Listing::factory()->active()->create(['type' => 'franchise']);
        $franchise->coordinate()->create([
            'latitude'  => 53.9045,
            'longitude' => 27.5615,
        ]);

        $sellBusiness = Listing::factory()->active()->create(['type' => 'sell_business']);
        $sellBusiness->coordinate()->create([
            'latitude'  => 53.9045,
            'longitude' => 27.5615,
        ]);

        $response = $this->get(route('listings.map', ['type' => 'franchise']));

        $points = $response->viewData('mapPoints');
        $this->assertCount(1, $points);
        $this->assertEquals($franchise->id, $points->first()['id']);
    }
}
