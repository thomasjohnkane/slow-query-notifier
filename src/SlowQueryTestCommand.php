<?php

namespace SlowQueryNotifier;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use SlowQueryNotifier\SlowQueryNotification;
use Illuminate\Notifications\AnonymousNotifiable;

class SlowQueryTestCommand extends Command
{

    protected $signature = 'sqn:test';

    protected $description = 'Test that notification is successfully sent';

    public function handle()
    {
        if (!app(SlowQueryNotifier::class)->getThreshold() OR !app(SlowQueryNotifier::class)->getEmail()) {
            $this->error('No email or threshold set. Please set in your AppServiceProvider.php');

            return false;
        }

        app(SlowQueryNotifier::class)->shouldThrowExceptions();
        $connection = app(SlowQueryNotifier::class)->getTemporaryConnectionWithSleepFunction();
        $sleep = app(SlowQueryNotifier::class)->getThreshold() + 10;
        $result = $connection->select(\DB::raw("SELECT sleep($sleep)"));
        $this->info('You should have received an email at: '.app(SlowQueryNotifier::class)->getEmail().'. If you do not receive it in 5 minutes, then email is not configured properly');
    }
}
