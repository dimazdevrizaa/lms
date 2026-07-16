<?php

namespace App\Observers;

use App\Models\Notification;
use App\Jobs\SendWebPush;

class NotificationObserver
{
    public function created(Notification $notification): void
    {
        $user = $notification->user;
        if ($user) {
            SendWebPush::dispatch($user, $notification->title, $notification->message, $notification->url);
        }
    }
}
