<?php

return [
    'name' => env('APP_NAME', 'Asset Management System'),
    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'timezone' => 'Asia/Jakarta',
    'locale' => 'id',
    'fallback_locale' => 'en',
    'faker_locale' => 'id_ID',
    'cipher' => 'AES-256-CBC',
    'key' => env('APP_KEY'),
    'previous_keys' => [...array_filter(explode(',', env('APP_PREVIOUS_KEYS', '')))],
    'maintenance' => ['driver' => 'file'],

    // Custom config
    'qr_code_size' => env('QR_CODE_SIZE', 300),
    'qr_code_format' => env('QR_CODE_FORMAT', 'png'),
    'admin_email' => env('ADMIN_NOTIFICATION_EMAIL', 'admin@assetmanagement.com'),
];
