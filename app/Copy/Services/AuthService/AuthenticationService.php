<?php

namespace App\Services\AuthService;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Traits\ApiResponse;
use App\Traits\UserQueries; // Import the UserQueries trait
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Import Log facade for enhanced error logging
use Illuminate\Http\JsonResponse; // Explicitly import JsonResponse

class AuthenticationService
{
    // Use ApiResponse trait for consistent API responses (success/error)
    // Use UserQueries trait for common user-related database queries
    use ApiResponse, UserQueries;

    /**
     * Handle user login.
     * Authenticates a user based on phone number and password,
     * generates an API token, and updates last login time.
     *
     * @param array $credentials Contains 'phone_number' and 'password'.
     * @return JsonResponse Returns a success response with user data and token, or an error response.
     */
    public function login(array $credentials): JsonResponse
    {
        $phoneNumber = $credentials['phone_number'];
        $password = $credentials['password'];

        try {
            // Find the user by phone number using the centralized UserQueries trait
            $user = $this->findUserByPhoneNumber($phoneNumber);

            // If user not found, return an error. Use a generic message for security.
            if (!$user) {
                return $this->errorResponse('Invalid credentials.', 401);
            }

            // Check if the user's phone number is verified.
            // Using the centralized UserQueries trait.
            if (!$this->checkUserIsVerified($phoneNumber)) {
                return $this->errorResponse('Please verify your phone number to log in.', 403);
            }

            // Verify the provided password against the hashed password in the database
            if (!Hash::check($password, $user->password)) {
                // Return generic error for invalid credentials to prevent enumeration attacks
                return $this->errorResponse('Invalid credentials.', 401);
            }

            // If authentication is successful:
            // 1. Create a new API token for the user (assuming Laravel Sanctum is configured).
            $token = $user->createToken('auth_token')->plainTextToken;

            // 2. Update the user's last login timestamp.
            $user->last_login_at = now();
            $user->save();

            // 3. Return a successful login response with user data and the token.
            return $this->successResponse(
                [
                    'user' => $user->only(['uuid', 'phone_number', 'name']), // Return only necessary user data
                    'token' => $token,
                ],
                'Login successful',
                200
            );
        } catch (\Exception $e) {
            // Log the full exception details for debugging purposes
            Log::error("User login failed for phone number {$phoneNumber}: " . $e->getMessage(), [
                'exception' => $e,
                'phone_number' => $phoneNumber,
            ]);
            // Return a generic error message to the client, hiding internal details
            return $this->errorResponse('An error occurred during login. Please try again later.', 500);
        }
    }

    /**
     * Refreshes the authentication token for the currently authenticated user.
     * Assumes Laravel Sanctum is managing tokens and `Auth::user()` is available.
     *
     * @return string The new plain text API token.
     * @throws \Exception If no authenticated user is found.
     */
    public function refreshToken(): string
    {
        $user = Auth::user();

        // Ensure there is an authenticated user before attempting to refresh the token
        if (!$user) {
            // In a real application, this should ideally be caught by middleware
            // or handled as an unauthorized access, but here for robustness.
            Log::warning("Attempted to refresh token without an authenticated user.");
            throw new \Exception('Unauthorized: No authenticated user to refresh token for.');
        }

        // Revoke the current token to ensure only one active token per session (optional, depending on strategy)
        // $user->currentAccessToken()->delete();

        // Create and return a new plain text token
        return $user->createToken('auth_token')->plainTextToken;
    }

    /**
     * Handles user logout by revoking all authentication tokens for the current user.
     * Assumes Laravel Sanctum is managing tokens and `Auth::user()` is available.
     *
     * @return void
     */
    public function logout(): void
    {
        $user = Auth::user();

        // If a user is authenticated, revoke all their tokens.
        // This effectively logs them out from all devices using these tokens.
        if ($user) {
            $user->tokens()->delete(); // Revoke all personal access tokens for the user
            Log::info("User ID: {$user->id} logged out successfully and all tokens revoked.");
        } else {
            Log::info("Attempted logout for a non-authenticated user.");
        }
    }
}
