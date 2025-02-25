<?php

namespace App\Providers;

use Config;
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
        $paths = [];
        $databasePath = database_path();
        $migrationFolders = Config::get('migration_folders');

        foreach ($migrationFolders as $folder) {
            $paths[] = "$databasePath/migrations/$folder";
        }

        $this->loadMigrationsFrom($paths);
    }
}
