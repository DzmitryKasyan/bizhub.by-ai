<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

final class ExchangeRateService
{
    public const CACHE_KEY = 'exchange_rates';
    private const CACHE_TTL = 3600;

    /**
     * @return array{rates: array<string, float>, updated_at: string|null}
     */
    public function getRates(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function (): array {
            $rates = [];

            try {
                $response = Http::timeout(10)
                    ->get('https://api.nbrb.by/exrates/rates?periodicity=0')
                    ->throw()
                    ->json();

                foreach ($response as $currency) {
                    $abbr = $currency['Cur_Abbreviation'] ?? null;
                    $scale = (int) ($currency['Cur_Scale'] ?? 1);
                    if (in_array($abbr, ['USD', 'EUR', 'RUB', 'CNY', 'PLN'], true)) {
                        $rate = (float) ($currency['Cur_OfficialRate'] ?? 0);
                        if ($scale > 1) {
                            $rate = round($rate / $scale, 4);
                        }
                        $rates[$abbr] = round($rate, 4);
                    }
                }
            } catch (\Throwable) {
            }

            try {
                $cryptoResponse = Http::timeout(5)
                    ->get('https://api.coingecko.com/api/v3/simple/price', [
                        'ids' => 'bitcoin,ethereum',
                        'vs_currencies' => 'usd',
                    ])
                    ->throw()
                    ->json();

                $usdToByn = $rates['USD'] ?? 3.2;

                if (isset($cryptoResponse['bitcoin']['usd'])) {
                    $rates['BTC'] = round((float) $cryptoResponse['bitcoin']['usd'] * $usdToByn, 2);
                }
                if (isset($cryptoResponse['ethereum']['usd'])) {
                    $rates['ETH'] = round((float) $cryptoResponse['ethereum']['usd'] * $usdToByn, 2);
                }
            } catch (\Throwable) {
            }

            return [
                'rates' => $rates,
                'updated_at' => now()->toIso8601String(),
            ];
        });
    }

    /**
     * Flat rates array for the compact homepage bar.
     * @return array<string, float>
     */
    public function getRatesFlat(): array
    {
        return $this->getRates()['rates'];
    }

    /**
     * @return array<string, array{label: string, icon: string, type: string}>
     */
    public function labels(): array
    {
        return [
            'USD' => ['label' => 'Доллар США', 'icon' => '🇺🇸', 'type' => 'fiat'],
            'EUR' => ['label' => 'Евро', 'icon' => '🇪🇺', 'type' => 'fiat'],
            'RUB' => ['label' => 'Рос. рубль', 'icon' => '🇷🇺', 'type' => 'fiat'],
            'CNY' => ['label' => 'Кит. юань', 'icon' => '🇨🇳', 'type' => 'fiat'],
            'PLN' => ['label' => 'Польский злотый', 'icon' => '🇵🇱', 'type' => 'fiat'],
            'BTC' => ['label' => 'Bitcoin', 'icon' => '₿', 'type' => 'crypto'],
            'ETH' => ['label' => 'Ethereum', 'icon' => 'Ξ', 'type' => 'crypto'],
        ];
    }
}
