<?php

namespace App\Providers;

use App\Issue;
use App\Observers\IssueObserver;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Issue::observe(IssueObserver::class);
        Blade::directive('icon', function ($icon) {
            return icon($icon);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
