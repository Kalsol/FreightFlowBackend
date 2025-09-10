<?php

namespace App\Services\AuthService;

use App\Models\User;
use App\Traits\ApiResponse;
use App\Traits\UserQueries; // Import the UserQueries trait
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Services\OTPService;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log; // Import Log facade for enhanced error logging
use Illuminate\Http\JsonResponse; // Explicitly import JsonResponse

class RegistrationService
{
    // Use ApiResponse trait for consistent API responses (success/error)
    // Use UserQueries trait for common user-related database queries
    use ApiResponse, UserQueries;

    public const OTP_REASON_REGISTRATION = 'Registration';

    protected OTPService $otpService;

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
     * Registers a new user with the provided phone number and password,
     * and sends a verification code.
     *
     * @param array $data Contains 'phone_number' and 'password'.
     * @return JsonResponse
     */
    public function create(array $data): JsonResponse
    {
        $phoneNumber = $data['phone_number'];
        $password = $data['password'];

        // Apply rate limiting to prevent brute-force or spamming registration attempts
        $key = 'registration_attempt:' . $phoneNumber;
        if (RateLimiter::tooManyAttempts($key, 3)) { // Allow 3 attempts per minute per phone number
            $seconds = RateLimiter::availableIn($key);
            return $this->errorResponse("Too many registration attempts. Please try again in $seconds seconds.", 429);
        }
        RateLimiter::hit($key, 60); // Record a hit for the rate limiter

        DB::beginTransaction(); // Start a database transaction for atomicity
        try {
            // Check if a user with this phone number already exists
            // Using the centralized UserQueries trait
            if ($this->findUserByPhoneNumber($phoneNumber)) {
                // Return a generic error message to prevent user enumeration
                return $this->errorResponse('A user with this phone number already exists.', 409); // 409 Conflict
            }

            // Create the new user record
            $user = User::create([
                'phone_number' => $phoneNumber,
                'password' => Hash::make($password), // Hash the password securely
                // 'phone_number_verified_at' => null, // Explicitly set to null if not using migration default
            ]);

            // Store the OTP in the database and dispatch the SMS notification
            $this->otpService->storeOtpInDatabase($user->phone_number, self::OTP_REASON_REGISTRATION);

            DB::commit(); // Commit the transaction if all operations are successful

            // Return a success response, including limited user data
            return $this->successResponse(
                $user->only(['id', 'phone_number']), // Return only relevant user data
                'Your account has been created. A verification code has been sent to your phone.',
                201 // 201 Created
            );
        } catch (\Throwable $e) {
            DB::rollBack(); // Rollback the transaction on error
            Log::error("User registration failed for phone number {$phoneNumber}: " . $e->getMessage(), [
                'exception' => $e,
                'phone_number' => $phoneNumber,
            ]);
            // Return a generic error message to the client, hiding internal details
            return $this->errorResponse('Registration failed. An unexpected error occurred. Please try again later.', 500);
        }
    }
}
