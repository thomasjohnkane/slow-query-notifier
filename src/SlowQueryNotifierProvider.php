<?php

namespace SlowQueryNotifier;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class SlowQueryNotifierProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(SlowQueryNotifier::class);

        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php', 'slow-query-notifier'
        );
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('slow-query-notifier.php'),
        ]);
        
        if (!config('slow_query_notifier.enabled', true)) {
            return;
        }
        
        \DB::listen(function ($query) {
            app(SlowQueryNotifier::class)->checkQuery($query);
        });

        if ($this->app->runningInConsole()) {
            $this->commands([SlowQueryTestCommand::class]);
        }
    }
}
