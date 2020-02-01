<?php

namespace SlowQueryNotifier;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use SlowQueryNotifier\SlowQueryNotification;

class SlowQueryNotifier
{
    private $threshold;

    private $email;

    public function threshold($miliseconds) {
        $this->threshold = $miliseconds;

        return $this;
    }

    public function getThreshold() {
        return $this->threshold;
    }

    public function toEmail($email) {
        $this->email = $email;

        return $this;
    }

    public function checkQuery($query) {
        if ($query->time > $this->threshold) {
            try {
                Notification::route('mail', $this->email)
                    ->notify(new SlowQueryNotification());
            } catch (\Exception $e) {
                // Fail silently
                report($e);
                Log::error($e->getMessage());
            }
        }
    }

    public function getSqnConnection($name = 'sqn') {
        app()['config']->set('database.connections.'.$name, [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $connection = \DB::connection($name);
        $pdo = $connection->getPdo();
        $pdo->sqliteCreateFunction(
            'sleep',
            function ($miliseconds) {
                return usleep($miliseconds * 1000);
            }
        );
        return $connection;
    }
}
