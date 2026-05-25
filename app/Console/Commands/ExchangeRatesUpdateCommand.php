<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\ExchangeRateService;
use Illuminate\Console\Command;

final class ExchangeRatesUpdateCommand extends Command
{
    protected $signature = 'exchange:update';
    protected $description = 'Fetch and cache fresh exchange rates from NBRB and CoinGecko';

    public function handle(ExchangeRateService $rates): int
    {
        $this->info('Fetching exchange rates...');

        $data = $rates->getRates();
        $ratesList = $data['rates'];

        if (empty($ratesList)) {
            $this->warn('No rates returned — APIs may be unavailable.');

            return self::FAILURE;
        }

        $this->table(['Currency', 'Rate (Br)'], collect($ratesList)
            ->map(fn ($rate, $code) => [$code, number_format($rate, $code === 'BTC' || $code === 'ETH' ? 0 : 4, '.', ' ')])
            ->values()
            ->toArray());

        $this->newLine();
        $this->info(sprintf('Updated %d rates. Cache valid for 1 hour.', count($ratesList)));

        return self::SUCCESS;
    }
}
