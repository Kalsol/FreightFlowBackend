<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http; // Re-introduced Laravel's HTTP Client
use App\Models\User; // Ensure User model is accessible

class OtpNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $otp;
    protected string $purpose;
    protected string $phoneNumber; // Store the phone number for direct use in SMS channel

    /**
     * Create a new notification instance.
     *
     * @param string $otp The One-Time Password to be sent.
     * @param string $purpose The purpose of the OTP (e.g., 'Registration', 'Password Reset').
     * @param string $phoneNumber The recipient's phone number.
     */
    public function __construct(string $otp, string $purpose, string $phoneNumber)
    {
        $this->otp = $otp;
        $this->purpose = $purpose;
        $this->phoneNumber = $phoneNumber; // Assign phone number to property
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param object $notifiable The entity being notified (e.g., a User model).
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // We'll use a custom channel named 'afromessage' for SMS sending.
        // You can also keep 'mail' and 'database' if those are still desired.
        return ['afromessage', 'database'];
    }

    /**
     * Send the notification via the Afromessage custom channel.
     * This method directly handles the HTTP POST request to the Afromessage API.
     *
     * @param object $notifiable The entity being notified (e.g., a User model).
     * @return void
     */
    public function toAfromessage(object $notifiable): void
    {
        // Load these from .env, ensuring they are configured
        $url = env('AFRO_MESSAGE_URL');
        $token = env('AFRO_MESSAGE_TOKEN');
        $from = env('AFRO_MESSAGE_FROM', null);
        $sender = env('SENDER_NAME', null);
        $callback = env('AFRO_MESSAGE_CALLBACK', null);

        // Prepend '251' to the phone number if it doesn't already start with it or '+251'
        $toPhoneNumber = $this->phoneNumber;
        if (!preg_match('/^(251|\+251)/', $toPhoneNumber)) {
            // Remove leading zero if present and add 251
            $toPhoneNumber = '251' . ltrim($toPhoneNumber, '0');
        }

        // Construct the message for the OTP
        $messageContent = "Your OTP for {$this->purpose} is: {$this->otp}";

        // Prepare the request body as an associative array
        $requestBody = [
            "from" => $from,
            "sender" => $sender,
            "to" => $toPhoneNumber,
            "message" => $messageContent,
            "callback" => $callback
        ];

        // Log the exact JSON payload being sent for debugging purposes
        Log::debug("Sending Afromessage API Request for OTP - URL: {$url}, Payload: " . json_encode($requestBody));

        try {
            // Make the POST request using Laravel's HTTP client
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ])
                // ->withoutVerifying() // Consider removing this in production for security
                ->post($url, $requestBody);

            // Handle the API response
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['acknowledge']) && $data['acknowledge'] == 'success') {
                    Log::info("OTP sent successfully to {$toPhoneNumber} for purpose '{$this->purpose}'. API Acknowledge: success. Response: " . $response->body());
                } else {
                    Log::error("Failed to send OTP to {$toPhoneNumber} for purpose '{$this->purpose}'. API Acknowledge: failure. Response: " . $response->body());
                }
            } else {
                Log::error("Failed to send OTP to {$toPhoneNumber} for purpose '{$this->purpose}'. HTTP Code: {$response->status()}. Response: " . $response->body());
            }
        } catch (\Throwable $e) {
            Log::error("HTTP Client Error while sending OTP to {$toPhoneNumber} for purpose '{$this->purpose}': " . $e->getMessage(), [
                'exception' => $e,
                'phone_number' => $toPhoneNumber,
                'purpose' => $this->purpose,
            ]);
        } 
    }

    /**
     * Get the mail representation of the notification.
     * This method is a placeholder if you wish to send OTP via email as well.
     *
     * @param object $notifiable The entity being notified.
     * @return MailMessage
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your OTP Code')
            ->line("Your One-Time Password for {$this->purpose} is: **{$this->otp}**.")
            ->line('This code is valid for 5 minutes.')
            ->line('Please do not share this code with anyone.');
    }

    /**
     * Get the database representation of the notification.
     * This method defines what data is stored in the `notifications` table.
     *
     * @param object $notifiable The entity being notified.
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'otp' => $this->otp,
            'purpose' => $this->purpose,
            'phone_number' => $this->phoneNumber,
            'message' => "Your OTP for {$this->purpose} is: {$this->otp}"
        ];
    }
}
