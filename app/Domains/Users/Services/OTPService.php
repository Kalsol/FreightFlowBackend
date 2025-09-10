<?php

namespace App\Domains\Users\Services;

use App\Domains\Users\User;
use App\Domains\Users\PhoneVerification;
use Illuminate\Support\Facades\DB;
use App\Traits\OtpGenerate;
use App\Notifications\SendOtpNotification; // Import the updated OtpNotification
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
// Removed: use App\Services\SMSService; // SMSService is no longer directly used here

class OTPService
{
    use OtpGenerate;

    // Removed SMSService dependency from constructor as it's now handled by the Notification
    // private SMSService $smsService;

    // public function __construct(SMSService $smsService)
    // {
    //     $this->smsService = $smsService;
    // }

    /**
     * Stores the generated OTP in the database and dispatches the OtpNotification.
     * This method is the primary entry point for creating and sending OTPs.
     *
     * @param string $phoneNumber The phone number to which the OTP will be sent.
     * @param string $purpose The reason/purpose for the OTP (e.g., 'Registration', 'Password Reset').
     * @return void
     * @throws \Throwable If database transaction fails.
     */
    public function storeOtpInDatabase(string $contactNumber, string $purpose): void
    {
        $otp = $this->generateOtp(); // Generate a new OTP code

        DB::beginTransaction(); // Start a database transaction for atomicity
        try {
            // Create a new phone verification record in the database
            PhoneVerification::create([
                'phone_number' => $contactNumber,
                'purpose'      => $purpose,
                'otp_code'     => Hash::make($otp), // Hash the OTP before storing it for security
                'expires_at'   => now()->addMinutes(5), // OTP valid for 5 minutes
                'is_used'      => false, // Mark as unused initially
            ]);

            // Find the user to send the notification. This assumes user already exists.
            // If the user doesn't exist at this point (e.g., during registration before user creation),
            // you might need to reconsider when exactly to send the notification or create the user first.
            $user = User::where('contact_number', $contactNumber)->first();

            if ($user) {
                // Dispatch the SendOtpNotification to the user.
                // Laravel's notification system will handle queuing if SendOtpNotification implements ShouldQueue.
                $user->notify(new SendOtpNotification($otp, $purpose, $contactNumber));
                Log::info("OTP for purpose '{$purpose}' stored and SendOtpNotification dispatched for {$contactNumber}.");
            } else {
                Log::warning("User with contact number {$contactNumber} not found. SendOtpNotification was not dispatched after OTP storage.");
                // You might want to throw an exception or handle this case differently
            }

            DB::commit(); // Commit the transaction if the record is created and notification dispatched successfully

        } catch (\Throwable $e) {
            DB::rollBack(); // Rollback the transaction if any error occurs
            Log::error("Failed to store OTP or dispatch OtpNotification for {$contactNumber}: " . $e->getMessage());
            // Re-throw the exception to allow calling methods to handle it
            throw $e;
        }
    }
}
