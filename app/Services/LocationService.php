<?php

namespace App\Services;

use Illuminate\Support\Str;

class LocationService
{
    public function normalizeLocationPayload(
        array $data,
        string $locationKey = 'location_text',
        string $cityKey = 'city',
    ): array {
        return [
            'location_text' => $this->cleanString($data[$locationKey] ?? null),
            'city' => $this->canonicalizeCity($data[$cityKey] ?? null),
        ];
    }

    public function canonicalizeCity(null|string|int|float $city): ?string
    {
        $cleanCity = $this->cleanString($city);
        if (!$cleanCity) {
            return null;
        }

        return $this->canonicalCityLookup()[$this->normalizeCity($cleanCity)] ?? null;
    }

    public function estimateDelivery(?string $buyerCity, ?string $sellerCity, bool $sameDayEnabled = true): string
    {
        $canonicalBuyerCity = $this->canonicalizeCity($buyerCity);
        $canonicalSellerCity = $this->canonicalizeCity($sellerCity);

        if (!$sameDayEnabled || !$canonicalBuyerCity || !$canonicalSellerCity) {
            return 'multiple_days';
        }

        return $canonicalBuyerCity === $canonicalSellerCity ? 'same_day' : 'multiple_days';
    }

    public function citiesMatch(?string $buyerCity, ?string $sellerCity): bool
    {
        $canonicalBuyerCity = $this->canonicalizeCity($buyerCity);
        $canonicalSellerCity = $this->canonicalizeCity($sellerCity);

        if (!$canonicalBuyerCity || !$canonicalSellerCity) {
            return false;
        }

        return $canonicalBuyerCity === $canonicalSellerCity;
    }

    public function deliveryEstimateLabel(?string $estimate): string
    {
        return match ($estimate) {
            'same_day' => 'التوصيل المتوقع: خلال اليوم',
            default => 'التوصيل المتوقع: خلال عدة أيام',
        };
    }

    protected function normalizeCity(string $city): string
    {
        return (string) Str::of($city)
            ->replace(['أ', 'إ', 'آ'], 'ا')
            ->replace('ؤ', 'و')
            ->replace(['ئ', 'ى'], 'ي')
            ->lower()
            ->replaceMatches('/[^\p{L}\p{N}]+/u', ' ')
            ->squish();
    }

    protected function cleanString(null|string|int|float $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $clean = trim((string) $value);

        return $clean !== '' ? $clean : null;
    }

    protected function canonicalCityLookup(): array
    {
        static $lookup;

        if (is_array($lookup)) {
            return $lookup;
        }

        $lookup = [];

        foreach (config('syria_cities.cities', []) as $city) {
            $cleanCity = $this->cleanString($city);
            if ($cleanCity) {
                $lookup[$this->normalizeCity($cleanCity)] = $cleanCity;
            }
        }

        foreach (config('syria_cities.aliases', []) as $alias => $canonicalCity) {
            $cleanAlias = $this->cleanString($alias);
            $cleanCanonicalCity = $this->cleanString($canonicalCity);

            if (!$cleanAlias || !$cleanCanonicalCity) {
                continue;
            }

            $lookup[$this->normalizeCity($cleanAlias)] = $cleanCanonicalCity;
            $lookup[$this->normalizeCity($cleanCanonicalCity)] = $cleanCanonicalCity;
        }

        return $lookup;
    }
}
