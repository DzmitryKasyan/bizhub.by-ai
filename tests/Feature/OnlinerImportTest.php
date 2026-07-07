<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Listing;
use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OnlinerImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_marks_representative_and_price_on_request(): void
    {
        Storage::fake('public');
        User::factory()->create(['id' => 1]);
        Location::factory()->create(['name' => 'Минск']);
        Category::factory()->create(['id' => 10]);

        $jsonl = storage_path('app/onliner_listings.jsonl');
        file_put_contents($jsonl, json_encode([
            'topic_id' => 123,
            'title' => 'Продажа готового кафе',
            'description' => 'Описание кафе',
            'price' => null,
            'location' => 'Минск',
        ], JSON_UNESCAPED_UNICODE) . "\n");

        $this->artisan('onliner:import')
            ->assertSuccessful();

        $this->assertDatabaseHas('listings', [
            'title' => 'Продажа готового кафе',
            'is_representative' => true,
            'price_on_request' => true,
            'price' => null,
        ]);

        unlink($jsonl);
    }

    public function test_import_skips_prohibited_content(): void
    {
        Storage::fake('public');
        User::factory()->create(['id' => 1]);
        Location::factory()->create(['name' => 'Минск']);

        $jsonl = storage_path('app/onliner_listings.jsonl');
        file_put_contents($jsonl, json_encode([
            'topic_id' => 124,
            'title' => 'Займ под залог',
            'description' => 'Описание',
            'price' => 1000,
            'location' => 'Минск',
        ], JSON_UNESCAPED_UNICODE) . "\n");

        $this->artisan('onliner:import')
            ->assertSuccessful();

        $this->assertDatabaseMissing('listings', [
            'title' => 'Займ под залог',
        ]);

        unlink($jsonl);
    }
}
