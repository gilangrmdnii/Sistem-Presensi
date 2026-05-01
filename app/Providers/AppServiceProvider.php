<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Pakai Bootstrap 5 pagination styling (bukan Tailwind default)
        Paginator::useBootstrapFive();

        Schema::defaultStringLength(191);
    }
}
