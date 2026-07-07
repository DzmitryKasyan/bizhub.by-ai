<?php

namespace Tests\Feature\Migrations;

use App\Enums\ListingStatus;
use App\Enums\UserRole;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CleanExistingListingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_existing_price_one_from_admin_becomes_price_on_request(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $listing = Listing::factory()->create([
            'user_id' => $admin->id,
            'price' => 1,
            'status' => ListingStatus::Active,
        ]);

        $this->artisan('migrate:rollback', ['--force' => true, '--path' => 'database/migrations/2026_07_06_000005_clean_existing_listings.php'])->assertSuccessful();
        $this->artisan('migrate', ['--force' => true, '--path' => 'database/migrations/2026_07_06_000005_clean_existing_listings.php'])->assertSuccessful();

        $listing->refresh();
        $this->assertTrue($listing->price_on_request);
        $this->assertNull($listing->price);
        $this->assertTrue($listing->is_representative);
    }

    public function test_existing_prohibited_content_gets_rejected(): void
    {
        $user = User::factory()->create();
        $listing = Listing::factory()->create([
            'user_id' => $user->id,
            'title' => 'Предлагаю займ',
            'description' => 'Описание',
            'status' => ListingStatus::Active,
        ]);

        $this->artisan('migrate:rollback', ['--force' => true, '--path' => 'database/migrations/2026_07_06_000005_clean_existing_listings.php'])->assertSuccessful();
        $this->artisan('migrate', ['--force' => true, '--path' => 'database/migrations/2026_07_06_000005_clean_existing_listings.php'])->assertSuccessful();

        $listing->refresh();
        $this->assertEquals(ListingStatus::Rejected->value, $listing->status->value);
        $this->assertStringContainsString('Запрещённая тематика', $listing->rejection_reason);
    }
}
