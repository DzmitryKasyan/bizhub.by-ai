<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\ListingStatus;
use App\Enums\ListingType;
use App\Models\Category;
use App\Models\Listing;
use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListingCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_page_loads_successfully(): void
    {
        Listing::factory()->count(3)->active()->create();

        $response = $this->get(route('listings.index'));

        $response->assertStatus(200);
        $response->assertSee('Каталог объявлений');
    }

    public function test_sorting_by_price_works(): void
    {
        Listing::factory()->active()->create(['price' => 3000]);
        Listing::factory()->active()->create(['price' => 1000]);
        Listing::factory()->active()->create(['price' => 2000]);

        $response = $this->get(route('listings.index', ['sort' => 'price_asc']));

        $response->assertStatus(200);
        $listings = $response->viewData('listings');
        $this->assertEquals(1000, $listings->first()->price);
    }

    public function test_location_filter_works(): void
    {
        $location = Location::factory()->create();
        $otherLocation = Location::factory()->create();

        Listing::factory()->active()->create(['location_id' => $location->id]);
        Listing::factory()->active()->create(['location_id' => $otherLocation->id]);

        $response = $this->get(route('listings.index', ['location' => $location->id]));

        $response->assertStatus(200);
        $listings = $response->viewData('listings');
        $this->assertCount(1, $listings);
        $this->assertEquals($location->id, $listings->first()->location_id);
    }

    public function test_category_filter_works(): void
    {
        $category = Category::factory()->create();

        Listing::factory()->active()->create(['category_id' => $category->id]);
        Listing::factory()->active()->create();

        $response = $this->get(route('listings.index', ['category' => $category->slug]));

        $response->assertStatus(200);
        $listings = $response->viewData('listings');
        $this->assertCount(1, $listings);
        $this->assertEquals($category->id, $listings->first()->category_id);
    }

    public function test_inactive_listings_are_not_shown(): void
    {
        Listing::factory()->create(['status' => ListingStatus::Draft]);

        $response = $this->get(route('listings.index'));

        $response->assertStatus(200);
        $listings = $response->viewData('listings');
        $this->assertCount(0, $listings);
    }
}
