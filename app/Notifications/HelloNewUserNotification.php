<?php

namespace App\Notifications;

use App\Notifications\Channels\WebPushChannel;
use App\Notifications\Channels\WebPushMessage;
use Illuminate\Notifications\Notification;

class HelloNewUserNotification extends Notification
{
    public function __construct()
    {
    }

    public function via($notifiable): array
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Hello New User')
            ->message('Welcome to our application!' . $notifiable->name);
    }
}
