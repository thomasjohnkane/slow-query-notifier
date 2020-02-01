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
        Notification::fake();

        $connection = app(SlowQueryNotifier::class)->getSqnConnection();
        $sleep = app(SlowQueryNotifier::class)->getThreshold() + 1;
        $result = $connection->select(\DB::raw("SELECT sleep($sleep)"));
        $notifiable = (new AnonymousNotifiable)->route('email', 'amdin@example.dev');

        if (!Notification::hasSent($notifiable, SlowQueryNotification::class)) {
            Log::info('notification not sent...');
            $this->error('Error: Not able to send notification...');
            throw new \Exception("Error sending SlowQueryNotification", 1);
        }
    }
}
