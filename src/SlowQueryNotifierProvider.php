<?php

namespace SlowQueryNotifier;

use Illuminate\Support\ServiceProvider;

class SlowQueryNotifierProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(SlowQueryNotifier::class);
    }

    public function boot()
    {
        \DB::listen(function ($query) {
            app(SlowQueryNotifier::class)->checkQuery($query);
        });

        if ($this->app->runningInConsole()) {
            $this->commands([SlowQueryTestCommand::class]);
        }
    }
}
