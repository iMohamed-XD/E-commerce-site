<?php

return [
    'default_phone_number' => '0987654321',

    'exchange_rate' => [
        'fallback_usd_to_syp' => (float) env('USD_TO_SYP_FALLBACK_RATE', 12000),
        'price_migration_syp_to_usd_rate' => (float) env('PRICE_MIGRATION_SYP_TO_USD_RATE', 12000),
        'timeout_seconds' => (int) env('EXCHANGE_RATE_TIMEOUT_SECONDS', 10),
    ],
];
