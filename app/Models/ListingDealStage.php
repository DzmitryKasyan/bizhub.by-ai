<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DealStage;
use App\Enums\DealStageStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListingDealStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'listing_id',
        'buyer_id',
        'stage',
        'status',
        'notes',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'stage' => DealStage::class,
            'status' => DealStageStatus::class,
        ];
    }

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
