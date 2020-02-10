<?php

namespace SlowQueryNotifier;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class SlowQueryNotifierProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(SlowQueryNotifier::class);

        if ($this->app['config']->get('slow_query_notifier') === null) {
            $this->app['config']->set('slow_query_notifier', require __DIR__.'/../config/config.php');
        }
    }

    public function boot()
    {
        if (config('slow_query_notifier.enabled', true)) {
            Log::info('Enabled...');
            \DB::listen(function ($query) {
                app(SlowQueryNotifier::class)->checkQuery($query);
            });

            if ($this->app->runningInConsole()) {
                $this->commands([SlowQueryTestCommand::class]);
            }
        }
    }
}
