<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
        'storage/*' // biar storage bisa diakses dari luar
    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:3000', // kalau app Flutter jalan di port 3000
        'http://localhost:5000', // kalau di 5000
        'http://127.0.0.1:8000',
        'http://localhost:8000',
        '*' // kalau bener-bener pengen bebas di development
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,


];
