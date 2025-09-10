<?php

namespace App\Notifications\Channels;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Notifications\Dispatcher;

class AfromessageChannel
{
    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @return void
     */
    public function send(mixed $notifiable, Notification $notification): void
    {
        // Get the message payload from the notification's toAfromessage method
        $message = $notification->toAfromessage($notifiable);
        $url = config('services.afromessage.url');
        $token = config('services.afromessage.token');
        
        // Ensure the API URL and token are configured before sending
        if (!$url || !$token) {
            Log::error('Afromessage API URL or Token is not configured. Please check your services.php file.');
            return;
        }

        try {
            // Make the POST request using Laravel's HTTP client
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ])
            ->post($url, $message);

            // Handle the API response
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['acknowledge']) && $data['acknowledge'] == 'success') {
                    Log::info('Afromessage sent successfully.', ['response' => $response->body()]);
                } else {
                    Log::error('Afromessage API returned a failure acknowledgment.', ['response' => $response->body()]);
                }
            } else {
                Log::error('Failed to send Afromessage.', ['status' => $response->status(), 'response' => $response->body()]);
            }
        } catch (\Throwable $e) {
            Log::error('HTTP Client Error while sending Afromessage: ' . $e->getMessage(), ['exception' => $e]);
        }
    }
}
