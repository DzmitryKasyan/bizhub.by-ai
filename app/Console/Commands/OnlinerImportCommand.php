<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Listing;
use App\Models\ListingImage;
use App\Models\Location;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Laravel\Scout\Searchable;

class OnlinerImportCommand extends Command
{
    protected $signature = 'onliner:import';
    protected $description = 'Import parsed onliner JSONL into listings with auto-categorization';

    private const CATEGORY_RULES = [
        1  => ['магазин','торговл','продукт','ритейл','стеллаж','сканер','весы','прилав','касс','одежд','обув','аптек','цвет','зоомагаз'],
        10 => ['пекар','кафе','ресторан','кофейн','пиццер','суши','ролл','кухн','общепит','доставк.*ед','столов','фастфуд','тесто','кондитер'],
        19 => ['сервис','сто','автосервис','ремонт','салон.*красот','парикмахер','строитель','клининг','грузоперевоз','перевозк','химчист','прачеч','логис'],
        29 => ['производств','станок','цех','завод','фабрик','металлообраб','металлоконстр','упаковоч','лить[её]','изготовлен'],
        37 => ['помещен','офис','склад','здани','торгов.*площад','недвижим','аренд.*бизнес'],
        44 => ['образован','учебн','школ','курс','тренинг','обучен'],
        50 => ['медицин','клиник','стоматолог','ветеринар','фармац','оптик','фитнес','спортзал','здоров'],
        57 => ['гостиниц','отель','хостел','туризм','тур.*агент','санатор','агротур','агроусадьб','рыболов'],
        63 => ['интернет|IT|айти|веб|сайт|приложен|SaaS|маркетплейс|онлайн|программ|AI|искусствен'],
        69 => ['сельск|ферм|урожай|теплиц|животновод|растениевод|рыбовод|агро'],
        75 => ['франшиз'],
    ];

    private const TYPE_RULES = [
        // Check partnership/investment BEFORE buy/sell — more specific patterns first
        'franchise'        => ['франшиз'],
        'partnership'      => ['партн(е|ё)р|сотруднич|соучред|совлад|соинвест'],
        'seek_investment'  => ['инвест(иц|ор).*ищ|ищу.*инвест|привлек.*инвест'],
        'offer_investment' => ['инвест(иц|ор).*предлаг|вложен|финансирован|проинвестирую|инвестирую'],
        'equipment'        => ['оборудован|станок|аппарат|установк|машин.*б/у|прода.*станок|сканер|стеллаж|тестоделит|упаковоч'],
        'real_estate'      => ['недвижим|помещен.*прода|офис.*прода|склад.*прода|здани.*прода|торгов.*площад.*прода'],
        'sell_business'    => ['прода(м|ю|етс|жа).*бизнес|готовый бизнес|действующий бизнес|прода(м|ю).*магазин|прода(м|ю).*кафе|прода(м|ю).*ресторан|прода(м|ю).*салон|прода(м|ю).*сто|прода(м|ю).*пекар|прода(м|ю).*клиник'],
        'buy_business'     => ['куп(лю|им).*(готовый бизнес|действующий бизнес)'],
    ];

    private array $locationMap = [];

    public function handle(): int
    {
        $jsonlPath = storage_path('app/onliner_listings.jsonl');
        if (!file_exists($jsonlPath)) {
            $this->error('No JSONL file. Run onliner:parse first.');
            return 1;
        }

        $this->loadLocationMap();
        $userId = 1;
        $now = now();
        $imported = 0;

        Searchable::withoutSyncingToSearch(function () use ($jsonlPath, $userId, $now, &$imported) {
            $this->doImport($jsonlPath, $userId, $now, $imported);
        });

        $this->info("✅ Imported {$imported} listings.");
        return 0;
    }

    private function doImport(string $jsonlPath, int $userId, \Illuminate\Support\Carbon $now, int &$imported): void
    {
        foreach (file($jsonlPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $item = json_decode($line, true);
            if (!$item) continue;

            $title = html_entity_decode($item['title'] ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $description = html_entity_decode($item['description'] ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8');

            // Skip existing
            if (Listing::where('title', $title)->exists()) {
                continue;
            }

            $type = $this->classifyType($title, $description);
            $categoryId = $this->classifyCategory($title, $description);

            $locationId = $this->locationMap[$item['location']] ?? 2;
            $price = $item['price'] ?? 0;
            if (!$price || $price < 1) $price = 1;

            $listing = Listing::create([
                'user_id'       => $userId,
                'type'          => $type,
                'category_id'   => $categoryId,
                'title'         => $title,
                'description'   => $description ?: $title,
                'price'         => $price,
                'currency'      => 'BYN',
                'location_id'   => $locationId,
                'status'        => 'active',
                'views_count'   => rand(50, 400),
                'expires_at'    => $now->copy()->addDays(rand(30, 90)),
                'created_at'    => $now->copy()->subDays(rand(1, 14)),
                'updated_at'    => $now,
            ]);

            // Save contacts
            if (!empty($item['phone'])) {
                $listing->contacts()->create([
                    'type'       => 'phone',
                    'value'      => $item['phone'],
                    'is_public'  => true,
                ]);
            }
            if (!empty($item['telegram'])) {
                $listing->contacts()->create([
                    'type'       => 'telegram',
                    'value'      => $item['telegram'],
                    'is_public'  => true,
                ]);
            }

            // Download and attach image safely: re-encode to WebP with UUID filename
            if (!empty($item['image_url'])) {
                try {
                    $img = @file_get_contents($item['image_url'], false, stream_context_create([
                        'http' => [
                            'timeout'       => 10,
                            'header'        => "User-Agent: Mozilla/5.0\r\n",
                            'max_redirects' => 2,
                        ],
                    ]));

                    if ($img && strlen($img) > 2000 && strlen($img) < 10_000_000) {
                        $tempPath = tempnam(sys_get_temp_dir(), 'onliner_');
                        file_put_contents($tempPath, $img);

                        $manager = new ImageManager(new Driver());
                        $image = $manager->read($tempPath);

                        $filename = Str::uuid() . '.webp';
                        $dest = 'listings/' . $filename;
                        Storage::disk('public')->put($dest, $image->toWebp(80));

                        unlink($tempPath);

                        ListingImage::create([
                            'listing_id' => $listing->id,
                            'path'       => $dest,
                            'is_main'    => true,
                            'sort_order' => 0,
                        ]);
                    }
                } catch (\Throwable) {}
            }

            $imported++;
        }
    }

    private function classifyType(string $title, string $desc): string
    {
        $text = mb_strtolower($title . ' ' . $desc);
        foreach (self::TYPE_RULES as $type => $patterns) {
            foreach ($patterns as $pattern) {
                if (preg_match('{' . $pattern . '}iu', $text)) {
                    return $type;
                }
            }
        }
        return 'sell_business';
    }

    private function classifyCategory(string $title, string $desc): int
    {
        $text = mb_strtolower($title . ' ' . $desc);
        foreach (self::CATEGORY_RULES as $catId => $patterns) {
            foreach ($patterns as $pattern) {
                if (preg_match('{' . $pattern . '}iu', $text)) {
                    return $catId;
                }
            }
        }
        return 19; // Услуги — default
    }

    private function loadLocationMap(): void
    {
        foreach (Location::all() as $loc) {
            $this->locationMap[$loc->name] = $loc->id;
        }
    }
}