<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Ai\Providers\MockProvider;

class AiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // The mock provider is now used directly in controllers
        // when AI_MODE=mock, no need to register with Laravel AI SDK
        // since we're using the facade directly with mode switching
    }
}
