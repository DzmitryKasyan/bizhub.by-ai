<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListingDocument extends Model
{
    protected $fillable = [
        'listing_id',
        'path',
        'original_name',
        'type',
    ];

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }

    public function getTypeLabel(): string
    {
        return match($this->type) {
            'financial' => 'Финансовая отчётность',
            'legal' => 'Юридические документы',
            'presentation' => 'Презентация',
            default => 'Прочие документы',
        };
    }
}
