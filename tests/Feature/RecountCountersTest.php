<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\ListingType;
use App\Models\Category;
use App\Models\Listing;
use App\Models\ListingTypeModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecountCountersTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_recounts_category_counters(): void
    {
        $category = Category::factory()->create();
        Listing::factory()->active()->count(3)->create(['category_id' => $category->id]);
        Listing::factory()->active()->count(2)->create();

        $this->artisan('listings:recount-counters')->assertSuccessful();

        $this->assertDatabaseHas('categories', [
            'id'             => $category->id,
            'listings_count' => 3,
        ]);
    }

    public function test_command_recounts_type_counters(): void
    {
        ListingTypeModel::factory()->create([
            'code' => ListingType::Franchise->value,
            'name' => 'Франшиза',
        ]);

        Listing::factory()->active()->count(4)->create(['type' => ListingType::Franchise->value]);
        Listing::factory()->active()->count(2)->create(['type' => ListingType::SellBusiness->value]);

        $this->artisan('listings:recount-counters')->assertSuccessful();

        $this->assertDatabaseHas('listing_types', [
            'code'           => ListingType::Franchise->value,
            'listings_count' => 4,
        ]);
    }
}
