<?php

return [
    'exchange_rate_cny_idr' => env('EXCHANGE_RATE_CNY_IDR', 2200),
    'import_tax_percent' => env('IMPORT_TAX_PERCENT', 10),
    'shipping_cost_per_kg' => env('SHIPPING_COST_PER_KG', 150000),
    
    'default_weights' => [
        'margin_percent' => 0.25,
        'sold_count' => 0.20,
        'rating' => 0.15,
        'search_volume' => 0.15,
        'competitor_count' => 0.10,
        'price_volatility' => 0.10,
        'shipping_complexity' => 0.05,
    ],
];