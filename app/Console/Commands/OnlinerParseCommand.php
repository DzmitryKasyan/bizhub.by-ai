<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

class OnlinerParseCommand extends Command
{
    protected $signature = 'onliner:parse {--pages=5} {--delay=300 : ms between topic requests}';
    protected $description = 'Parse listings from baraholka.onliner.by (f=284)';

    private const FORUM_URL = 'https://baraholka.onliner.by/viewforum.php';
    private const TOPIC_URL = 'https://baraholka.onliner.by/viewtopic.php';
    private const FORUM_ID = 284;
    private const PER_PAGE = 50;

    public function handle(): int
    {
        $pages = (int) $this->option('pages');
        $delay = (int) $this->option('delay') * 1000;
        $jsonlPath = storage_path('app/onliner_listings.jsonl');
        $existing = $this->loadIds($jsonlPath);
        $this->info('Existing: ' . count($existing));

        $newCount = 0;
        $handle = fopen($jsonlPath, 'a');

        for ($page = 0; $page < $pages; $page++) {
            $start = $page * self::PER_PAGE;
            $url = self::FORUM_URL . '?f=' . self::FORUM_ID . ($start > 0 ? "&start={$start}" : '');
            $this->info("Page " . ($page + 1) . "…");
            $html = $this->fetch($url);
            if (!$html) { $this->warn('Failed'); continue; }

            $items = $this->parsePage($html);
            $this->info('  Listings on page: ' . count($items));

            $pageNew = 0;
            foreach ($items as $item) {
                if (isset($existing[$item['topic_id']])) continue;

                // Visit individual topic page for full text, contacts and image
                $topicHtml = $this->fetch(self::TOPIC_URL . '?t=' . $item['topic_id']);
                if ($topicHtml) {
                    $topicData = $this->extractTopicData($topicHtml);
                    $item['description'] = $topicData['description'] ?: $item['description'];
                    $item['phone'] = $topicData['phone'];
                    $item['telegram'] = $topicData['telegram'];
                    $item['image_url'] = $topicData['image_url'];
                } else {
                    $item['image_url'] = null;
                }
                unset($item['placeholder']);

                fwrite($handle, json_encode($item, JSON_UNESCAPED_UNICODE) . "\n");
                $existing[$item['topic_id']] = true;
                $pageNew++; $newCount++;

                usleep($delay);
            }
            $this->info("  New: {$pageNew}");
        }

        fclose($handle);
        $this->info("Done. Total new: {$newCount}");
        return 0;
    }

