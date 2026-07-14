<?php

namespace App\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class AliasServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Get the AliasLoader instance
        $loader = AliasLoader::getInstance();

        // Add your aliases
        // $loader->alias('Setting', \App\Facades\Setting::class);
        // $loader->alias('HelperMethods', \App\Facades\HelperMethods::class);
        $loader->alias('Html', Spatie\Html\Facades\Html::class);
        $loader->alias('ViewHelper', \App\Libraries\HelperClass\ViewHelper::class);
        $loader->alias('Visitor', Shetabit\Visitor\Facade\Visitor::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
