<?php

namespace App\Domains\Users\Controllers\Auth;


use App\Domains\Users\Actions\RegisterUserAction;
use App\Domains\Users\Requests\RegisterUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class RegisterController extends Controller
{
    /**
     * Handle the incoming user registration request.
     *
     * @param RegisterUserRequest $request
     * @param RegisterUserAction $action
     * @return JsonResponse
     */
    public function signUp(RegisterUserRequest $request, RegisterUserAction $action): JsonResponse
    {
        // The request has been validated by RegistrationRequest.
        // We just pass the data to our action class.
        $response = $action->execute($request->validated());

        return $response;
    }
}
