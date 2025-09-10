<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AuthService\RegistrationService;
use App\Http\Requests\Auth\RegistrationRequest;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    protected $registrationService;


    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    public function signUp(RegistrationRequest $request) : JsonResponse
    {
        $requestedData = $request->validated();
        $response = $this->registrationService->create($requestedData);
        return response()->json($response);
    }
}
