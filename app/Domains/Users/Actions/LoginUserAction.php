<?php

namespace App\Domains\Users\Actions;

use App\Domains\Users\User;
use App\Traits\ApiResponse;
use App\Traits\UserQueries;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginUserAction
{
    use ApiResponse, UserQueries;

    public function execute(array $credentials): JsonResponse
    {
        $contactNumber = $credentials['contact_number'];
        $password = $credentials['password'];

        try {
            /** @var User|null $user */
            $user = $this->findUserByContactNumber($contactNumber);
            if (!$user) {
                return $this->errorResponse('Invalid credentials.', 401);
            }

            if (!$this->checkUserIsVerified($contactNumber)) {
                return $this->errorResponse('Please verify your phone number to log in.', 403);
            }

            if (!Hash::check($password, $user->password)) {
                return $this->errorResponse('Invalid credentials.', 401);
            }

            $token = $user->createToken('auth_token')->plainTextToken;
            $user->last_login_at = now();
            $user->save();

            return $this->successResponse([
                'user' => $user->only(['uuid', 'contact_number', 'name']),
                'token' => $token,
            ], 'Login successful', 200);
        } catch (\Exception $e) {
            Log::error("User login failed for contact number {$contactNumber}: " . $e->getMessage(), [
                'exception' => $e,
                'contact_number' => $contactNumber,
            ]);
            return $this->errorResponse('An error occurred during login. Please try again later.', 500);
        }
    }

    public function refreshToken(): string
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user) {
            Log::warning('Attempted to refresh token without an authenticated user.');
            throw new \Exception('Unauthorized: No authenticated user to refresh token for.');
        }
        return $user->createToken('auth_token')->plainTextToken;
    }

    public function logout(): void
    {
        /** @var User|null $user */
        $user = Auth::user();
        if ($user) {
            $user->tokens()->delete();
            Log::info("User ID: {$user->id} logged out successfully and all tokens revoked.");
        } else {
            Log::info('Attempted logout for a non-authenticated user.');
        }
    }

    /**
     * Lookup a user by contact_number.
     */
    protected function findUserByContactNumber(string $contactNumber): ?User
    {
        return User::query()->where('contact_number', $contactNumber)->first();
    }

    /**
     * Check if user's contact_number is verified.
     * Adjust this according to how you store verification state.
     */
    protected function checkUserIsVerified(string $contactNumber): bool
    {
        $user = $this->findUserByContactNumber($contactNumber);
        if (!$user) {
            return false;
        }
        // If you have phone_number_verified_at, adapt to contact_number if needed
        return (bool) ($user->phone_number_verified_at ?? $user->contact_number_verified_at ?? null);
    }
}


