<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\PhoneNumber;

class PhoneVerificationRequest extends FormRequest
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
            'otp_code' => ['required', 'numeric']
        ];
    }


    public function messages()
    {
        return [
            'phone_number.required' => 'Phone Number required',
            'phone_number.numeric' => 'Phone Number must be number',
            'otp_code.required' => 'OTP Code required',
            'otp_code.numeric' => 'OTP Code must be number'
        ];
    }
}
