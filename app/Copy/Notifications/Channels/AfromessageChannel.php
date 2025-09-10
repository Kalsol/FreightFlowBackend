<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use App\Notifications\OtpNotification; // Import your OtpNotification class

class AfromessageChannel
{
    /**
     * Send the given notification.
     * This method is called by Laravel's Notification system when the 'afromessage' channel is used.
     *
     * @param mixed $notifiable The entity being notified (e.g., a User model).
     * @param \Illuminate\Notifications\Notification $notification The notification instance.
     * @return void
     */
    public function send(mixed $notifiable, Notification $notification): void
    {
        // Check if the notification has a 'toAfromessage' method
        // This ensures that only notifications designed for this channel are processed.
        if (method_exists($notification, 'toAfromessage')) {
            // Call the 'toAfromessage' method on the notification instance.
            // This is where your actual Afromessage API call logic resides (as implemented in OtpNotification).
            $notification->toAfromessage($notifiable);
        }
    }
}
