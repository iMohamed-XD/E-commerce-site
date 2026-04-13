<?php

namespace Tests\Unit;

use App\Services\LocationService;
use Tests\TestCase;

class LocationServiceTest extends TestCase
{
    public function test_it_canonicalizes_legacy_and_arabic_city_values(): void
    {
        $service = app(LocationService::class);

        $this->assertSame('دمشق', $service->canonicalizeCity('Damascus'));
        $this->assertSame('إدلب', $service->canonicalizeCity('ادلب'));
        $this->assertSame('اللاذقية', $service->canonicalizeCity('Latakia'));
        $this->assertNull($service->canonicalizeCity('بيروت'));
    }

    public function test_it_matches_cities_by_canonical_value(): void
    {
        $service = app(LocationService::class);

        $this->assertTrue($service->citiesMatch('Damascus', 'دمشق'));
        $this->assertTrue($service->citiesMatch('الحسكه', 'Al-Hasakah'));
        $this->assertFalse($service->citiesMatch('دمشق', 'حمص'));
    }

    public function test_it_estimates_delivery_from_matching_cities_and_same_day_flag(): void
    {
        $service = app(LocationService::class);

        $this->assertSame('same_day', $service->estimateDelivery('Damascus', 'دمشق', true));
        $this->assertSame('multiple_days', $service->estimateDelivery('حمص', 'دمشق', true));
        $this->assertSame('multiple_days', $service->estimateDelivery('دمشق', 'Damascus', false));
        $this->assertSame('multiple_days', $service->estimateDelivery(null, 'دمشق', true));
    }
}
