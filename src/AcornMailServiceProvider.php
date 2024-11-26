<?php

namespace inRage\AcornMail;

use Illuminate\Support\ServiceProvider;

class AcornMailServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('inRage\AcornMail', fn () => AcornMail::make($this->app));
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/wp-mail.php' => $this->app->configPath('wp-mail.php'),
        ], 'acorn-mail');

        $this->app->make('inRage\AcornMail');
    }
}