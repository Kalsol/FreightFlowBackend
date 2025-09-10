<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\PhoneNumber;
use Illuminate\Validation\Rules\Password;

class LogInRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'phone_number' => ['required', new PhoneNumber(), 'numeric'],
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->uncompromised(),  // checks against known data breaches
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'phone_number.required' => 'The phone number is required.',
            'phone_number.unique' => 'The phone number is already taken.',
            'phone_number.numeric' => 'The phone number must be a number.',
            'password.required' => 'The password is required.',
        ];
    }
}
