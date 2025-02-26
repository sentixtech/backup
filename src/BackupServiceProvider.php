<?php

namespace Backup;

use Illuminate\Support\ServiceProvider;
use Backup\Console\BackupCommand;

class BackupServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/backup.php', 'backup');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                BackupCommand::class,
            ]);
        }
    }
}
