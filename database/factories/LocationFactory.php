<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LocationFactory extends Factory
{
    protected $model = Location::class;

    public function definition(): array
    {
        $name = fake()->unique()->city();

        return [
            'parent_id'  => null,
            'name'       => $name,
            'slug'       => Str::slug($name),
            'type'       => 'region',
            'latitude'   => fake()->latitude(53.0, 56.0),
            'longitude'  => fake()->longitude(23.0, 32.0),
        ];
    }
}
