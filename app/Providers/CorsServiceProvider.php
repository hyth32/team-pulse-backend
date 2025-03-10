<?php

namespace App\Providers;

use Fruitcake\Cors\CorsServiceProvider as BaseCorsServiceProvider;

class CorsServiceProvider extends BaseCorsServiceProvider
{
    protected function configure()
    {
        return [
            'paths' => ['api/*'],
            'allowed_methods' => ['*'],
            'allowed_origins' => ['*'], // Или укажите конкретные домены
            'allowed_headers' => ['*'],
            'exposed_headers' => [],
            'max_age' => 0,
            'supports_credentials' => false,
        ];
    }
}
