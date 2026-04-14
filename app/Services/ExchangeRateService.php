<?php

namespace App\Services;

use App\Models\ExchangeRate;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class ExchangeRateService
{
    protected const RATE_CACHE_KEY_PREFIX = 'exchange_rate.usd_to_syp.current';

    protected const RATE_META_CACHE_KEY_PREFIX = 'exchange_rate.usd_to_syp.last_fetched';

    public function getCurrentUsdToSypRate(): float
    {
        $manualOverrideRate = $this->getManualOverrideRate();
        if ($manualOverrideRate !== null) {
            return $manualOverrideRate;
        }

        $ttlSeconds = (int) config('mahly.exchange_rate.cache_ttl_seconds', 3600);

        $rateCacheKey = $this->currentRateCacheKey();
        try {
            $cachedRate = Cache::remember(
                $rateCacheKey,
                now()->addSeconds(max(60, $ttlSeconds)),
                function (): float {
                    return $this->refreshUsdToSypRate()->rate;
                }
            );

            if (is_numeric($cachedRate) && (float) $cachedRate > 0) {
                return (float) $cachedRate;
            }
        } catch (Throwable $throwable) {
            Log::error('Failed to refresh USD to SYP rate from API, falling back to stored/default rate.', [
                'exception' => $throwable->getMessage(),
            ]);
        }

        $latest = $this->getLatestUsdToSypRateForConfiguredProvider();
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

        $rate = ExchangeRate::query()->updateOrCreate(
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

        $ttlSeconds = (int) config('mahly.exchange_rate.cache_ttl_seconds', 3600);
        $rateCacheKey = $this->currentRateCacheKey();
        $rateMetaCacheKey = $this->currentRateMetaCacheKey();

        Cache::put($rateCacheKey, (float) $rate->rate, now()->addSeconds(max(60, $ttlSeconds)));
        Cache::forever($rateMetaCacheKey, [
            'rate' => (float) $rate->rate,
            'provider' => (string) ($payload['provider'] ?? 'unknown'),
            'fetched_at' => Carbon::now()->toIso8601String(),
        ]);

        return $rate;
    }

    public function convertUsdToSyp(float $amountUsd, ?float $rate = null): float
    {
        $effectiveRate = $rate ?? $this->getCurrentUsdToSypRate();

        return round($amountUsd * $effectiveRate, 2);
    }

    public function getFallbackRate(): float
    {
        return (float) config('mahly.exchange_rate.fallback_usd_to_syp', 13000);
    }

    protected function fetchUsdToSypRate(): ?array
    {
        $url = (string) config('services.exchange_rate.url');

        if ($url === '') {
            return null;
        }

        $timeout = (int) config('mahly.exchange_rate.timeout_seconds', 10);
        $apiKey = (string) config('services.exchange_rate.key');

        $request = Http::acceptJson()
            ->connectTimeout(max(5, min($timeout, 15)))
            ->timeout(max(10, $timeout))
            ->retry(2, 1500, throw: false);

        if ($apiKey !== '') {
            $request = $request
                ->withToken($apiKey)
                ->withHeaders([
                    'apikey' => $apiKey,
                    'x-api-key' => $apiKey,
                ]);
        }

        $response = Str::contains($url, 'lirascope.syria-cloud.sy')
            ? $request->get($url, [
                'currencies' => 'USD',
                'lang' => 'en',
            ])
            : $request->get($url);

        if (!$response->successful()) {
            Log::warning('Exchange rate API request failed.', [
                'url' => $url,
                'status' => $response->status(),
            ]);
            return null;
        }

        $json = $response->json();
        $rate = $this->extractRateFromResponse($json);
        $rate = $this->normalizeMarketRate($rate, $json);

        if ($rate === null || $rate <= 0) {
            Log::warning('Exchange rate API response missing valid SYP rate.', [
                'url' => $url,
                'payload' => is_array($json) ? array_keys($json) : gettype($json),
            ]);
            return null;
        }

        return [
            'rate' => round($rate, 6),
            'provider' => $this->configuredProviderSignature(),
            'meta' => [
                'fetched_at' => Carbon::now()->toIso8601String(),
            ],
        ];
    }

    protected function getManualOverrideRate(): ?float
    {
        $override = config('mahly.exchange_rate.manual_override_usd_to_syp');

        if (!is_numeric($override)) {
            return null;
        }

        $rate = (float) $override;
        return $rate > 0 ? $rate : null;
    }

    protected function extractRateFromResponse(mixed $payload): ?float
    {
        if (!is_array($payload)) {
            return null;
        }

        $marketUsd = collect((array) data_get($payload, 'marketRates'))
            ->first(fn ($row) => strtoupper((string) data_get($row, 'currency')) === 'USD');
        $effectiveUsd = collect((array) data_get($payload, 'effectiveRates'))
            ->first(fn ($row) => strtoupper((string) data_get($row, 'currency')) === 'USD');
        $cbsUsd = collect((array) data_get($payload, 'cbsRates'))
            ->first(fn ($row) => strtoupper((string) data_get($row, 'currency')) === 'USD');

        $sourcePreference = strtolower((string) config('mahly.exchange_rate.source_preference', 'market'));
        $orderedSources = match ($sourcePreference) {
            'market' => ['market'],
            'effective' => ['effective', 'market', 'cbs'],
            'cbs', 'central_bank' => ['cbs', 'market', 'effective'],
            default => ['market', 'effective', 'cbs'],
        };

        foreach ($orderedSources as $source) {
            $row = match ($source) {
                'market' => $marketUsd,
                'effective' => $effectiveUsd,
                'cbs' => $cbsUsd,
                default => null,
            };

            $resolved = $this->resolveUsdRowValue($row, $source === 'market');
            if ($resolved !== null) {
                return $resolved;
            }
        }

        $sourcePreference = strtolower((string) config('mahly.exchange_rate.source_preference', 'market'));
        if ($sourcePreference === 'market') {
            return null;
        }

        $candidates = [data_get($payload, 'rates.SYP')];

        foreach ($candidates as $candidate) {
            if (is_numeric($candidate)) {
                return (float) $candidate;
            }
        }

        return null;
    }

    protected function normalizeMarketRate(?float $rate, mixed $payload): ?float
    {
        if ($rate === null || !is_array($payload)) {
            return $rate;
        }

        // Some market feeds publish "new SYP" units (e.g. 128.7 instead of 12,870).
        // We scale only for market-feed payloads to avoid affecting standard FX APIs.
        $hasMarketShape = isset($payload['marketRates']) || isset($payload['effectiveRates']) || isset($payload['cbsRates']);
        if (!$hasMarketShape) {
            return $rate;
        }

        if ($rate >= 1000) {
            return $rate;
        }

        $scaleFactor = (float) config('mahly.exchange_rate.market_rate_scale_factor', 100);
        if ($scaleFactor <= 0) {
            return $rate;
        }

        return $rate * $scaleFactor;
    }

    protected function resolveUsdRowValue(mixed $row, bool $isMarketSource): ?float
    {
        if (!is_array($row)) {
            return null;
        }

        $marketSide = strtolower((string) config('mahly.exchange_rate.market_rate_side', 'sell'));
        $preferredField = $isMarketSource
            ? match ($marketSide) {
                'buy' => 'buy',
                'mid' => 'mid',
                default => 'sell',
            }
            : 'mid';

        $candidates = [
            data_get($row, $preferredField),
            data_get($row, 'mid'),
            data_get($row, 'sell'),
            data_get($row, 'buy'),
        ];

        foreach ($candidates as $candidate) {
            if (is_numeric($candidate)) {
                return (float) $candidate;
            }
        }

        return null;
    }

    protected function getLatestUsdToSypRateForConfiguredProvider(): ?ExchangeRate
    {
        $provider = $this->configuredProviderSignature();

        $query = ExchangeRate::query()
            ->forPair('USD', 'SYP')
            ->where('provider', $provider)
            ->latest('retrieved_at')
            ->latest('id');

        $latest = $query->first();
        if ($latest) {
            return $latest;
        }

        // Backward-compatible fallback for older rows that stored only host.
        $host = parse_url((string) config('services.exchange_rate.url'), PHP_URL_HOST);
        if (!is_string($host) || $host === '') {
            return null;
        }

        return ExchangeRate::query()
            ->forPair('USD', 'SYP')
            ->where('provider', $host)
            ->latest('retrieved_at')
            ->latest('id')
            ->first();
    }

    protected function configuredProviderSignature(): string
    {
        $url = (string) config('services.exchange_rate.url');
        if ($url === '') {
            return 'configured-provider';
        }

        $parts = parse_url($url);
        $host = (string) ($parts['host'] ?? 'configured-provider');
        $path = (string) ($parts['path'] ?? '');

        return trim($host.$path);
    }

    protected function currentRateCacheKey(): string
    {
        return self::RATE_CACHE_KEY_PREFIX.'.'.$this->rateContextHash();
    }

    protected function currentRateMetaCacheKey(): string
    {
        return self::RATE_META_CACHE_KEY_PREFIX.'.'.$this->rateContextHash();
    }

    protected function rateContextHash(): string
    {
        $context = implode('|', [
            (string) config('services.exchange_rate.url'),
            (string) config('mahly.exchange_rate.source_preference', 'market'),
            (string) config('mahly.exchange_rate.market_rate_side', 'sell'),
            (string) config('mahly.exchange_rate.market_rate_scale_factor', 100),
        ]);

        return sha1($context);
    }
}
