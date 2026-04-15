<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        // Belarus
        $belarus = Location::create([
            'name' => 'Беларусь',
            'slug' => 'belarus',
            'type' => 'country',
            'latitude' => 53.7098,
            'longitude' => 27.9534,
        ]);

        $regions = [
            [
                'name' => 'Минск',
                'slug' => 'minsk',
                'type' => 'city',
                'latitude' => 53.9045,
                'longitude' => 27.5615,
                'cities' => [],
            ],
            [
                'name' => 'Минская область',
                'slug' => 'minsk-region',
                'type' => 'region',
                'latitude' => 53.9,
                'longitude' => 27.5,
                'cities' => [
                    ['name' => 'Борисов', 'slug' => 'borisov', 'latitude' => 54.2312, 'longitude' => 28.4876],
                    ['name' => 'Жодино', 'slug' => 'zhodino', 'latitude' => 54.1006, 'longitude' => 28.3441],
                    ['name' => 'Молодечно', 'slug' => 'molodechno', 'latitude' => 54.3128, 'longitude' => 26.8388],
                    ['name' => 'Солигорск', 'slug' => 'soligorsk', 'latitude' => 52.7891, 'longitude' => 27.5406],
                    ['name' => 'Слуцк', 'slug' => 'slutsk', 'latitude' => 53.0284, 'longitude' => 27.5546],
                    ['name' => 'Марьина Горка', 'slug' => 'maryina-gorka', 'latitude' => 53.5154, 'longitude' => 28.1419],
                ],
            ],
            [
                'name' => 'Брестская область',
                'slug' => 'brest-region',
                'type' => 'region',
                'latitude' => 52.4,
                'longitude' => 25.9,
                'cities' => [
                    ['name' => 'Брест', 'slug' => 'brest', 'latitude' => 52.0976, 'longitude' => 23.7341],
                    ['name' => 'Барановичи', 'slug' => 'baranovichi', 'latitude' => 53.1320, 'longitude' => 26.0086],
                    ['name' => 'Пинск', 'slug' => 'pinsk', 'latitude' => 52.1214, 'longitude' => 26.0934],
                    ['name' => 'Кобрин', 'slug' => 'kobrin', 'latitude' => 52.2117, 'longitude' => 24.3592],
                    ['name' => 'Лунинец', 'slug' => 'luninets', 'latitude' => 52.2522, 'longitude' => 26.8001],
                ],
            ],
            [
                'name' => 'Витебская область',
                'slug' => 'vitebsk-region',
                'type' => 'region',
                'latitude' => 55.2,
                'longitude' => 28.5,
                'cities' => [
                    ['name' => 'Витебск', 'slug' => 'vitebsk', 'latitude' => 55.1904, 'longitude' => 30.2049],
                    ['name' => 'Орша', 'slug' => 'orsha', 'latitude' => 54.5075, 'longitude' => 30.4137],
                    ['name' => 'Полоцк', 'slug' => 'polotsk', 'latitude' => 55.4820, 'longitude' => 28.7918],
                    ['name' => 'Новополоцк', 'slug' => 'novopolotsk', 'latitude' => 55.5319, 'longitude' => 28.6428],
                    ['name' => 'Молодечно', 'slug' => 'molodechno-vitebsk', 'latitude' => 54.3128, 'longitude' => 26.8388],
                ],
            ],
            [
                'name' => 'Гомельская область',
                'slug' => 'gomel-region',
                'type' => 'region',
                'latitude' => 52.4,
                'longitude' => 30.0,
                'cities' => [
                    ['name' => 'Гомель', 'slug' => 'gomel', 'latitude' => 52.4412, 'longitude' => 30.9878],
                    ['name' => 'Жлобин', 'slug' => 'zhlobin', 'latitude' => 52.8928, 'longitude' => 30.0342],
                    ['name' => 'Мозырь', 'slug' => 'mozyr', 'latitude' => 52.0497, 'longitude' => 29.2383],
                    ['name' => 'Бобруйск', 'slug' => 'bobruysk', 'latitude' => 53.1402, 'longitude' => 29.2212],
                    ['name' => 'Светлогорск', 'slug' => 'svetlogorsk', 'latitude' => 52.6340, 'longitude' => 29.7397],
                ],
            ],
            [
                'name' => 'Гродненская область',
                'slug' => 'grodno-region',
                'type' => 'region',
                'latitude' => 53.7,
                'longitude' => 25.3,
                'cities' => [
                    ['name' => 'Гродно', 'slug' => 'grodno', 'latitude' => 53.6884, 'longitude' => 23.8258],
                    ['name' => 'Лида', 'slug' => 'lida', 'latitude' => 53.8878, 'longitude' => 25.2951],
                    ['name' => 'Волковыск', 'slug' => 'volkovysk', 'latitude' => 53.1506, 'longitude' => 24.4530],
                    ['name' => 'Слоним', 'slug' => 'slonim', 'latitude' => 53.0924, 'longitude' => 25.3145],
                ],
            ],
            [
                'name' => 'Могилёвская область',
                'slug' => 'mogilev-region',
                'type' => 'region',
                'latitude' => 53.9,
                'longitude' => 30.3,
                'cities' => [
                    ['name' => 'Могилёв', 'slug' => 'mogilev', 'latitude' => 53.9168, 'longitude' => 30.3449],
                    ['name' => 'Бобруйск', 'slug' => 'bobruysk-mogilev', 'latitude' => 53.1402, 'longitude' => 29.2212],
                    ['name' => 'Осиповичи', 'slug' => 'osipovichi', 'latitude' => 53.3029, 'longitude' => 28.6424],
                    ['name' => 'Горки', 'slug' => 'gorki', 'latitude' => 54.2839, 'longitude' => 30.9856],
                ],
            ],
        ];

        foreach ($regions as $regionData) {
            $cities = $regionData['cities'] ?? [];
            unset($regionData['cities']);

            $region = Location::create(array_merge($regionData, [
                'parent_id' => $belarus->id,
            ]));

            foreach ($cities as $cityData) {
                Location::create(array_merge($cityData, [
                    'parent_id' => $region->id,
                    'type' => 'city',
                ]));
            }
        }

        $this->command->info('Locations seeded: ' . Location::count() . ' total');
    }
}
