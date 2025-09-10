<?php

namespace App\Domains\Users\PhoneVerification;

use App\Http\Controllers\Controller;
use App\Domains\Users\PhoneVerification\PhoneVerificationService;
use App\Domains\Users\PhoneVerification\OtpRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerifyPhoneController extends Controller
{
    private $phoneVerificationService;


    public function __construct(PhoneVerificationService $phoneVerificationService)
    {
        $this->phoneVerificationService = $phoneVerificationService;
    }

    public function verifyPhone(OtpRequest $request) :JsonResponse
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
