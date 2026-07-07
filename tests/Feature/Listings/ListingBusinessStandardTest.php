<?php

namespace Tests\Feature\Listings;

use App\Enums\ListingType;
use App\Models\Category;
use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ListingBusinessStandardTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function sell_business_requires_business_standard_fields(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $this->actingAs($user)
            ->post(route('my-listings.store'), [
                'type' => ListingType::SellBusiness->value,
                'title' => 'Кофейня в центре',
                'description' => str_repeat('а', 100),
                'category_id' => $category->id,
                'location_id' => $location->id,
                'currency' => 'BYN',
                'price_strategy' => 'auto',
                'price_on_request' => true,
            ])
            ->assertSessionHasErrors([
                'listing_format',
                'rent_conditions',
                'included_in_deal',
                'ready_documents',
                'employees_count',
                'sale_reason',
                'monthly_revenue',
            ]);
    }

    #[Test]
    public function sell_business_accepts_valid_business_standard(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $this->actingAs($user)
            ->post(route('my-listings.store'), [
                'type' => ListingType::SellBusiness->value,
                'title' => 'Кофейня в центре',
                'description' => str_repeat('а', 100),
                'category_id' => $category->id,
                'location_id' => $location->id,
                'currency' => 'BYN',
                'price_strategy' => 'auto',
                'price_on_request' => true,
                'listing_format' => 'established_business',
                'rent_conditions' => 'Аренда 50 м², 2000 BYN/мес, договор до 2027',
                'included_in_deal' => 'Оборудование, товар, Instagram, договор аренды',
                'ready_documents' => 'Выписка из ЕГР, бухгалтерская отчётность',
                'employees_count' => 4,
                'sale_reason' => 'Переезд',
                'monthly_profit' => 8000,
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect();
    }

    #[Test]
    public function non_sell_business_does_not_require_business_standard(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $this->actingAs($user)
            ->post(route('my-listings.store'), [
                'type' => ListingType::Equipment->value,
                'title' => 'Кофемашина',
                'description' => str_repeat('а', 100),
                'category_id' => $category->id,
                'currency' => 'BYN',
                'price_strategy' => 'auto',
                'price_on_request' => true,
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect();
    }
}
