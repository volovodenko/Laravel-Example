<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use App\Rules\PhoneNumberRule;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Paginator::useBootstrap();
        Carbon::setLocale(config('app.locale'));

        $this->bootRules();
        $this->bootObservers();
    }

    private function bootRules()
    {
        \Validator::extend('phone', PhoneNumberRule::class);
    }

    private function bootObservers()
    {
        User::observe(UserObserver::class);
    }
}
