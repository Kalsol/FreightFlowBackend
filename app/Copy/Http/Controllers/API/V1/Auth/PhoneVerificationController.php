<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService\PhoneVerificationService;
use App\Http\Requests\Auth\PhoneVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PhoneVerificationController extends Controller
{
    private $phoneVerificationService;


    public function __construct(PhoneVerificationService $phoneVerificationService)
    {
        $this->phoneVerificationService = $phoneVerificationService;
    }

    public function verifyPhone(PhoneVerificationRequest $request) :JsonResponse
    {
        $isVerified = $this->phoneVerificationService->verify($request->validated());
        return $isVerified;
    }

    public function reSendVerificationCode(Request $request) :JsonResponse
    {
        $response = $this->phoneVerificationService->reSendVerificationCode($request->phone_number);
        return $response;
    }

}
