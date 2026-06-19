<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListingTypeModel extends Model
{
    use HasFactory;
    protected $table = 'listing_types';

    protected $fillable = [
        'code',
        'name',
        'icon',
        'sort_order',
        'is_active',
        'listings_count',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected static function newFactory(): \Database\Factories\ListingTypeFactory
    {
        return \Database\Factories\ListingTypeFactory::new();
    }
}
