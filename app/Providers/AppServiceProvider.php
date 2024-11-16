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
        \Schema::defaultStringLength(191);
        if (explode(':', config('app.url'))[0] == 'https') {
            $this->app['request']->server->set('HTTPS', 'on');
            \URL::forceScheme('https');
        }

        view()->composer('layouts.aside','App\Http\Composer\LeftMenuComposer');

        $modules =\Module::all();
        foreach ($modules as $module) {
            $this->loadMigrationsFrom([$module->getPath() . '/Database/Migrations']);
        }
    }
}
