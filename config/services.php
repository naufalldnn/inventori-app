<?php

return [
    'cloudinary' => [
        'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
        'api_key' => env('CLOUDINARY_API_KEY'),
        'api_secret' => env('CLOUDINARY_API_SECRET'),
        'folder' => env('CLOUDINARY_FOLDER', 'inventori/items'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect_uri' => env('GOOGLE_REDIRECT_URI'),
    ],

    'doku' => [
        'mall_id' => env('DOKU_CLIENT_ID', env('DOKU_MALL_ID')),
        'shared_key' => env('DOKU_SHARED_KEY'),
        'sandbox_url' => env('DOKU_SANDBOX_URL', 'https://sandbox.doku.com'),
        'api_url' => env('DOKU_API_URL', 'https://api-sandbox.doku.com'),
        'notification_url' => env('DOKU_NOTIFICATION_URL'),
    ],

    'binderbyte' => [
        'api_key' => env('BINDERBYTE_API_KEY', env('JNT_API_KEY')),
        'base_url' => env('BINDERBYTE_BASE_URL', 'http://api.binderbyte.com/v1'),
        'courier' => env('BINDERBYTE_COURIER', 'jnt'),
        'couriers' => [
            'jnt' => 'J&T Express',
            'jne' => 'JNE Express',
            'sicepat' => 'SiCepat',
            'anteraja' => 'AnterAja',
            'spx' => 'SPX / Shopee Express',
        ],
    ],
];
