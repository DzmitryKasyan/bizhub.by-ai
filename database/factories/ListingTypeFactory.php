<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ListingTypeModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class ListingTypeFactory extends Factory
{
    protected $model = ListingTypeModel::class;

    public function definition(): array
    {
        return [
            'code'        => fake()->unique()->word(),
            'name'        => fake()->words(2, true),
            'icon'        => 'heroicon-o-briefcase',
            'sort_order'  => fake()->numberBetween(1, 100),
            'is_active'   => true,
            'listings_count' => 0,
        ];
    }
}
