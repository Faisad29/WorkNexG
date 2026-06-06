<?php

use Illuminate\Support\ServiceProvider;

return [
    'name' => env('APP_NAME', 'WorkNexG'),
    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'timezone' => 'Asia/Riyadh',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'faker_locale' => 'en_US',
    'cipher' => 'AES-256-CBC',
    'key' => env('APP_KEY'),
    'providers' => ServiceProvider::defaultProviders()->merge(
        require __DIR__.'/../bootstrap/providers.php'
    )->toArray(),
];
