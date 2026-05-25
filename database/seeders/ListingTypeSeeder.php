<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ListingTypeModel;
use Illuminate\Database\Seeder;

class ListingTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['code' => 'sell_business',    'name' => 'Продажа бизнеса',          'icon' => 'heroicon-o-building-storefront', 'sort_order' => 1],
            ['code' => 'buy_business',     'name' => 'Куплю бизнес',             'icon' => 'heroicon-o-shopping-bag',        'sort_order' => 2],
            ['code' => 'seek_investment',  'name' => 'Ищу инвестиции',           'icon' => 'heroicon-o-arrow-trending-up',   'sort_order' => 3],
            ['code' => 'offer_investment', 'name' => 'Предлагаю инвестиции',     'icon' => 'heroicon-o-banknotes',           'sort_order' => 4],
            ['code' => 'franchise',        'name' => 'Франшиза',                 'icon' => 'heroicon-o-squares-2x2',         'sort_order' => 5],
            ['code' => 'partnership',      'name' => 'Партнёрство',              'icon' => 'heroicon-o-user-group',          'sort_order' => 6],
            ['code' => 'real_estate',      'name' => 'Коммерческая недвижимость', 'icon' => 'heroicon-o-home',                 'sort_order' => 7],
            ['code' => 'equipment',        'name' => 'Оборудование',             'icon' => 'heroicon-o-wrench-screwdriver',  'sort_order' => 8],
            ['code' => 'trust_management', 'name' => 'Доверительное управление',  'icon' => 'heroicon-o-hand-raised',         'sort_order' => 9],
        ];

        foreach ($types as $data) {
            ListingTypeModel::updateOrCreate(
                ['code' => $data['code']],
                $data
            );
        }

        $this->command->info('Listing types seeded: ' . count($types));
    }
}
