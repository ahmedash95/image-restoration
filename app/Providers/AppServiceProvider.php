<?php

namespace App\Providers;

use App\Http\Integrations\Api\Replicate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(Replicate::class, function () {
            return new Replicate(config('services.replicate.api_token'));
        });
    }
}
