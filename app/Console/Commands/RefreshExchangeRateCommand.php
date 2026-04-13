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
            $this->error('Failed to refresh the USD to SYP exchange rate: '.$throwable->getMessage());

            return self::FAILURE;
        }

        $this->info(sprintf(
            'USD to SYP rate refreshed successfully for %s: %s',
            $rate->effective_date?->toDateString() ?? now()->toDateString(),
            $rate->rate,
        ));

        return self::SUCCESS;
    }
}
