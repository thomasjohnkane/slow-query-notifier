<?php

namespace SlowQueryNotifier;

use Illuminate\Bus\Queueable;
use \Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;


class SlowQueryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Query Threshold Exceeded')
            ->greeting('We found a slow query:')
            ->line('Threshold: '.app(SlowQueryNotifier::class)->getThreshold().' ms')
            ->line('Time: '.$this->query->time.' ms')
            ->line(new HtmlString('<hr />'))
            ->line(new HtmlString('<code style="margin-top: 16px; color: white; background: #2d3748; border-radius: 6px; padding: 10px; display: block;">'.$this->query->sql.'<code>'))
            ->line(new HtmlString('<hr />'))
            ->salutation(new HtmlString('Yours Truley,<br />Slow Query Notifier'));
    }
}
