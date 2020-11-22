<?php

namespace HoangPhi\VietnamMap\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InstallCommand extends Command
{
    protected $signature = 'vietnam-map:install';

    protected $description = 'Vietnam maps install auto import into database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Please wait for installing...');

        Artisan::call('vendor:publish', ['--provider' => 'HoangPhi\VietnamMap\VietnamMapServiceProvider']);
        Artisan::call('migrate');
        Artisan::call('config:cache');
        Artisan::call('config:clear');
        Artisan::call('vietnam-map:download');

        $this->info('Completed to install Vietnam\'s area database !');
    }
}
