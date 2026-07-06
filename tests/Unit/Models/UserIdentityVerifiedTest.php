<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserIdentityVerifiedTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_identity_verified_at(): void
    {
        $timestamp = now()->startOfSecond();

        $user = User::factory()->create(['identity_verified_at' => $timestamp]);

        $this->assertNotNull($user->identity_verified_at);
        $this->assertInstanceOf(Carbon::class, $user->identity_verified_at);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'identity_verified_at' => $timestamp->toDateTimeString(),
        ]);
    }
}
