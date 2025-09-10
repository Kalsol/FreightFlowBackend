<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\AuthService\PasswordService;
use App\Rules\PhoneNumber;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    protected $passwordService;

    public function __construct(PasswordService $passwordService)
    {
        $this->passwordService = $passwordService;
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $phoneNumber = $request->input('phone_number');
        return $this->passwordService->sendResetCode($phoneNumber);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $phoneNumber = $request->input('phone_number');
        $newPassword = $request->input('new_password');
        return $this->passwordService->resetPassword($phoneNumber,$newPassword);
    }

    public function verifyForResetPassword(Request $request): JsonResponse
    {
        $phoneNumber = $request->input('phone_number');
        $otp = $request->input('otp_code');
        return $this->passwordService->verifyForResetPassword($otp, $phoneNumber);
    }

    public function changePassword(Request $request): JsonResponse
    {
        $token = $request->bearerToken();
        $currentPassword = $request->input('current_password');
        $newPassword = $request->input('new_password');
        return $this->passwordService->changePassword($currentPassword, $newPassword);
    }
}
