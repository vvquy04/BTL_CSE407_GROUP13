<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Factories\ProductFactory;
use App\Factories\MenProductFactory;
use App\Factories\WomenProductFactory;
use App\Factories\SmartProductFactory;
use App\Factories\SportProductFactory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(ProductFactory::class, function ($app) {
            return new MenProductFactory(); // Default factory
        });

        $this->app->bind(MenProductFactory::class, function ($app) {
            return new MenProductFactory();
        });

        $this->app->bind(WomenProductFactory::class, function ($app) {
            return new WomenProductFactory();
        });

        $this->app->bind(SmartProductFactory::class, function ($app) {
            return new SmartProductFactory();
        });

        $this->app->bind(SportProductFactory::class, function ($app) {
            return new SportProductFactory();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
