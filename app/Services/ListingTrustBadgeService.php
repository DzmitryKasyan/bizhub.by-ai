<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\VerificationStatus;
use App\Enums\VerificationType;
use App\Models\Listing;
use App\Models\User;

final class ListingTrustBadgeService
{
    /**
     * Get approved trust badges for a listing and its owner.
     *
     * @return array<array{key: string, label: string, short_label: string, color: string}>
     */
    public function forListing(Listing $listing): array
    {
        $badges = [];
        $owner = $listing->user;

        if ($owner?->hasVerifiedPhone()) {
            $badges[] = $this->badge(VerificationType::Phone);
        }

        if ($owner?->identity_verified_at !== null) {
            $badges[] = $this->badge(VerificationType::Identity);
        }

        $approvedTypes = $listing->verifications
            ->where('status', VerificationStatus::Approved)
            ->pluck('type')
            ->unique()
            ->toArray();

        foreach ([VerificationType::BusinessDocs, VerificationType::Financials, VerificationType::Vetted] as $type) {
            if (in_array($type, $approvedTypes, true)) {
                $badges[] = $this->badge($type);
            }
        }

        return $badges;
    }

    public function badge(VerificationType $type): array
    {
        return [
            'key' => $type->value,
            'label' => $type->label(),
            'short_label' => $type->shortLabel(),
            'color' => $type->color(),
        ];
    }

    public function hasBadge(Listing $listing, VerificationType $type): bool
    {
        return collect($this->forListing($listing))
            ->contains('key', $type->value);
    }

    public function approve(Listing $listing, User $reviewer, VerificationType $type, ?string $notes = null): void
    {
        $listing->verifications()->updateOrCreate(
            ['type' => $type->value],
            [
                'user_id' => $listing->user_id,
                'status' => VerificationStatus::Approved->value,
                'reviewed_by' => $reviewer->id,
                'reviewed_at' => now(),
                'notes' => $notes,
            ]
        );
    }

    public function reject(Listing $listing, User $reviewer, VerificationType $type, ?string $notes = null): void
    {
        $listing->verifications()->updateOrCreate(
            ['type' => $type->value],
            [
                'user_id' => $listing->user_id,
                'status' => VerificationStatus::Rejected->value,
                'reviewed_by' => $reviewer->id,
                'reviewed_at' => now(),
                'notes' => $notes,
            ]
        );
    }
}
