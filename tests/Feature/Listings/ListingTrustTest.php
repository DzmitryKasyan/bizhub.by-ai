<?php

namespace Tests\Feature\Listings;

use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListingTrustTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Category $category;
    private Location $location;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => UserRole::Entrepreneur]);
        $this->category = Category::factory()->create();
        $this->location = Location::factory()->create();
    }

    public function test_user_cannot_create_listing_with_price_one(): void
    {
        $this->actingAs($this->user);

        $response = $this->post(route('my-listings.store'), [
            'type' => 'sell_business',
            'title' => 'Продажа бизнеса',
            'description' => 'Описание достаточной длины для прохождения валидации и проверки запрещенного контента.',
            'price' => 1,
            'currency' => 'BYN',
            'category_id' => $this->category->id,
            'location_id' => $this->location->id,
            'listing_format' => 'established_business',
            'rent_conditions' => 'Аренда',
            'included_in_deal' => 'Бизнес',
            'ready_documents' => 'Выписка',
            'employees_count' => 2,
            'sale_reason' => 'Переезд',
            'monthly_profit' => 5000,
        ]);

        $response->assertSessionHasErrors(['price_strategy']);
    }

    public function test_user_can_create_listing_with_price_on_request(): void
    {
        $this->actingAs($this->user);

        $response = $this->post(route('my-listings.store'), [
            'type' => 'sell_business',
            'title' => 'Продажа бизнеса',
            'description' => 'Описание достаточной длины для прохождения валидации и проверки запрещенного контента.',
            'price_on_request' => true,
            'currency' => 'BYN',
            'category_id' => $this->category->id,
            'location_id' => $this->location->id,
            'listing_format' => 'established_business',
            'rent_conditions' => 'Аренда',
            'included_in_deal' => 'Бизнес',
            'ready_documents' => 'Выписка',
            'employees_count' => 2,
            'sale_reason' => 'Переезд',
            'monthly_profit' => 5000,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('listings', [
            'title' => 'Продажа бизнеса',
            'price_on_request' => true,
        ]);
    }

    public function test_prohibited_content_is_rejected(): void
    {
        $this->actingAs($this->user);

        $response = $this->post(route('my-listings.store'), [
            'type' => 'sell_business',
            'title' => 'Предлагаю займ',
            'description' => 'Описание достаточной длины для прохождения валидации и проверки запрещенного контента.',
            'price_on_request' => true,
            'currency' => 'BYN',
            'category_id' => $this->category->id,
            'location_id' => $this->location->id,
            'listing_format' => 'established_business',
            'rent_conditions' => 'Аренда',
            'included_in_deal' => 'Бизнес',
            'ready_documents' => 'Выписка',
            'employees_count' => 2,
            'sale_reason' => 'Переезд',
            'monthly_profit' => 5000,
        ]);

        $response->assertSessionHasErrors(['title']);
    }
}
