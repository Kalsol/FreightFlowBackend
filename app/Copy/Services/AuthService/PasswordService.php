<?php

namespace App\Services\AuthService;

use App\Models\User;
use App\Services\OTPService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\PhoneVerification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Traits\UserQueries;

class PasswordService
{
    use ApiResponse, UserQueries; // Using UserQueries trait for common user queries
    protected OTPService $otpService;
    protected const OTP_REASON_PASSWORD_RESET = 'Password Reset';

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
     * Sends a password reset code to the user's phone number.
     *
     * @param string $phoneNumber The phone number to send the reset code to.
     * @return JsonResponse
     */
    public function sendResetCode(string $phoneNumber): JsonResponse
    {
        // First, check if the user exists. This is done to prevent sending OTPs
        // to non-existent numbers, though the API response remains generic for security.
        $user = $this->findUserByPhoneNumber($phoneNumber);

        // Apply rate limiting to prevent abuse of the password reset functionality.
        $key = 'password_reset:' . $phoneNumber;
        if (RateLimiter::tooManyAttempts($key, 3)) { // Allow 3 attempts per minute per phone number
            $seconds = RateLimiter::availableIn($key);
            // Return a 429 Too Many Requests error if rate limit is exceeded.
            return $this->errorResponse("Too many attempts. Please try again in $seconds seconds.", 429);
        }
        RateLimiter::hit($key, 60); // Record a hit for the rate limiter

        // **Security Note:** To prevent user enumeration, always return a success status (200 OK)
        // with a generic message, regardless of whether the user exists or not,
        // unless a rate limit is hit. This prevents attackers from knowing if a phone number
        // corresponds to a registered account.
        if (!$user) {
            Log::info("Password reset request for non-existent phone number: {$phoneNumber}. Generic success response sent.");
            return $this->successResponse([], 'If an account exists, a password reset code has been sent.', 200);
        }

        try {
            // Store the OTP in the database and dispatch the SMS notification via the OTPService.
            // This is typically an asynchronous operation (queued job).
            $this->otpService->storeOtpInDatabase($phoneNumber, self::OTP_REASON_PASSWORD_RESET);
            Log::info("Password reset code dispatched for phone number: {$phoneNumber}.");
            return $this->successResponse([], "Password reset code sent successfully.", 200);
        } catch (\Throwable $e) {
            // Log the full exception for debugging purposes, but return a generic error to the client.
            Log::error("Failed to send password reset code for {$phoneNumber}: " . $e->getMessage(), ['exception' => $e]);
            return $this->errorResponse('Failed to send password reset code. Please try again later.', 500);
        }
    }



    /**
     * Verifies the OTP code for password reset and confirms readiness for password change.
     *
     * @param int $otp The OTP code provided by the user.
     * @param string $phoneNumber The phone number associated with the OTP.
     * @return JsonResponse
     */
    public function verifyForResetPassword(int $otp, string $phoneNumber): JsonResponse
    {
        // Apply rate limiting to prevent brute-force attacks on OTP verification.
        $key = 'verify_password_reset:' . $phoneNumber; // Use a specific key for verification attempts.
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return $this->errorResponse("Too many attempts. Try again in $seconds seconds.", 429);
        }
        RateLimiter::hit($key, 60); // Allow 3 attempts per minute for verification.

        // Find the most recent, unused, and unexpired OTP for the given phone number.
        $verification = PhoneVerification::where('phone_number', $phoneNumber)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->latest() // Get the most recent OTP sent for password reset.
            ->first();

        // If no record is found or the OTP hash does not match, return a generic error.
        // This prevents attackers from discerning between invalid, expired, or used OTPs.
        if (!$verification || !Hash::check((string)$otp, $verification->otp_code)) {
            Log::warning("Invalid or expired OTP verification attempt for phone number: {$phoneNumber}.");
            return $this->errorResponse('Invalid OTP code or OTP has expired. Please request a new one.', 400);
        }

        try {
            DB::beginTransaction(); // Start a database transaction.

            // Mark the successfully verified OTP as used to prevent replay attacks.
            $verification->is_used = true;
            $verification->save();

            DB::commit(); // Commit the transaction.

            Log::info("OTP successfully verified for password reset for phone number: {$phoneNumber}.");
            return $this->successResponse([], "OTP verified successfully. You can now reset your password.", 200);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback on exception.
            Log::error("OTP verification for password reset failed for {$phoneNumber}: " . $e->getMessage(), ['exception' => $e]);
            return $this->errorResponse('An error occurred during OTP verification. Please try again.', 500);
        }
    }



    /**
     * Resets the user's password after successful OTP verification.
     *
     * @param string $phoneNumber The phone number of the user.
     * @param string $newPassword The new password for the user.
     * @return JsonResponse
     */
    public function resetPassword(string $phoneNumber, string $newPassword): JsonResponse
    {
        try {
            $user = $this->findUserByPhoneNumber($phoneNumber);
            if (!$user) {
                // If user not found, return an error. This case should ideally
                // be preceded by successful OTP verification.
                Log::warning("Attempted password reset for non-existent user with phone number: {$phoneNumber}.");
                return $this->errorResponse('Unable to reset password. User not found.', 404);
            }

            // Hash the new password securely before saving it to the database.
            $user->password = Hash::make($newPassword);
            $user->save();

            Log::info("Password successfully reset for phone number: {$phoneNumber}.");
            return $this->successResponse([], "Password reset successfully.", 200);
        } catch (\Exception $e) {
            // Log the full exception for debugging.
            Log::error("Failed to reset password for {$phoneNumber}: " . $e->getMessage(), ['exception' => $e]);
            return $this->errorResponse('Failed to reset password. Please try again.', 500);
        }
    }

  
    /**
     * Allows an authenticated user to change their current password.
     *
     * @param string $currentPassword The user's current password.
     * @param string $newPassword The user's new password.
     * @return JsonResponse
     */
    public function changePassword(string $currentPassword, string $newPassword): JsonResponse
    {
        $user = Auth::user(); // Get the currently authenticated user.

        // Ensure there is an authenticated user. This check adds robustness,
        // though middleware typically handles unauthenticated access.
        if (!$user) {
            Log::warning("Attempted to change password without an authenticated user.");
            return $this->errorResponse('Unauthorized: User not authenticated.', 401);
        }

        // Verify the provided current password against the user's stored password hash.
        if (!Hash::check($currentPassword, $user->password)) {
            Log::info("Failed password change attempt for user ID: {$user->phone_number}. Incorrect current password provided.");
            return $this->errorResponse('Current password is incorrect.', 403);
        }

        // Hash and save the new password.
        $user->password = Hash::make($newPassword);
        $user->save();

        Log::info("Password changed successfully for user ID: {$user->id}.");
        return $this->successResponse([], "Password changed successfully.", 200);
    }
}
