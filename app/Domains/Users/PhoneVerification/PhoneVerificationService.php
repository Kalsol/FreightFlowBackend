<?php

namespace App\Domains\Users\PhoneVerification;

use App\Domains\Users\PhoneVerification;
use App\Domains\Users\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use App\Domains\Users\Services\OTPService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Traits\UserQueries; // Ensure this trait exists in App\Traits

class PhoneVerificationService
{
    // Use the ApiResponse trait for consistent JSON responses
    // Use the UserQueries trait for common user-related database operations
    use ApiResponse, UserQueries;

    public const OTP_REASON_RESEND_FOR_REGISTRATION = 'ResendForRegistration';

    private OTPService $otpService;

    /**
     * Constructor to inject the OTPService dependency.
     *
     * @param OTPService $otpService The OTP service instance.
     */
    public function __construct(OTPService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Verifies a phone number using an OTP code.
     *
     * @param array $data Contains 'phone_number' and 'otp_code'.
     * @return JsonResponse
     */
    public function verify(array $data): JsonResponse
    {
        $phoneNumber = $data['phone_number'];
        $otpCode = $data['otp_code'];

        // Apply rate limiting to prevent brute-force attacks on OTP verification
        $key = 'verify-attempts:' . $phoneNumber;
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return $this->errorResponse("Too many attempts. Please try again in $seconds seconds.", 429);
        }
        RateLimiter::hit($key, 60); // Record a hit for the rate limiter

        // First, check if a user exists with the provided phone number
        $user = $this->findUserByPhoneNumber($phoneNumber);
        if (!$user) {
            // Return a generic error to prevent user enumeration
            return $this->errorResponse('Invalid OTP code or phone number.', 400);
        }

        // Check if the user is already verified to avoid unnecessary processing
        if ($this->checkUserIsVerified($phoneNumber)) {
            return $this->errorResponse('Phone number is already verified.', 400);
        }

        // Attempt to retrieve a valid (unused and unexpired) verification record
        $verification = $this->getValidVerificationRecord($phoneNumber, $otpCode);

        if (!$verification) {
            // Use a generic error message for security to prevent enumeration attacks
            // This covers cases where OTP is invalid, expired, or already used
            return $this->errorResponse('Invalid OTP code or OTP has expired. Please request a new one.', 400);
        }

        try {
            DB::beginTransaction(); // Start a database transaction for atomicity

            // Mark the found OTP as used
            $verification->is_used = true;
            $verification->save();

            // Verify the user's account by setting phone_number_verified_at
            $this->verifyUserAccount($user);

            DB::commit(); // Commit the transaction if all operations are successful

            return $this->successResponse(["message" => "Your phone number has been successfully verified."], 'Success', 200);

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction on error
            Log::error("Phone verification failed for {$phoneNumber}: " . $e->getMessage());
            return $this->errorResponse('An internal error occurred during verification. Please try again.', 500);
        }
    }

    /**
     * Finds the most recent valid (unused and unexpired) OTP record
     * for a given phone number and verifies the provided OTP code against its hash.
     *
     * @param string $phoneNumber The phone number associated with the OTP.
     * @param string $otpCode The OTP code provided by the user.
     * @return PhoneVerification|null The matching PhoneVerification model instance, or null if not found/valid.
     */
    private function getValidVerificationRecord(string $phoneNumber, string $otpCode): ?PhoneVerification
    {
        // Retrieve the most recent unused and unexpired OTP for the given phone number.
        // Using 'latest()' and 'first()' makes this efficient by only fetching one record.
        $verification = PhoneVerification::where('phone_number', $phoneNumber)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        // If a verification record is found, check if the provided OTP matches its hashed version.
        if ($verification && Hash::check((string)$otpCode, $verification->otp_code)) {
            return $verification;
        }
        return null;
    }

    /**
     * Marks the user's phone number as verified in the database.
     *
     * @param User $user The User model instance to be verified.
     * @return void
     */
    private function verifyUserAccount(User $user): void
    {
        // Only update if the phone number is not already verified
        if ($user->phone_number_verified_at === null) {
            $user->phone_number_verified_at = now();
            $user->save();
        }
    }

    /**
     * Resends a phone verification code to the specified phone number.
     *
     * @param string $phoneNumber The phone number to send the new verification code to.
     * @return JsonResponse
     */
    public function reSendVerificationCode(string $phoneNumber): JsonResponse
    {
        // Apply rate limiting for resend attempts
        $key = 'resend-verification-attempts:' . $phoneNumber;
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return $this->errorResponse("Too many resend attempts. Try again in $seconds seconds.", 429);
        }
        RateLimiter::hit($key, 60);

        // Check if the user exists
        if (!$this->findUserByPhoneNumber($phoneNumber)) {
            return $this->errorResponse('No user found with this phone number.', 404);
        }

        // Check if the user is already verified
        if ($this->checkUserIsVerified($phoneNumber)) {
            return $this->errorResponse('User already verified.', 400);
        }

        // Mark all previous unused OTPs for this phone number as used before sending a new one.
        // This ensures only the latest OTP is valid.
        $this->updatePreviousOtps($phoneNumber);

        try {
            // Store a new OTP in the database and trigger its sending via SMS
            $this->otpService->storeOtpInDatabase($phoneNumber, self::OTP_REASON_RESEND_FOR_REGISTRATION);
            return $this->successResponse(['message' => 'Verification code resent successfully.'], 'Success', 200);
        } catch (\Throwable $e) {
            Log::error("Failed to resend verification code for {$phoneNumber}: " . $e->getMessage());
            return $this->errorResponse('Failed to resend verification code. Please try again later.', 500);
        }
    }

    /**
     * Marks all previous unused OTPs for a given phone number as used.
     * This prevents old OTPs from being reused after a new one is sent.
     *
     * @param string $phoneNumber The phone number whose previous OTPs should be updated.
     * @return void
     */
    private function updatePreviousOtps(string $phoneNumber): void
    {
        PhoneVerification::where('phone_number', $phoneNumber)
            ->where('is_used', false)
            ->update(['is_used' => true]);
    }
}
