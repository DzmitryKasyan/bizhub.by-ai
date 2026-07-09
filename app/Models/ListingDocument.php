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
        'is_confidential',
    ];

    protected function casts(): array
    {
        return [
            'is_confidential' => 'boolean',
        ];
    }

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }

    public function getUrlAttribute(): string
    {
        return route('listings.documents.download', [$this->listing->slug, $this]);
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
