<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\DealStage;
use App\Enums\DealStageStatus;
use App\Models\Listing;
use App\Services\ListingDealService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ListingDealController extends Controller
{
    public function __construct(private readonly ListingDealService $dealService)
    {
    }

    public function signNda(Request $request, Listing $listing): RedirectResponse
    {
        $buyer = auth()->user();

        abort_if($listing->isOwnedBy($buyer), 422, 'Нельзя подписать NDA на своё объявление.');

        if ($this->dealService->hasSignedNda($listing, $buyer)) {
            return back()->with('success', 'NDA уже подписано.');
        }

        $validated = $request->validate([
            'agree' => 'accepted',
        ]);

        $this->dealService->signNda($listing, $buyer, $request->ip());

        return back()->with('success', 'NDA подписано. Открыт доступ к data room.');
    }

    public function updateStage(Request $request, Listing $listing): RedirectResponse
    {
        $user = auth()->user();

        abort_unless(
            $listing->isOwnedBy($user)
            || $this->dealService->hasSignedNda($listing, $user)
            || $user->isModerator(),
            403
        );

        $validated = $request->validate([
            'stage' => 'required|in:' . implode(',', DealStage::values()),
            'status' => 'required|in:' . implode(',', DealStageStatus::values()),
            'buyer_id' => 'required|exists:users,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        $buyer = \App\Models\User::findOrFail($validated['buyer_id']);

        // Both seller and buyer (with signed NDA) can update stages, but only for this buyer.
        if (! $listing->isOwnedBy($user) && ! $user->isModerator() && $buyer->id !== $user->id) {
            throw ValidationException::withMessages(['buyer_id' => 'Вы не можете менять статус для другого покупателя.']);
        }

        // Seller/moderator can update any participating buyer's stage.
        if ($listing->isOwnedBy($user) || $user->isModerator()) {
            abort_unless(
                $buyer->id === $user->id || $this->dealService->hasSignedNda($listing, $buyer),
                403,
                'Указанный покупатель не подписал NDA.'
            );
        }

        $this->dealService->updateStage(
            $listing,
            $buyer,
            DealStage::from($validated['stage']),
            DealStageStatus::from($validated['status']),
            $validated['notes'] ?? null,
            $user
        );

        return back()->with('success', 'Статус сделки обновлён.');
    }
}
