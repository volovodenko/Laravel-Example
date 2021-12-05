<?php

return [
    'pagination' => [
        'per_page' => 5,
    ],
    'seller' => [
        'rating' => [
            'min' => 1,
            'max' => 5,
        ],
    ],
    'google' => [
        'places' => [
            'api_key' => env('GOOGLE_PLACES_API_KEY', ''),
        ],
    ],
];
