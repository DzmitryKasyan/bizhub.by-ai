<?php

declare(strict_types=1);

namespace Tests\Feature\Listings;

use App\Models\Category;
use App\Models\Listing;
use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ListingImageUploadSecurityTest extends TestCase
{
    use RefreshDatabase;

    private const MINIMAL_PNG = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAc/9eJ7wAAAAASUVORK5CYII=';

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'type' => 'sell_business',
            'title' => 'Бизнес с фото',
            'description' => str_repeat('а', 100),
            'price_on_request' => true,
            'currency' => 'BYN',
            'listing_format' => 'established_business',
            'rent_conditions' => 'Аренда',
            'included_in_deal' => 'Бизнес',
            'ready_documents' => 'Выписка',
            'employees_count' => 2,
            'sale_reason' => 'Переезд',
            'monthly_profit' => 5000,
        ], $overrides);
    }

    private function realPngFile(): UploadedFile
    {
        $tmp = tempnam(sys_get_temp_dir(), 'png');
        file_put_contents($tmp, base64_decode(self::MINIMAL_PNG));
        return new UploadedFile($tmp, 'photo.png', 'image/png', null, true);
    }

    #[Test]
    public function valid_image_is_uploaded_and_converted_to_webp(): void
    {
        if (! extension_loaded('gd') && ! extension_loaded('imagick')) {
            $this->markTestSkipped('GD or Imagick required for image processing.');
        }

        Storage::fake('public');
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $location = Location::factory()->create();

        $image = $this->realPngFile();

        $this->actingAs($user)
            ->post(route('my-listings.store'), $this->validPayload([
                'category_id' => $category->id,
                'location_id' => $location->id,
                'images' => [$image],
            ]))
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $listing = Listing::latest()->first();
        $this->assertNotNull($listing);
        $this->assertCount(1, $listing->images);

        $path = $listing->images->first()->path;
        $this->assertStringEndsWith('.webp', $path);
        Storage::disk('public')->assertExists($path);

        @unlink($image->getPathname());
    }

    #[Test]
    public function svg_upload_is_rejected(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $tmp = tempnam(sys_get_temp_dir(), 'svg');
        file_put_contents($tmp, '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>');
        $svg = new UploadedFile($tmp, 'exploit.svg', 'image/svg+xml', null, true);

        $this->actingAs($user)
            ->post(route('my-listings.store'), $this->validPayload([
                'category_id' => $category->id,
                'images' => [$svg],
            ]))
            ->assertSessionHasErrors(['images.0']);

        @unlink($tmp);
    }

    #[Test]
    public function fake_image_with_plain_text_content_is_rejected(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $tmp = tempnam(sys_get_temp_dir(), 'fake');
        file_put_contents($tmp, '<?php echo "exploit"; ?>');
        $fake = new UploadedFile($tmp, 'exploit.jpg', 'image/jpeg', null, true);

        $this->actingAs($user)
            ->post(route('my-listings.store'), $this->validPayload([
                'category_id' => $category->id,
                'images' => [$fake],
            ]))
            ->assertSessionHasErrors(['images.0']);

        @unlink($tmp);
    }

    #[Test]
    public function oversized_image_is_rejected(): void
    {
        if (! extension_loaded('gd') && ! extension_loaded('imagick')) {
            $this->markTestSkipped('GD or Imagick required for image processing.');
        }

        Storage::fake('public');
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $tmp = tempnam(sys_get_temp_dir(), 'big');
        $img = imagecreatetruecolor(6000, 4000);
        imagepng($img, $tmp);
        imagedestroy($img);
        $big = new UploadedFile($tmp, 'big.png', 'image/png', null, true);

        $this->actingAs($user)
            ->post(route('my-listings.store'), $this->validPayload([
                'category_id' => $category->id,
                'images' => [$big],
            ]))
            ->assertSessionHasErrors(['images.0']);

        @unlink($tmp);
    }
}
