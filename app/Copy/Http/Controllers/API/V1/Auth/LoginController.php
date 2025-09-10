<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LogInRequest;
use App\Services\AuthService\AuthenticationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class LoginController extends Controller
{
    protected $authService;

    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Handle user login.
     */
    public function signIn(LogInRequest $request) : JsonResponse
    {
        $credentials = $request->only('phone_number', 'password');
        $response = $this->authService->login($credentials);
        return $response;
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout();
        return response()->json(['message' => 'Logout successful']);
    }

    /**
     * Handle token refresh.
     */
    public function refreshToken(Request $request): JsonResponse
    {
       $token = $this->authService->refreshToken();
       return response()->json(['token' => $token]);
    }
}
