<?php

namespace Tests;

use SlowQueryNotifier\SlowQueryNotifierFacade;
use SlowQueryNotifier\SlowQueryNotifierProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageAliases($app)
    {
        return [
            'SlowQueryNotifier' => SlowQueryNotifierFacade::class
        ];
    }

    protected function getPackageProviders($app)
    {
        return [SlowQueryNotifierProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('mail.driver', 'log');
    }

    public function clearDatabase() {
        // file_put_contents(config('database.connections.testbench.database'), '');
    }

    public function getSqnConnection($name = 'sqn') {
        return app(SlowQueryNotifier::class)->getSqnConnection($name);
    }
}
