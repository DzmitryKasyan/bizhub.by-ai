<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'parent_id'   => null,
            'name'        => ucfirst($name),
            'slug'        => Str::slug($name),
            'icon'        => 'heroicon-o-briefcase',
            'sort_order'  => fake()->numberBetween(1, 100),
            'is_active'   => true,
        ];
    }
}
