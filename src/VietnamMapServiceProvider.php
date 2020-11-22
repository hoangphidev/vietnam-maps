<?php

namespace HoangPhi\VietnamMap;

use HoangPhi\VietnamMap\Console\Commands\DownloadCommand;
use HoangPhi\VietnamMap\Console\Commands\InstallCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class VietnamMapServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/vietnam-maps.php', 'vietnam-maps');
    }

    public function boot(Filesystem $filesystem)
    {
        $this->publishes([
            __DIR__ . '/../database/migrations/create_vietnam_maps_table.php' => $this->generateMigrationFileName($filesystem),
        ], 'migrations');

        $this->publishes([
            __DIR__ . '/../config/vietnam-maps.php' => config_path('vietnam-maps.php'),
        ], 'config');

        $this->commands([
            DownloadCommand::class,
            InstallCommand::class,
        ]);
    }

    protected function generateMigrationFileName(Filesystem $filesystem) : string
    {
        $timestamp = date('Y_m_d_His');
        return Collection::make($this->app->databasePath() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem) {
                return $filesystem->glob($path.'*_create_vietnam_maps_table.php');
            })->push($this->app->databasePath() . "/migrations/{$timestamp}_create_vietnam_maps_table.php")->first();
    }
}
