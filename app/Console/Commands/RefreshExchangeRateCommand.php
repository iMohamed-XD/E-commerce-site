<?php

namespace App\Console\Commands;

use App\Services\ExchangeRateService;
use Illuminate\Console\Command;
use Throwable;

class RefreshExchangeRateCommand extends Command
{
    protected $signature = 'mahly:refresh-exchange-rate';

    protected $description = 'Refresh the cached USD to SYP exchange rate for Mahly.';

    public function handle(ExchangeRateService $exchangeRateService): int
    {
        try {
            $rate = $exchangeRateService->refreshUsdToSypRate();
        } catch (Throwable $throwable) {
            $fallbackRate = $exchangeRateService->getCurrentUsdToSypRate();
            $this->warn('Failed to refresh the USD to SYP exchange rate from remote API: '.$throwable->getMessage());
            $this->warn('Using configured fallback/current rate instead: '.$fallbackRate);

            return self::SUCCESS;
        }

        $this->info(sprintf(
            'USD to SYP rate refreshed successfully for %s: %s',
            $rate->effective_date?->toDateString() ?? now()->toDateString(),
            $rate->rate,
        ));

        return self::SUCCESS;
    }
}
