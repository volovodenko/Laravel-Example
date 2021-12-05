<?php

declare(strict_types = 1);

return [
    'delivery_auto' => [
        'baseUrl' => env('DELIVERY_AUTO_BASE_URL', ''),
        'culture' => 'uk-UA', // en-US, ru-RU, uk-UA
        'country' => 1, // 1 - Ukraine, 2 - Russia
    ],
    'new_post' => [
        'baseUrl' => env('NEW_POST_BASE_URL', ''),
        'apiKey' => env('NEW_POST_API_KEY', ''),
    ],
];
