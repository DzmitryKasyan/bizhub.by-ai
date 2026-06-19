<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\ListingStatus;
use App\Models\Category;
use App\Models\Listing;
use App\Models\ListingTypeModel;
use Illuminate\Console\Command;

class RecountListingCounters extends Command
{
    protected $signature = 'listings:recount-counters';

    protected $description = 'Recount active listings count for categories and listing types';

    public function handle(): int
    {
        // Categories: count active listings directly assigned + children recursively
        $categories = Category::all();
        foreach ($categories as $category) {
            $categoryIds = $this->collectCategoryIds($category);
            $count = Listing::active()
                ->whereIn('category_id', $categoryIds)
                ->count();

            $category->update(['listings_count' => $count]);
        }

        // Listing types
        foreach (ListingTypeModel::all() as $type) {
            $count = Listing::active()
                ->where('type', $type->code)
                ->count();

            $type->update(['listings_count' => $count]);
        }

        $this->info('Listing counters recalculated.');

        return self::SUCCESS;
    }

    private function collectCategoryIds(Category $category): array
    {
        $ids = [$category->id];

        foreach ($category->children as $child) {
            $ids = array_merge($ids, $this->collectCategoryIds($child));
        }

        return $ids;
    }
}
