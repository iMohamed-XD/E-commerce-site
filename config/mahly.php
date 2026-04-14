<?php

return [
    'default_phone_number' => '0987654321',

    'exchange_rate' => [
        'fallback_usd_to_syp' => (float) env('USD_TO_SYP_FALLBACK_RATE', 13000),
        'manual_override_usd_to_syp' => env('USD_TO_SYP_MANUAL_RATE'),
        'price_migration_syp_to_usd_rate' => (float) env('PRICE_MIGRATION_SYP_TO_USD_RATE', 12000),
        'timeout_seconds' => (int) env('EXCHANGE_RATE_TIMEOUT_SECONDS', 10),
        'cache_ttl_seconds' => (int) env('EXCHANGE_RATE_CACHE_TTL_SECONDS', 14400),
        'market_rate_scale_factor' => (float) env('USD_TO_SYP_MARKET_RATE_SCALE_FACTOR', 100),
        'source_preference' => env('USD_TO_SYP_SOURCE_PREFERENCE', 'market'),
        'market_rate_side' => env('USD_TO_SYP_MARKET_RATE_SIDE', 'sell'),
    ],
];
