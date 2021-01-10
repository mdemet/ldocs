<?php

namespace Mdemet\Ldocs;

use Illuminate\Support\ServiceProvider;

class LdocsServiceProvider extends ServiceProvider {

    public function boot() {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'ldocs');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }

    public function register() {

    }
}
