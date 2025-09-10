<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\PhoneNumber;
use Illuminate\Validation\Rules\Password;

class RegistrationRequest extends FormRequest
{


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
            'phone_number' => ['required', 'unique:users,phone_number', new PhoneNumber(), 'numeric'],
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)
                    ->mixedCase()       // at least one uppercase and one lowercase
                    ->letters()         // at least one letter
                    ->numbers()         // at least one number
                    ->symbols()         // at least one symbol
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
            'password.confirmed' => 'The password confirmation does not match.',
            'password.min' => 'password must be 8 characters.',
            'password.mixedCase' => 'password contain uppercase and lowercase letter.',
            'password.letters' => 'password must contain letter.',
            'password.numbers' => 'password must contain number.',
            'password.symbols' => 'password must contain symbol.',
        ];
    }

}
