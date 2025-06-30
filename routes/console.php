<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\SecurityCommand;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Register custom commands
Artisan::command('security:manage {action} {ip?}', function ($action, $ip = null) {
    $command = new SecurityCommand();
    $command->setLaravel($this->getLaravel());
    return $command->handle();
})->purpose('Manage security settings (block/unblock IPs, clear cache, view logs)');
