<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListingResourceTrustTest extends TestCase
{
    use RefreshDatabase;
    public function test_admin_can_access_listings_resource(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $this->actingAs($admin);

        $response = $this->get('/admin/listings');
        $response->assertOk();
    }
}
