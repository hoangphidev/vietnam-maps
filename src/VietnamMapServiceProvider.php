<?php

namespace HoangPhi\VietnamMap;

use HoangPhi\VietnamMap\Console\Commands\DownloadCommand;
use Illuminate\Support\ServiceProvider;

class VietnamMapServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/vietnam-maps.php', 'vietnam-maps');
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../config/vietnam-maps.php' => config_path('vietnam-maps.php'),
        ], 'config');

        $this->commands([
            DownloadCommand::class,
        ]);
    }
}
