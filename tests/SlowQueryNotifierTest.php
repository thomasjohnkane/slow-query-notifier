<?php

namespace Tests;

use SlowQueryNotifier;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Notification;
use SlowQueryNotifier\SlowQueryNotification;
use Illuminate\Notifications\AnonymousNotifiable;

class SlowQueryNotifierTest extends TestCase
{
    /** @test */
    public function queries_slower_than_given_threshold_send_email()
    {
        $this->clearDatabase();
        $connection = SlowQueryNotifier::getTemporaryConnectionWithSleepFunction();

        Notification::fake();
        SlowQueryNotifier::threshold(99)->toEmail('amdin@example.dev');

        $result = $connection->select(\DB::raw('SELECT sleep(100)'));
        $notifiable = (new AnonymousNotifiable)->route('email', 'amdin@example.dev');

        Notification::assertSentTo($notifiable, SlowQueryNotification::class);
    }

    /** @test */
    public function queries_faster_than_given_threshold_dont_send_email()
    {
        $this->clearDatabase();
        $connection = SlowQueryNotifier::getTemporaryConnectionWithSleepFunction();

        Notification::fake();
        SlowQueryNotifier::threshold(100)->toEmail('amdin@example.dev');

        $result = $connection->select(\DB::raw('SELECT sleep(10)'));
        $notifiable = (new AnonymousNotifiable)->route('email', 'amdin@example.dev');

        Notification::assertNotSentTo($notifiable, SlowQueryNotification::class);
    }

    /** @test */
    public function slow_query_notifier_will_supress_mail_related_errors_in_order_to_not_blow_up_production()
    {
        $this->clearDatabase();
        $connection = SlowQueryNotifier::getTemporaryConnectionWithSleepFunction();
        SlowQueryNotifier::threshold(99);

        // This non existent driver will blowup mail notification
        app()['config']->set('mail.driver', 'non_existent_driver');
        $result = $connection->select(\DB::raw('SELECT sleep(100)'));

        $this->assertTrue(true);
    }

    /** @test */
    public function artisan_command_sends_test_email()
    {
        SlowQueryNotifier::threshold(100)->toEmail('amdin@example.dev');

        Notification::fake();

        \Artisan::call('sqn:test');

        $notifiable = (new AnonymousNotifiable)->route('email', 'amdin@example.dev');
        Notification::assertSentTo($notifiable, SlowQueryNotification::class);
    }

    /** @test */
    public function artisan_command_throws_error_if_test_email_cannot_send()
    {
        SlowQueryNotifier::threshold(100)->toEmail('amdin@example.dev');

        // This non existent driver will blowup mail notification
        app()['config']->set('mail.driver', 'non_existent_driver');
        $this->expectException(\Exception::class);
        \Artisan::call('sqn:test');
    }
    public function threshold_and_email_set_in_service_provider() {
        $this->expectException(\Exception::class);
        $this->artisan('sqn:test')->expectsOutput('No email or threshold set. Please set in your AppServiceProvider.php')->run();
    }
}
