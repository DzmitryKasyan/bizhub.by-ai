<?php

use App\Models\Listing;
use App\Services\ProhibitedContentService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $prohibited = new ProhibitedContentService();

        DB::transaction(function () use ($prohibited) {
            foreach (Listing::with('user')->cursor() as $listing) {
                $text = $listing->title . ' ' . $listing->description;

                if ($prohibited->contains($text)) {
                    $listing->update([
                        'status' => 'rejected',
                        'rejection_reason' => 'Запрещённая тематика: ' . implode(', ', $prohibited->detect($text)),
                    ]);
                    continue;
                }

                if ($listing->price == 1 && $listing->user?->isAdmin()) {
                    $listing->update([
                        'price' => null,
                        'price_on_request' => true,
                        'is_representative' => true,
                        'representative_note' => config('bizhub.representative_default_note'),
                    ]);
                }
            }
        });
    }

    public function down(): void
    {
        // Необратимая миграция данных; откат не предусмотрен.
    }
};
