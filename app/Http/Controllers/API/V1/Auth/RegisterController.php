<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domains\Users\Actions\RegisterUserAction;
use App\Domains\Users\Requests\RegisterUserRequest;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    protected $action;


    public function __construct(RegisterUserAction $action)
    {
        $this->action = $action;
    }

    public function signUp(RegisterUserRequest $request) : JsonResponse
    {
        $user = $this->action->execute($request->validated());
        return response()->json($user, 201);
    }
}
