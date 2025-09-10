<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendOtpNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $otp;
    protected string $purpose;
    protected string $phoneNumber;

    /**
     * Create a new notification instance.
     *
     * @param string $otp The One-Time Password to be sent.
     * @param string $purpose The purpose of the OTP.
     * @param string $phoneNumber The recipient's phone number.
     */
    public function __construct(string $otp, string $purpose, string $phoneNumber)
    {
        $this->otp = $otp;
        $this->purpose = $purpose;
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param object $notifiable The entity being notified.
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Use your custom channel. You must register this channel first.
        return ['afromessage', 'mail'];
    }

    /**
     * Get the data array for the Afromessage custom channel.
     *
     * @param object $notifiable The entity being notified.
     * @return array
     */
    public function toAfromessage(object $notifiable): array
    {
        $from = config('services.afromessage.from');
        $sender = config('services.afromessage.sender');
        $callback = config('services.afromessage.callback');
        
        $toPhoneNumber = $this->phoneNumber;
        if (!preg_match('/^(251|\+251)/', $toPhoneNumber)) {
            $toPhoneNumber = '251' . ltrim($toPhoneNumber, '0');
        }

        $messageContent = "Your OTP for {$this->purpose} is: {$this->otp}";

        return [
            'from' => $from,
            'sender' => $sender,
            'to' => $toPhoneNumber,
            'message' => $messageContent,
            'callback' => $callback,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your OTP Code')
            ->line("Your One-Time Password for {$this->purpose} is: **{$this->otp}**.")
            ->line('This code is valid for 5 minutes.')
            ->line('Please do not share this code with anyone.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
        ];
    }
}
