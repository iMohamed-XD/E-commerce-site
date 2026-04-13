<?php

namespace App\Services;

use App\Models\ExchangeRate;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class ExchangeRateService
{
    public function getCurrentUsdToSypRate(): float
    {
        $latest = $this->getLatestUsdToSypRate();

        if ($latest) {
            return (float) $latest->rate;
        }

        return $this->getFallbackRate();
    }

    public function getLatestUsdToSypRate(): ?ExchangeRate
    {
        return ExchangeRate::query()
            ->forPair('USD', 'SYP')
            ->latest('retrieved_at')
            ->latest('id')
            ->first();
    }

    public function refreshUsdToSypRate(): ExchangeRate
    {
        $payload = $this->fetchUsdToSypRate();

        if (!$payload) {
            throw new RuntimeException('Unable to fetch the USD to SYP exchange rate from the configured provider.');
        }

        return ExchangeRate::query()->updateOrCreate(
            [
                'base_currency' => 'USD',
                'target_currency' => 'SYP',
                'effective_date' => now()->toDateString(),
            ],
            [
                'rate' => $payload['rate'],
                'provider' => $payload['provider'],
                'retrieved_at' => now(),
                'meta' => $payload['meta'],
            ],
        );
    }

    public function convertUsdToSyp(float $amountUsd, ?float $rate = null): float
    {
        $effectiveRate = $rate ?? $this->getCurrentUsdToSypRate();

        return round($amountUsd * $effectiveRate, 2);
    }

    public function getFallbackRate(): float
    {
        return (float) config('mahly.exchange_rate.fallback_usd_to_syp', 12000);
    }

    protected function fetchUsdToSypRate(): ?array
    {
        $url = (string) config('services.exchange_rate.url');

        if ($url === '') {
            return null;
        }

        $apiKey = (string) config('services.exchange_rate.key');
        $timeout = (int) config('mahly.exchange_rate.timeout_seconds', 10);

        $request = Http::acceptJson()->timeout($timeout);

        if ($apiKey !== '') {
            $request = $request
                ->withToken($apiKey)
                ->withHeaders([
                    'apikey' => $apiKey,
                    'x-api-key' => $apiKey,
                ]);
        }

        $response = $request->get($url, array_filter([
            'base' => 'USD',
            'symbols' => 'SYP',
            'access_key' => $apiKey !== '' ? $apiKey : null,
            'api_key' => $apiKey !== '' ? $apiKey : null,
        ], static fn ($value) => $value !== null));

        if (!$response->successful()) {
            return null;
        }

        $json = $response->json();
        $rate = $this->extractRateFromResponse($json);

        if ($rate === null || $rate <= 0) {
            return null;
        }

        return [
            'rate' => round($rate, 6),
            'provider' => parse_url($url, PHP_URL_HOST) ?: 'configured-provider',
            'meta' => [
                'fetched_at' => Carbon::now()->toIso8601String(),
            ],
        ];
    }

    protected function extractRateFromResponse(mixed $payload): ?float
    {
        if (!is_array($payload)) {
            return null;
        }

        $candidates = [
            data_get($payload, 'rates.SYP'),
            data_get($payload, 'conversion_rates.SYP'),
            data_get($payload, 'data.SYP'),
            data_get($payload, 'result.SYP'),
        ];

        foreach ($candidates as $candidate) {
            if (is_numeric($candidate)) {
                return (float) $candidate;
            }
        }

        return null;
    }
}
