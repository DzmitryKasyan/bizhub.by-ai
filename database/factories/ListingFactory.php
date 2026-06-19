<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Currency;
use App\Enums\ListingStatus;
use App\Enums\ListingType;
use App\Models\Category;
use App\Models\Listing;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ListingFactory extends Factory
{
    protected $model = Listing::class;

    public function definition(): array
    {
        $title = fake()->sentence(4);

        return [
            'user_id'     => User::factory(),
            'type'        => fake()->randomElement(ListingType::cases())->value,
            'category_id' => Category::factory(),
            'title'       => $title,
            'slug'        => Str::slug($title) . '-' . fake()->unique()->randomNumber(5),
            'description' => fake()->paragraphs(3, true),
            'price'       => fake()->optional(0.8)->numberBetween(1000, 1000000),
            'currency'    => Currency::BYN->value,
            'status'      => ListingStatus::Active->value,
            'location_id' => Location::factory(),
            'expires_at'  => now()->addDays(30),
        ];
    }

    public function active(): static
    {
        return $this->state(fn () => ['status' => ListingStatus::Active->value]);
    }

    public function ofType(ListingType $type): static
    {
        return $this->state(fn () => ['type' => $type->value]);
    }
}
