<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Currency;
use App\Enums\ListingStatus;
use App\Enums\ListingType;
use App\Enums\OwnershipType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Tags\HasTags;

class Listing extends Model implements HasMedia
{
    use HasFactory;
    use HasSlug;
    use HasTags;
    use InteractsWithMedia;
    use Searchable;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'type',
        'category_id',
        'subcategory_id',
        'title',
        'slug',
        'description',
        'price',
        'price_max',
        'currency',
        'price_negotiable',
        'location_id',
        'address',
        'latitude',
        'longitude',
        'monthly_revenue',
        'monthly_profit',
        'payback_months',
        'investment_amount',
        'year_founded',
        'employees_count',
        'ownership_type',
        'sale_reason',
        'status',
        'rejection_reason',
        'views_count',
        'favorites_count',
        'responses_count',
        'is_promoted',
        'promoted_until',
        'is_highlighted',
        'is_top',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'type' => ListingType::class,
            'currency' => Currency::class,
            'ownership_type' => OwnershipType::class,
            'status' => ListingStatus::class,
            'price_negotiable' => 'boolean',
            'is_promoted' => 'boolean',
            'is_highlighted' => 'boolean',
            'is_top' => 'boolean',
            'price' => 'decimal:2',
            'price_max' => 'decimal:2',
            'monthly_revenue' => 'decimal:2',
            'monthly_profit' => 'decimal:2',
            'investment_amount' => 'decimal:2',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'promoted_until' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // ─── Scout ─────────────────────────────────────────────────────

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type?->value,
            'status' => $this->status?->value,
            'price' => $this->price,
            'currency' => $this->currency?->value,
            'category_id' => $this->category_id,
            'location_id' => $this->location_id,
            'is_promoted' => $this->is_promoted,
            'is_top' => $this->is_top,
            'views_count' => $this->views_count,
            'created_at' => $this->created_at?->timestamp,
        ];
    }

    public function shouldBeSearchable(): bool
    {
        return $this->status === ListingStatus::Active;
    }

    // ─── Relationships ─────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ListingImage::class)->orderBy('sort_order');
    }

    public function mainImage(): HasMany
    {
        return $this->hasMany(ListingImage::class)->where('is_main', true)->limit(1);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ListingDocument::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoritedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function promotions(): HasMany
    {
        return $this->hasMany(Promotion::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', ListingStatus::Active->value);
    }

    public function scopeOfType(Builder $query, ListingType $type): Builder
    {
        return $query->where('type', $type->value);
    }

    public function scopePromoted(Builder $query): Builder
    {
        return $query->where('is_promoted', true)->where('promoted_until', '>=', now());
    }

    public function scopeTop(Builder $query): Builder
    {
        return $query->where('is_top', true);
    }

    public function scopeNotExpired(Builder $query): Builder
    {
        return $query->where(function (Builder $q) {
            $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
        });
    }

    public function scopeInPriceRange(Builder $query, float $min, float $max): Builder
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    // ─── Media ─────────────────────────────────────────────────────

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);

        $this->addMediaCollection('documents')
            ->acceptsMimeTypes(['application/pdf', 'application/msword']);
    }

    // ─── Helpers ───────────────────────────────────────────────────

    public function isActive(): bool
    {
        return $this->status === ListingStatus::Active;
    }

    public function isOwnedBy(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    public function getMainImageAttribute(): ?string
    {
        $image = $this->relationLoaded('images')
            ? $this->images->firstWhere('is_main', true) ?? $this->images->first()
            : $this->hasMany(ListingImage::class)->where('is_main', true)->first()
                ?? $this->hasMany(ListingImage::class)->orderBy('sort_order')->first();

        return $image?->path;
    }

    public function getImagesArrayAttribute(): array
    {
        $images = $this->relationLoaded('images')
            ? $this->images
            : $this->hasMany(ListingImage::class)->orderBy('sort_order')->get();

        return $images->pluck('path')->toArray();
    }

    public function getFormattedPriceAttribute(): string
    {
        if (!$this->price) {
            return 'Цена по запросу';
        }

        $symbol = $this->currency?->symbol() ?? '';
        $price = number_format((float) $this->price, 0, '.', ' ');

        if ($this->price_max) {
            $priceMax = number_format((float) $this->price_max, 0, '.', ' ');
            return "{$price} – {$priceMax} {$symbol}";
        }

        return "{$price} {$symbol}";
    }
}
