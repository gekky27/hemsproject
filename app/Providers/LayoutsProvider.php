<?php

namespace App\Providers;

use App\Models\AppSettings;
use App\Models\Transaction;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class LayoutsProvider extends ServiceProvider
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
        View::composer([
            'layouts.app',
        ], function ($view) {
            $appset = AppSettings::first();

            $view->with('appset', $appset);
        });
    }
}
