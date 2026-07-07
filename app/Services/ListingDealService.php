<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\DealStage;
use App\Enums\DealStageStatus;
use App\Models\Listing;
use App\Models\ListingDealStage;
use App\Models\NdaSignature;
use App\Models\User;

final class ListingDealService
{
    public function hasSignedNda(Listing $listing, User $buyer): bool
    {
        return $listing->ndaSignatures()
            ->where('buyer_id', $buyer->id)
            ->exists();
    }

    public function signNda(Listing $listing, User $buyer, ?string $ip = null): NdaSignature
    {
        return $listing->ndaSignatures()->firstOrCreate(
            ['buyer_id' => $buyer->id],
            [
                'signed_at' => now(),
                'ip_address' => $ip,
            ]
        );
    }

    public function canAccessDataRoom(Listing $listing, ?User $user): bool
    {
        if ($user === null) {
            return false;
        }

        if ($listing->isOwnedBy($user) || $user->isModerator()) {
            return true;
        }

        return $this->hasSignedNda($listing, $user);
    }

    /**
     * @return array<int, array{stage: string, label: string, status: string, status_label: string, color: string, notes: string|null}>
     */
    public function dealProgress(Listing $listing, User $buyer): array
    {
        $stages = collect(DealStage::cases())
            ->map(fn (DealStage $stage) => [
                'stage' => $stage->value,
                'label' => $stage->label(),
                'status' => DealStageStatus::Pending->value,
                'status_label' => DealStageStatus::Pending->label(),
                'color' => DealStageStatus::Pending->color(),
                'notes' => null,
            ])
            ->keyBy('stage');

        $listing->dealStages()
            ->where('buyer_id', $buyer->id)
            ->get()
            ->each(function (ListingDealStage $record) use ($stages) {
                $stages[$record->stage->value] = [
                    'stage' => $record->stage->value,
                    'label' => $record->stage->label(),
                    'status' => $record->status->value,
                    'status_label' => $record->status->label(),
                    'color' => $record->status->color(),
                    'notes' => $record->notes,
                ];
            });

        return $stages->values()->toArray();
    }

    public function updateStage(Listing $listing, User $buyer, DealStage $stage, DealStageStatus $status, ?string $notes = null, ?User $updatedBy = null): ListingDealStage
    {
        return $listing->dealStages()->updateOrCreate(
            [
                'buyer_id' => $buyer->id,
                'stage' => $stage->value,
            ],
            [
                'status' => $status->value,
                'notes' => $notes,
                'updated_by' => $updatedBy?->id,
            ]
        );
    }
}
