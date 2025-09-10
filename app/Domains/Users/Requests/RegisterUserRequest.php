<?php

namespace App\Domains\Users\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\PhoneNumber;
use Illuminate\Validation\Rules\Password;

class RegisterUserRequest extends FormRequest
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
            'contact_number' => ['required', 'unique:users,contact_number', new PhoneNumber(), 'numeric'],
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
            'contact_number.required' => 'Phone number is required.',
            'contact_number.unique' => 'Phone number is already taken.',
            'contact_number.numeric' => 'Phone number must be a number.',
            'password.required' => ' Password is required.',
            'password.confirmed' => ' Password confirmation does not match.',
            'password.min' => 'Password must be 8 characters.',
            'password.mixedCase' => 'Password must contain uppercase and lowercase letters.',
            'password.letters' => 'Password must contain letters.',
            'password.numbers' => 'Password must contain numbers.',
            'password.symbols' => 'Password must contain symbols.',
        ];
    }

}
