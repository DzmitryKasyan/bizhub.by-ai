<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'experience_years',
        'investment_range_min',
        'investment_range_max',
        'industries',
        'regions',
        'social_links',
    ];

    protected function casts(): array
    {
        return [
            'industries' => 'array',
            'regions' => 'array',
            'social_links' => 'array',
            'investment_range_min' => 'decimal:2',
            'investment_range_max' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTelegramAttribute(): ?string
    {
        return $this->social_links['telegram'] ?? null;
    }

    public function getLinkedinAttribute(): ?string
    {
        return $this->social_links['linkedin'] ?? null;
    }

    public function getWebsiteAttribute(): ?string
    {
        return $this->social_links['website'] ?? null;
    }
}