    private function loadIds(string $path): array
    {
        $ids = [];
        if (!file_exists($path)) return $ids;
        foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $d = json_decode($line, true);
            if ($d && isset($d['topic_id'])) $ids[$d['topic_id']] = true;
        }
        return $ids;
    }

    private function fetch(string $url): ?string
    {
        $ctx = stream_context_create([
            'http' => ['method' => 'GET', 'header' => "User-Agent: Mozilla/5.0\r\n", 'timeout' => 15],
        ]);
        return @file_get_contents($url, false, $ctx) ?: null;
    }

    private function parsePage(string $html): array
    {
        $items = []; $seen = [];

        if (!preg_match_all(
            '/<h2\s+class="wraptxt">\s*<a\s+href="[^"]*viewtopic\.php\?t=(\d+)[^"]*">(.*?)<\/a>\s*<\/h2>.*?'
            . '<p\s+class="ba-description">(.*?)<\/p>/isu',
            $html, $matches, PREG_SET_ORDER
        )) return $items;

        foreach ($matches as $m) {
            $tid = $m[1];
            if (isset($seen[$tid])) continue;
            $seen[$tid] = true;

            $title = html_entity_decode(trim(strip_tags($m[2])), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            if (mb_strlen($title) < 3) continue;

            $desc = html_entity_decode(trim(preg_replace('/\s+/u', ' ', strip_tags($m[3]))), ENT_QUOTES | ENT_HTML5, 'UTF-8');

            // Price
            $price = null;
            $context = substr($html, max(0, strpos($html, $m[0]) - 1000), 2000);
            if (preg_match('/(\d[\d\s]*[.,]\d{2})\s*(?:₽|р|BYN|бел)/iu', $context, $pm)) {
                $price = (float) str_replace([' ', ','], ['', '.'], $pm[1]);
            }

            // Location
            $loc = 'Минск';
            if (preg_match('/(Минск|Гродно|Брест|Витебск|Могил[её]в|Гомель|Заславль|Смолевичи|Дзержинск|Бобруйск|Орша|Полоцк|Пинск|Барановичи|Лида|Солигорск|Мозырь|Слуцк|Борисов|Жодино|Молодечно)/iu', $context, $lm)) {
                $loc = str_replace(['Могилеве', 'Могилёв'], 'Могилев', $lm[1]);
            }

            $items[] = [
                'topic_id'    => (int) $tid,
                'title'       => $title,
                'description' => mb_substr($desc, 0, 1000),
                'price'       => $price,
                'location'    => $loc,
                'parsed_at'   => now()->toIso8601String(),
            ];
        }
        return $items;
    }

    private function extractTopicData(string $html): array
    {
        // Try to find the main post content
        $contentHtml = null;
        foreach ([
            '/<div[^>]*class="[^"]*post-content[^"]*"[^>]*>(.*?)<\/div>/isu',
            '/<div[^>]*class="[^"]*b-post__content[^"]*"[^>]*>(.*?)<\/div>/isu',
            '/<div[^>]*class="[^"]*content[^"]*"[^>]*>(.*?)<\/div>/isu',
        ] as $pattern) {
            if (preg_match($pattern, $html, $m)) {
                $contentHtml = $m[1];
                break;
            }
        }

        if (!$contentHtml) {
            // Fallback: extract from body, removing scripts and styles
            $body = preg_replace('/<script[^>]*>.*?<\/script>/isu', '', $html);
            $body = preg_replace('/<style[^>]*>.*?<\/style>/isu', '', $body);
            if (preg_match('/<body[^>]*>(.*?)<\/body>/isu', $body, $m)) {
                $contentHtml = $m[1];
            } else {
                $contentHtml = $html;
            }
        }

        $text = trim(preg_replace('/\s+/u', ' ', strip_tags($contentHtml)));
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Extract phone
        $phone = null;
        if (preg_match('/(?:\+375|80?\s?29|33|44|25)\s?[\d\-\(\)\s]{6,13}\d/iu', $text, $pm)) {
            $phone = preg_replace('/\s+/u', ' ', trim($pm[0]));
        }

        // Extract telegram
        $telegram = null;
        if (preg_match('/(?:telegram|телеграм|tg|тг)[^\w]*@([a-zA-Z0-9_]{5,32})/iu', $text, $tm)) {
            $telegram = '@' . $tm[1];
        } elseif (preg_match('/(?:https?:\/\/)?t\.me\/([a-zA-Z0-9_]{5,32})/iu', $text, $tm)) {
            $telegram = '@' . $tm[1];
        } elseif (preg_match('/(?<![a-zA-Z0-9_])@([a-zA-Z0-9_]{5,32})/u', $text, $tm)) {
            $telegram = '@' . $tm[1];
        }

        // Find first listing image (fleamarket folder), skip avatars and icons
        $imageUrl = null;
        if (preg_match('/<img\s+[^>]*src="(https?:\/\/content\.onliner\.by\/fleamarket\/[^"]+)"[^>]*>/iu', $html, $m)) {
            $imageUrl = $m[1];
        }
        if ($imageUrl && preg_match('#/icon/\d+#', $imageUrl)) {
            $imageUrl = null;
        }

        return [
            'description' => mb_substr($text, 0, 5000),
            'phone'       => $phone,
            'telegram'    => $telegram,
            'image_url'   => $imageUrl,
        ];
    }
}
