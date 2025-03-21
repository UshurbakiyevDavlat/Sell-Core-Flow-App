<?php

use AppModules\Assets\Providers\AlphaVantageProvider;

return [
    'default' => env('MARKET_DATA_PROVIDER', 'alpha_vantage'),

    'providers' => [
        'alpha_vantage' => [
            'client' => AlphaVantageProvider::class,
            'config' => [
                'api_key' => env('ALPHA_VANTAGE_API_KEY'),
                'base_url' => 'https://www.alphavantage.co/query',
            ],
        ],
    ],
];
