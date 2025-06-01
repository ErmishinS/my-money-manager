<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (env('VITE_DISABLED')) {
        app()->bind(Vite::class, fn () => new class {
            public function __invoke(...$args) { return ''; }
            public function asset(...$args) { return ''; }
        });
    }
    }
}
