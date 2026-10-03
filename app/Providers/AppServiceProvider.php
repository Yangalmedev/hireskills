<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Our own lightweight green pager (no Tailwind needed)
        Paginator::defaultView('vendor.pagination.green');
    }
}
