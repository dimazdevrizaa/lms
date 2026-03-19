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
        // Gunakan Bootstrap 5 pagination (sesuai tampilan proyek)
        // Mencegah SVG Tailwind (w-5 h-5) tampil besar karena proyek tidak memakai Tailwind
        Paginator::defaultView('pagination::bootstrap-5');
    }
}
