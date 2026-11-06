<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
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
        // Front pages load Bootstrap only (no Tailwind), so paginate()
        // links must render the Bootstrap 5 pagination markup, not the
        // default Tailwind one.
        Paginator::useBootstrapFive();
    }
}
