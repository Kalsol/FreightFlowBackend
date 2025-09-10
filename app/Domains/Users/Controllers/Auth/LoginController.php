<?php

namespace App\Domains\Users\Controllers\Auth;

use App\Domains\Users\Requests\LoginRequest;
use App\Domains\Users\Actions\LoginUserAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __construct(private readonly LoginUserAction $action)
    {
    }

    public function signIn(LoginRequest $request): JsonResponse
    {
        $response = $this->action->execute($request->only('contact_number', 'password'));

        return $response;
    }

    public function logout(Request $request): JsonResponse
    {
        $this->action->logout();
        return response()->json(['message' => 'Logout successful']);
    }

    public function refreshToken(Request $request): JsonResponse
    {
        $token = $this->action->refreshToken();
        return response()->json(['token' => $token]);
    }
}


