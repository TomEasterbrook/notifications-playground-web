<?php
namespace App\Notifications\Channels;

use App\Events\NewNotificationEvent;
use App\Models\User;
use Exception;
use Illuminate\Notifications\Notification;

class WebPushChannel
{
    public function send($notifiable, Notification $notification)
    {
       $message = $this->getMessage($notifiable, $notification);
        $devices = $this->getDevices($notifiable);

        if (is_array($devices)) {
            foreach ($devices as $device) {
                $this->sendNotification($device, $message);
            }
        } else {
            $this->sendNotification($devices, $message);
        }

    }

    /**
     * @throws Exception
     */
    private function getMessage(object $notifiable, Notification $notification)
    {
        if (method_exists($notification, 'toWebPush')) {
            return $notification->toWebPush($notifiable, $notification);
        }
        throw new Exception('Notification is missing toWebPush method.');
    }

    /**
     * @throws Exception
     */
    private function getDevices(object $notifiable): string|array
    {
        if (method_exists($notifiable, 'routeNotificationForWebPush')) {
            return $notifiable->routeNotificationForWebPush();
        }elseif ($notifiable instanceof  User) {
            return $notifiable->notificationDevices()->pluck('device_id')->toArray();
        }
        throw new Exception('Notifiable is missing routeNotificationFor method.');
    }

    private function sendNotification(string $device, WebPushMessage $message)
    {
        broadcast(new NewNotificationEvent($message->title, $message->message, $device));
    }


}
