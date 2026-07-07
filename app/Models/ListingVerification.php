<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ListingVerification extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'listing_id',
        'user_id',
        'type',
        'status',
        'reviewed_by',
        'reviewed_at',
        'notes',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'type' => \App\Enums\VerificationType::class,
            'status' => \App\Enums\VerificationStatus::class,
            'reviewed_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
